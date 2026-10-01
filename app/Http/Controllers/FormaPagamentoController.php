<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\Financeiro;
use App\Models\TipoPagamento;
use App\Models\DetalhesFormaPag;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\ContaBancaria;
use Carbon\Carbon;
use App\Models\FluxoCaixa;
use App\Models\Movimentacao;
use App\Models\SaldoConta;
use App\Models\TipoFluxoCaixa;
use App\Services\AtualizarStatusParcelas;

class FormaPagamentoController extends Controller
{
    public function index(Request $request, AtualizarStatusParcelas $atualizarStatusParcelas)
    {

        $atualizarStatusParcelas->executar();

        $id = $request->query('id_fin');

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

                    'Não Pago' =>
                    'bg-yellow-100 text-yellow-700',

                    'Acordo' =>
                    'bg-blue-100 text-blue-700',

                    'Atrasado', 'Inadimplencia' =>
                    'bg-red-100 text-red-700',

                    default =>
                    'bg-gray-100 text-gray-700',
                };
            }
        }

        $financeiroSelecionado = $financeiros->firstWhere(
            'id_fin',
            $id
        );

        $valorTotal = $financeiroSelecionado->fin_valor_total ?? 0;

        $entrada = $formasPagamento
            ->where('forma_prazo', 'Entrada')
            ->sum('forma_valor');

        $valorNegociado = $formasPagamento
            ->where('forma_prazo', '!=', 'Entrada')
            ->sum('forma_valor');

        $valorCompletado = $entrada + $valorNegociado;

        $pagamentoCompleto = abs(
            (float) $valorCompletado - (float) $valorTotal
        ) < 0.01;

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
                'valorNegociado',
                'valorCompletado',
                'pagamentoCompleto'
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

        return back()->with('success', 'Parcela baixada com sucesso!');
    }

    public function voltarNaoPago($id)
    {
        $parcela = DetalhesFormaPag::findOrFail($id);

        if ($parcela->det_situacao === 'Quitado') {
            return back()->with(
                'error',
                'Parcelas com status Quitado não podem voltar para Não Pago.'
            );
        }

        if ($parcela->det_situacao !== 'Pago') {
            return back()->with(
                'error',
                'Somente parcelas com status Pago podem voltar para Não Pago.'
            );
        }

        $forma = $parcela->formaPagamento;

        if ($forma) {

            $financeiro = $forma->financeiro;

            $fluxo = FluxoCaixa::where('flu_num_doc', $financeiro->orcamento_id_orcamento)
                ->where('flu_valor', $parcela->det_forma_valor_parcela)
                ->where('flu_desc', 'Pagamento de parcela - orçamento ' . $financeiro->orcamento_id_orcamento)
                ->latest('id_fluxo')
                ->first();

            if ($fluxo) {

                $mov = Movimentacao::find($fluxo->flu_id_movimentacao);

                if ($mov && $forma->conta_bancaria_id) {

                    $nomeMov = strtolower(trim($mov->mov_nome));

                    $saldoConta = SaldoConta::where(
                        'id_conta_bancaria_id',
                        $forma->conta_bancaria_id
                    )->first();

                    if ($saldoConta) {

                        if (str_contains($nomeMov, 'entrada')) {
                            $saldoConta->saldo_conta_valor -= $fluxo->flu_valor;
                        }

                        if (
                            str_contains($nomeMov, 'saída') ||
                            str_contains($nomeMov, 'saida')
                        ) {
                            $saldoConta->saldo_conta_valor += $fluxo->flu_valor;
                        }

                        $saldoConta->save();
                    }
                }

                $fluxo->delete();
            }
        }

        $parcela->det_situacao = 'Não Pago';
        $parcela->det_forma_data_pagamento = null;
        $parcela->save();

        return back()->with(
            'success',
            'Parcela voltou para Não Pago e o fluxo de caixa foi removido.'
        );
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
                'forma_prazo' => 'required|in:À vista,Parcelado,Entrada',
                'forma_qtd_parcela' => ['required', 'integer', 'between:1,50',],
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
            ], [
                'forma_qtd_parcela.required' => 'Informe a quantidade de parcelas.',
                'forma_qtd_parcela.integer' => 'A quantidade de parcelas deve ser um número inteiro.',
                'forma_qtd_parcela.between' => 'A quantidade de parcelas deve estar entre 1 e 50.',

                'forma_qtd_parcela.min' => 'A quantidade mínima de parcelas é 1.',
                'forma_qtd_parcela.max' => 'A quantidade máxima de parcelas é 50.',
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

                // Mudança de Status

                $valorTotal = (float) $financeiro->fin_valor_total;

                $formas = FormaPagamento::where(
                    'financeiro_id_fin',
                    $validatedData['financeiro_id_fin']
                )->get();

                // Valor Entrada
                $valorEntrada = $formas
                    ->where('forma_prazo', 'Entrada')
                    ->sum('forma_valor');
                // Valor Negociado
                $valorNegociado = $formas
                    ->where('forma_prazo', '!=', 'Entrada')
                    ->sum('forma_valor');

                $valorCompletado = $valorEntrada + $valorNegociado;

                $pagamentoCompleto = abs($valorCompletado - $valorTotal) < 0.01;

                $aguardando = DB::table('status_mercadoria')
                    ->where('id_status_merc', 1)
                    ->value('status_merc_nome');

                $realizado = DB::table('status_mercadoria')
                    ->where('id_status_merc', 2)
                    ->value('status_merc_nome');

                if ($pagamentoCompleto) {
                    $financeiro->fin_status = $realizado;
                } else {
                    $financeiro->fin_status = $aguardando;
                }

                $financeiro->save();

                // Atualiza o log do status "Pagamento realizado"
                DB::table('log_status')
                    ->where('log_id_orcamento', $financeiro->orcamento_id_orcamento)
                    ->where('status_mercadoria_id_status', 2)
                    ->update([
                        'log_situacao' => $pagamentoCompleto ? 1 : 0
                    ]);

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
                            'det_forma_valor_original' => $validatedData['valores_parcelas'][$i],
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

        DetalhesFormaPag::where('id_forma_pag', $formaPagamento->id_forma_pag)->delete();
        $formaPagamento->delete();

        $financeiro = Financeiro::findOrFail($financeiroId);

        $valorTotal = (float) $financeiro->fin_valor_total;
        $formas = FormaPagamento::where(
            'financeiro_id_fin',
            $financeiroId
        )->get();

        $valorEntrada = $formas
            ->where('forma_prazo', 'Entrada')
            ->sum('forma_valor');
        $valorNegociado = $formas
            ->where('forma_prazo', '!=', 'Entrada')
            ->sum('forma_valor');

        $valorCompletado = $valorEntrada + $valorNegociado;

        $pagamentoCompleto = abs($valorCompletado - $valorTotal) < 0.01;

        $aguardando = DB::table('status_mercadoria')
            ->where('id_status_merc', 1)
            ->value('status_merc_nome');

        $realizado = DB::table('status_mercadoria')
            ->where('id_status_merc', 2)
            ->value('status_merc_nome');

        $financeiro->fin_status = $pagamentoCompleto
            ? $realizado
            : $aguardando;

        $financeiro->save();

        DB::table('log_status')
            ->where('log_id_orcamento', $financeiro->orcamento_id_orcamento)
            ->where('status_mercadoria_id_status', 2)
            ->update([
                'log_situacao' => $pagamentoCompleto ? 1 : 0
            ]);

        return redirect('/forma_pagamento?' . $financeiroId)
            ->with('success', 'Forma de Pagamento removida com sucesso!');
    }
}
