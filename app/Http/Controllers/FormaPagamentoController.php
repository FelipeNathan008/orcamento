<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\Financeiro;
use App\Models\TipoPagamento;
use App\Models\DetalhesFormaPag;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\Cobranca;
use App\Models\ContaBancaria;
use App\Models\DetalhesCobranca;
use Carbon\Carbon;
use App\Models\FluxoCaixa;
use App\Models\Movimentacao;
use App\Models\SaldoConta;
use App\Models\TipoFluxoCaixa;

class FormaPagamentoController extends Controller
{
    public function index(Request $request)
    {
        $id = array_key_first($request->query());

        if (!$id) {
            return redirect()->route('financeiro.index');
        }

        $contas = ContaBancaria::all();

        $financeiros = Financeiro::with('orcamento')->get();

        $formasPagamento = FormaPagamento::where('financeiro_id_fin', $id)
            ->with([
                'financeiro',
                'tipoPagamento',
                'detalhes',
                'conta'
            ])
            ->get();

        foreach ($formasPagamento as $forma) {

            foreach ($forma->detalhes as $parcela) {

                $diasAtraso = Carbon::parse($parcela->det_forma_data_venc)
                    ->diffInDays(now(), false);

                if (
                    $diasAtraso > 3 &&
                    $parcela->det_situacao === 'Acordo'
                ) {
                    DetalhesFormaPag::where(
                        'id_det_forma',
                        $parcela->id_det_forma
                    )->update([
                        'det_situacao' => 'Inadimplencia'
                    ]);

                    DetalhesCobranca::where(
                        'id_det_forma',
                        $parcela->id_det_forma
                    )->update([
                        'det_cobr_status' => 'Inadimplencia'
                    ]);

                    $parcela->det_situacao = 'Inadimplencia';
                }

                $parcela->dias_atraso = $diasAtraso;

                $parcela->classe_vencimento = '';

                if (!in_array($parcela->det_situacao, ['Pago', 'Quitado'])) {

                    if ($diasAtraso > 3) {
                        $parcela->classe_vencimento =
                            'bg-red-200 text-red-800 font-semibold';
                    } elseif ($diasAtraso > 0) {
                        $parcela->classe_vencimento =
                            'bg-yellow-200 text-yellow-800 font-semibold';
                    }
                }

                $parcela->status_exibicao = $parcela->det_situacao;

                $parcela->cor_status = match ($parcela->status_exibicao) {
                    'Pago', 'Quitado' =>
                    'bg-green-100 text-green-700',

                    'Não pago' =>
                    'bg-yellow-100 text-yellow-700',

                    'Acordo' =>
                    'bg-blue-100 text-blue-700',

                    'Inadimplencia' =>
                    'bg-red-100 text-red-700',

                    default =>
                    'bg-gray-100 text-gray-700',
                };
            }

            $parcelasElegiveis = $forma->detalhes->filter(function ($parcela) {

                return $parcela->dias_atraso > 3
                    && !in_array(
                        $parcela->det_situacao,
                        ['Pago', 'Quitado']
                    );
            });

            if ($parcelasElegiveis->isEmpty()) {
                continue;
            }

            $tipo = $forma->tipo_pagamento_id_tipo;

            $tipoCobranca = match ($tipo) {
                1 => 1,
                2 => 2,
                3 => 3,
                4 => 4,
                default => 1
            };

            $cobranca = Cobranca::where(
                'cobr_id_fin',
                $forma->financeiro_id_fin
            )
                ->where('cobr_id_tipo', $tipoCobranca)
                ->first();

            if (!$cobranca) {

                $cobranca = Cobranca::create([
                    'cobr_id_fin' => $forma->financeiro_id_fin,
                    'cobr_id_tipo' => $tipoCobranca,
                    'cobr_cliente' => $forma->financeiro->fin_nome_cliente,
                    'cobr_id_orc' => $forma->financeiro->orcamento_id_orcamento,
                    'cobr_status' => 'Débito',
                ]);
            } elseif ($cobranca->cobr_status === 'Quitado') {

                $cobranca->update([
                    'cobr_status' => 'Débito'
                ]);
            }

            foreach ($parcelasElegiveis as $parcela) {

                $existe = DetalhesCobranca::where(
                    'cobranca_id',
                    $cobranca->id_cobranca
                )
                    ->where(
                        'id_det_forma',
                        $parcela->id_det_forma
                    )
                    ->exists();

                if (!$existe) {

                    DetalhesCobranca::create([
                        'cobranca_id' => $cobranca->id_cobranca,
                        'det_cobr_valor_parcela' =>
                        $parcela->det_forma_valor_parcela,
                        'det_cobr_data_venc' =>
                        $parcela->det_forma_data_venc,
                        'det_cobr_status' => 'Débito',
                        'id_det_forma' => $parcela->id_det_forma
                    ]);
                }
            }
        }

        $financeiroSelecionado = $financeiros->firstWhere(
            'id_fin',
            $id
        );

        $valorTotal = $financeiroSelecionado->fin_valor_total ?? 0;

        // Soma de TODAS as formas com prazo = Entrada (independente da qtd de parcelas)
        $entrada = $formasPagamento
            ->where('forma_prazo', 'Entrada')
            ->sum('forma_valor');

        // Valor pago:
        // 1) Entrada com qtd_parcela = 1 -> conta o forma_valor inteiro (pagamento único)
        $pagoEntradaUnica = $formasPagamento
            ->where('forma_prazo', 'Entrada')
            ->where('forma_qtd_parcela', 1)
            ->sum('forma_valor');

        // 2) Entrada com qtd_parcela > 1 OU Parcelado -> soma só as parcelas com situação Pago/Quitado
        $pagoParcelado = $formasPagamento
            ->filter(function ($forma) {
                return $forma->forma_prazo === 'Parcelado'
                    || ($forma->forma_prazo === 'Entrada' && $forma->forma_qtd_parcela > 1);
            })
            ->sum(function ($forma) {
                return $forma->detalhes
                    ->whereIn('det_situacao', ['Pago', 'Quitado'])
                    ->sum('det_forma_valor_parcela');
            });

        // 3) À vista
        $pagoAVista = $formasPagamento
            ->where('forma_prazo', 'À vista')
            ->where('forma_qtd_parcela', 1)
            ->sum('forma_valor');

        $valorPago = $pagoEntradaUnica + $pagoParcelado + $pagoAVista;

        // Valor negociado: soma de tudo que NÃO é Entrada (ou seja, total de formas - entrada)
        $valorFormas = $formasPagamento->sum('forma_valor');
        $valorNegociado = max($valorFormas - $entrada, 0);

        // Saldo devedor
        $valorFaltante = max($valorTotal - $valorPago, 0);

        return view(
            'view_forma_pagamento.index',
            compact(
                'formasPagamento',
                'financeiros',
                'id',
                'contas',
                'financeiroSelecionado',
                'valorPago',
                'valorTotal',
                'valorFaltante',
                'entrada',
                'valorNegociado'
            )
        );
    }

