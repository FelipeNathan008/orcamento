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
        $financeiros = Financeiro::all();

        $formasPagamento = FormaPagamento::where('financeiro_id_fin', $id)
            ->with(['financeiro', 'tipoPagamento', 'detalhes'])
            ->get();

        foreach ($formasPagamento as $forma) {
            foreach ($forma->detalhes as $parcela) {
                $diasAtraso = Carbon::parse($parcela->det_forma_data_venc)
                    ->diffInDays(now(), false);

                if ($diasAtraso > 3 && $parcela->det_situacao === 'Acordo') {
                    DetalhesFormaPag::where('id_det_forma', $parcela->id_det_forma)
                        ->update([
                            'det_situacao' => 'Inadimplencia'
                        ]);

                    DetalhesCobranca::where('id_det_forma', $parcela->id_det_forma)
                        ->update([
                            'det_cobr_status' => 'Inadimplencia'
                        ]);
                }
            }

            $parcelasElegiveis = $forma->detalhes->filter(function ($parcela) {
                $diasAtraso = Carbon::parse($parcela->det_forma_data_venc)
                    ->diffInDays(now(), false);

                return $diasAtraso > 3
                    && !in_array($parcela->det_situacao, ['Pago', 'Quitado']);
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

            $cobranca = Cobranca::where('cobr_id_fin', $forma->financeiro_id_fin)
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
                $existe = DetalhesCobranca::where('cobranca_id', $cobranca->id_cobranca)
                    ->where('id_det_forma', $parcela->id_det_forma)
                    ->exists();

                if (!$existe) {
                    DetalhesCobranca::create([
                        'cobranca_id' => $cobranca->id_cobranca,
                        'det_cobr_valor_parcela' => $parcela->det_forma_valor_parcela,
                        'det_cobr_data_venc' => $parcela->det_forma_data_venc,
                        'det_cobr_status' => 'Débito',
                        'id_det_forma' => $parcela->id_det_forma
                    ]);
                }
            }
        }

        return view('view_forma_pagamento.index', compact(
            'formasPagamento',
            'financeiros',
            'id',
            'contas'
        ));
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
            FluxoCaixa::create([
                'flu_data_despesa' => $request->data_pagamento,
                'flu_id_tipo' => $tipoVenda->id_tipo_fluxo,
                'flu_id_movimentacao' => 1,
                'conta_bancaria_id' => $forma->conta_bancaria_id ?? null,
                'flu_valor' => $parcela->det_forma_valor_parcela,
                'flu_tipo_fiscal' => 'OC',
                'flu_num_doc' => $financeiro->orcamento_id_orcamento,
                'flu_desc' => 'Pagamento de parcela - orçamento ' . $financeiro->orcamento_id_orcamento,
            ]);
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

            $validatedData = $request->validate([
                'financeiro_id_fin' => 'required|exists:financeiro,id_fin',
                'tipo_pagamento_id_tipo' => 'required|exists:tipo_pagamento,id_tipo_pagamento',
                'conta_bancaria_id' => 'nullable|exists:conta_bancaria,id_conta',
                'forma_valor' => 'required|numeric|min:0',
                'forma_mes' => 'required|integer|min:1|max:12',
                'forma_descricao' => 'required|string|max:120',
                'forma_prazo' => 'required|in:À vista,Parcelado',
                'forma_qtd_parcela' => 'required|integer|min:1',
                'forma_data' => 'required_if:forma_prazo,À vista|nullable|date|before_or_equal:today',
                'datas_parcelas' => 'required_if:forma_prazo,Parcelado|array|min:1',
                'datas_parcelas.*' => 'date',
                'valores_parcelas' => 'required_if:forma_prazo,Parcelado|array|min:1',
                'valores_parcelas.*' => 'numeric|min:0',
            ]);

            return DB::transaction(function () use ($request, $validatedData) {
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

                if ($validatedData['forma_prazo'] === 'À vista') {
                    $dadosForma['forma_data'] = $validatedData['forma_data'];
                }

                $formapag = FormaPagamento::create($dadosForma);

                $financeiro = Financeiro::findOrFail($validatedData['financeiro_id_fin']);

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

                if ($validatedData['forma_prazo'] === 'À vista') {
                    $tipoVenda = TipoFluxoCaixa::where('tipo_flu_nome', 'Venda')->first();

                    if (!$tipoVenda) {
                        throw new \Exception('Tipo "Venda" não encontrado na tabela tipo_fluxo_caixa.');
                    }

                    FluxoCaixa::create([
                        'flu_data_despesa' => $validatedData['forma_data'],
                        'flu_id_tipo' => $tipoVenda->id_tipo_fluxo,
                        'flu_id_movimentacao' => 1,
                        'conta_bancaria_id' => $validatedData['conta_bancaria_id'] ?? null,
                        'flu_valor' => $validatedData['forma_valor'],
                        'flu_tipo_fiscal' => 'OC',
                        'flu_num_doc' => $financeiro->orcamento_id_orcamento,
                        'flu_desc' => 'Pagamento da venda recebida do orçamento ' . $financeiro->orcamento_id_orcamento,
                    ]);
                }

                if ($validatedData['forma_prazo'] === 'Parcelado') {
                    foreach ($validatedData['datas_parcelas'] as $i => $dataParcela) {
                        DetalhesFormaPag::create([
                            'id_forma_pag' => $formapag->id_forma_pag,
                            'det_forma_data_venc' => $dataParcela,
                            'det_forma_valor_parcela' => $validatedData['valores_parcelas'][$i],
                            'det_situacao' => 'Acordo',
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