    public function darBaixa(Request $request, $id)
    {
        $parcela = DetalhesFormaPag::find($id);

        if (!$parcela) {
            return back()->with('error', 'Parcela não encontrada.');
        }

        $parcela->det_forma_data_pagamento = $request->data_pagamento;

        if (in_array($parcela->det_situacao, ['Acordo', 'Inadimplencia'])) {
            $novoStatus = 'Quitado';
        } else {
            $novoStatus = 'Pago';
        }

        $parcela->det_situacao = $novoStatus;
        $parcela->save();

        $forma = $parcela->formaPagamento;
        $financeiro = $forma->financeiro;
        $tipoVenda = TipoFluxoCaixa::where('tipo_flu_nome', 'Venda')->first();

        if ($tipoVenda) {
            $fluxo = FluxoCaixa::create([
                'flu_data_despesa' => $request->data_pagamento,
                'flu_id_tipo' => $tipoVenda->id_tipo_fluxo,
                'flu_id_movimentacao' => 1,
                'conta_bancaria_id' => $forma->conta_bancaria_id ?? null,
                'flu_valor' => $parcela->det_forma_valor_parcela,
                'flu_tipo_fiscal' => 'OC',
                'flu_num_doc' => $financeiro->orcamento_id_orcamento,
                'flu_desc' => 'Pagamento de parcela - orçamento ' . $financeiro->orcamento_id_orcamento,
            ]);

            $mov = Movimentacao::find($fluxo->flu_id_movimentacao);

            $saldoConta = SaldoConta::firstOrCreate(
                [
                    'id_conta_bancaria_id' => $forma->conta_bancaria_id
                ],
                [
                    'saldo_conta_valor' => 0
                ]
            );

            $nomeMov = strtolower(trim($mov->mov_nome));

            if (str_contains($nomeMov, 'entrada')) {

                $saldoConta->saldo_conta_valor += $parcela->det_forma_valor_parcela;
            }

            $saldoConta->save();
        }

        $financeiroId = $parcela->formaPagamento->financeiro_id_fin;
        $tipo = $parcela->formaPagamento->tipo_pagamento_id_tipo;

        $tipoCobranca = match ($tipo) {
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            default => 1
        };

        $cobranca = Cobranca::where('cobr_id_fin', $financeiroId)
            ->where('cobr_id_tipo', $tipoCobranca)
            ->first();

        if ($cobranca) {
            DetalhesCobranca::where('cobranca_id', $cobranca->id_cobranca)
                ->where('id_det_forma', $parcela->id_det_forma)
                ->update([
                    'det_cobr_status' => 'Quitado'
                ]);

            $existePendente = DetalhesCobranca::where('cobranca_id', $cobranca->id_cobranca)
                ->whereIn('det_cobr_status', ['Débito', 'Inadimplencia'])
                ->exists();

            if (!$existePendente) {
                $cobranca->update([
                    'cobr_status' => 'Quitado'
                ]);
            }
        }

        return back()->with('success', 'Parcela baixada com sucesso!');
    }

    public function voltarNaoPago($id)
    {
        $parcela = DetalhesFormaPag::findOrFail($id);

        if ($parcela->det_situacao === 'Quitado') {
            $parcela->det_situacao = 'Acordo';
        } else {
            $parcela->det_situacao = 'Não pago';
        }

        $parcela->save();

        $tipo = $parcela->formaPagamento->tipo_pagamento_id_tipo;

        $tipoCobranca = match ($tipo) {
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            default => 1
        };

        $cobranca = Cobranca::where('cobr_id_fin', $parcela->formaPagamento->financeiro_id_fin)
            ->where('cobr_id_tipo', $tipoCobranca)
            ->first();

        if ($cobranca) {
            DetalhesCobranca::where('cobranca_id', $cobranca->id_cobranca)
                ->where('id_det_forma', $parcela->id_det_forma)
                ->update([
                    'det_cobr_status' => 'Débito'
                ]);

            $existePendente = DetalhesCobranca::where('cobranca_id', $cobranca->id_cobranca)
                ->whereIn('det_cobr_status', ['Débito', 'Inadimplencia'])
                ->exists();

            if ($existePendente) {
                $cobranca->update([
                    'cobr_status' => 'Débito'
                ]);
            }
        }

        return back()->with('success', 'Parcela atualizada com sucesso!');
    }

    public function create(Request $request)
    {
        $financeiros = Financeiro::all();
        $tiposPagamento = TipoPagamento::all();
        $formasPagamento = FormaPagamento::with(['financeiro', 'tipoPagamento'])->get();
        $contas = ContaBancaria::all();

        return view('view_forma_pagamento.create', compact(
            'financeiros',
            'tiposPagamento',
            'formasPagamento',
            'contas'
        ));
    }

    public function store(Request $request)
    {
        try {
            $request->merge([
                'forma_valor' => trim(str_replace(['R$', '.', ','], ['', '', '.'], $request->forma_valor))
            ]);

            // Determina o comportamento real com base em prazo + qtd de parcelas
            $qtdParcelas = (int) $request->forma_qtd_parcela;
            $usaParcelas = $request->forma_prazo === 'Parcelado'
                || ($request->forma_prazo === 'Entrada' && $qtdParcelas > 1);
            $usaDataUnica = $request->forma_prazo === 'À vista'
                || ($request->forma_prazo === 'Entrada' && $qtdParcelas <= 1);

            $validatedData = $request->validate([
                'financeiro_id_fin' => 'required|exists:financeiro,id_fin',
                'tipo_pagamento_id_tipo' => 'required|exists:tipo_pagamento,id_tipo_pagamento',
                'conta_bancaria_id' => 'nullable|exists:conta_bancaria,id_conta',
                'forma_valor' => 'required|numeric|min:0',
                'forma_mes' => 'required|integer|min:1|max:12',
                'forma_descricao' => 'required|string|max:120',
                'forma_prazo' => 'required|in:À vista,Parcelado,Entrada', // <-- Entrada adicionado
                'forma_qtd_parcela' => 'required|integer|min:1',
                'forma_data' => $usaDataUnica
                    ? 'required|date|before_or_equal:today'
                    : 'nullable|date',
                'datas_parcelas' => $usaParcelas
                    ? 'required|array|min:1'
                    : 'nullable|array',
                'datas_parcelas.*' => 'date',
                'valores_parcelas' => $usaParcelas
                    ? 'required|array|min:1'
                    : 'nullable|array',
                'valores_parcelas.*' => 'numeric|min:0',
            ]);

            return DB::transaction(function () use ($request, $validatedData, $usaParcelas, $usaDataUnica) {
                $dadosForma = [
                    'financeiro_id_fin' => $validatedData['financeiro_id_fin'],
                    'tipo_pagamento_id_tipo' => $validatedData['tipo_pagamento_id_tipo'],
                    'conta_bancaria_id' => $validatedData['conta_bancaria_id'] ?? null,
                    'forma_valor' => $validatedData['forma_valor'],
                    'forma_mes' => $validatedData['forma_mes'],
                    'forma_descricao' => $validatedData['forma_descricao'],
                    'forma_prazo' => $validatedData['forma_prazo'],
                    'forma_qtd_parcela' => $validatedData['forma_qtd_parcela'],
                ];

                if ($usaDataUnica) {
                    $dadosForma['forma_data'] = $validatedData['forma_data'];
                }

                $formapag = FormaPagamento::create($dadosForma);

                $financeiro = Financeiro::findOrFail($validatedData['financeiro_id_fin']);

                // Mudanças de status
                $valorTotal = $financeiro->fin_valor_total;
                $valorPagoTotal = FormaPagamento::where('financeiro_id_fin', $validatedData['financeiro_id_fin'])
                    ->sum('forma_valor');

                $aguardando = DB::table('status_mercadoria')->where('id_status_merc', 1)->value('status_merc_nome');
                $realizado  = DB::table('status_mercadoria')->where('id_status_merc', 2)->value('status_merc_nome');

                $financeiro->fin_status = ($valorPagoTotal >= $valorTotal) ? $realizado : $aguardando;
                $financeiro->save();

                if ($valorPagoTotal >= $valorTotal) {
                    DB::table('log_status')
                        ->where('log_id_orcamento', $financeiro->orcamento_id_orcamento)
                        ->where('status_mercadoria_id_status', 2)
                        ->update(['log_situacao' => 1]);
                } else {
                    DB::table('log_status')
                        ->where('log_id_orcamento', $financeiro->orcamento_id_orcamento)
                        ->where('status_mercadoria_id_status', 2)
                        ->update(['log_situacao' => 0]);
                }

                // Pagamento único (À vista OU Entrada com 1 parcela)
                if ($usaDataUnica) {
                    $tipoVenda = TipoFluxoCaixa::where('tipo_flu_nome', 'Venda')->first();

                    if (!$tipoVenda) {
                        throw new \Exception('Tipo "Venda" não encontrado na tabela tipo_fluxo_caixa.');
                    }

                    $fluxo = FluxoCaixa::create([
                        'flu_data_despesa' => $validatedData['forma_data'],
                        'flu_id_tipo' => $tipoVenda->id_tipo_fluxo,
                        'flu_id_movimentacao' => 1,
                        'conta_bancaria_id' => $validatedData['conta_bancaria_id'] ?? null,
                        'flu_valor' => $validatedData['forma_valor'],
                        'flu_tipo_fiscal' => 'OC',
                        'flu_num_doc' => $financeiro->orcamento_id_orcamento,
                        'flu_desc' => 'Pagamento da venda recebida do orçamento ' . $financeiro->orcamento_id_orcamento,
                    ]);

                    $mov = Movimentacao::find($fluxo->flu_id_movimentacao);

                    if ($mov && $validatedData['conta_bancaria_id']) {
                        $saldoConta = SaldoConta::firstOrCreate(
                            ['id_conta_bancaria_id' => $validatedData['conta_bancaria_id']],
                            ['saldo_conta_valor' => 0]
                        );

                        $nomeMov = strtolower(trim($mov->mov_nome));

                        if (str_contains($nomeMov, 'entrada')) {
                            $saldoConta->saldo_conta_valor += $validatedData['forma_valor'];
                        }

                        if (str_contains($nomeMov, 'saída') || str_contains($nomeMov, 'saida')) {
                            $saldoConta->saldo_conta_valor -= $validatedData['forma_valor'];
                        }

                        $saldoConta->save();
                    }
                }

                // Múltiplas parcelas (Parcelado OU Entrada com 2+ parcelas)
                if ($usaParcelas) {
                    foreach ($validatedData['datas_parcelas'] as $i => $dataParcela) {
                        DetalhesFormaPag::create([
                            'id_forma_pag' => $formapag->id_forma_pag,
                            'det_forma_data_venc' => $dataParcela,
                            'det_forma_valor_parcela' => $validatedData['valores_parcelas'][$i],
                            'det_situacao' => 'Não Pago',
                        ]);
                    }
                }

                return redirect('/forma_pagamento?' . $validatedData['financeiro_id_fin'])
                    ->with('success', 'Forma de Pagamento criada com sucesso!');
            });
        } catch (ValidationException $e) {
            return redirect('/forma_pagamento?' . $request->financeiro_id_fin)
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar a forma de pagamento: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $formaPagamento = FormaPagamento::findOrFail($id);
        return view('view_forma_pagamento.show', compact('formaPagamento'));
    }

    public function destroy($id)
    {
        $formaPagamento = FormaPagamento::findOrFail($id);
        $financeiroId = $formaPagamento->financeiro_id_fin;

        $tipo = $formaPagamento->tipo_pagamento_id_tipo;

        $tipoCobranca = match ($tipo) {
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            default => 1
        };

        $cobranca = Cobranca::where('cobr_id_fin', $financeiroId)
            ->where('cobr_id_tipo', $tipoCobranca)
            ->first();

        if ($cobranca) {
            $idsForma = $formaPagamento->detalhes->pluck('id_det_forma');

            DetalhesCobranca::where('cobranca_id', $cobranca->id_cobranca)
                ->whereIn('id_det_forma', $idsForma)
                ->delete();

            if (!DetalhesCobranca::where('cobranca_id', $cobranca->id_cobranca)->exists()) {
                $cobranca->delete();
            }
        }

        DetalhesFormaPag::where('id_forma_pag', $formaPagamento->id_forma_pag)->delete();
        $formaPagamento->delete();

        $financeiro = Financeiro::findOrFail($financeiroId);

        $valorTotal = $financeiro->fin_valor_total;
        $valorPagoTotal = FormaPagamento::where('financeiro_id_fin', $financeiroId)->sum('forma_valor');

        $aguardando = DB::table('status_mercadoria')->where('id_status_merc', 1)->value('status_merc_nome');
        $realizado  = DB::table('status_mercadoria')->where('id_status_merc', 2)->value('status_merc_nome');

        $financeiro->fin_status = ($valorPagoTotal >= $valorTotal) ? $realizado : $aguardando;
        $financeiro->save();

        DB::table('log_status')
            ->where('log_id_orcamento', $financeiro->orcamento_id_orcamento)
            ->where('status_mercadoria_id_status', 2)
            ->update(['log_situacao' => ($valorPagoTotal >= $valorTotal) ? 1 : 0]);

        return redirect('/forma_pagamento?' . $financeiroId)
            ->with('success', 'Forma de Pagamento removida com sucesso!');
    }
}
