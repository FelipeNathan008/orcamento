<?php

namespace App\Http\Controllers;

use App\Models\FormaPagamento;
use App\Models\TipoPagamento;
use App\Models\DetalhesFormaPag;
use App\Models\JurosMulta;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Services\AtualizarStatusParcelas;

class CobrancaController extends Controller
{
    public function index(Request $request, AtualizarStatusParcelas $atualizarStatusParcelas)
    {
        $atualizarStatusParcelas->executar();
        $tiposPagamento = TipoPagamento::all();
        $query = FormaPagamento::with([
            'financeiro.orcamento',
            'tipoPagamento',
            'detalhes' => function ($query) {
                $query->whereIn('det_situacao', [
                    'Atrasado',
                    'Acordo',
                    'Inadimplencia'
                ]);
            }
        ])
            ->whereHas('detalhes', function ($query) {
                $query->whereIn('det_situacao', [
                    'Atrasado',
                    'Acordo',
                    'Inadimplencia'
                ]);
            });

        if ($request->filled('id_financeiro')) {
            $query->where(
                'financeiro_id_fin',
                $request->id_financeiro
            );
        }

        if ($request->filled('id_orcamento')) {
            $query->whereHas('financeiro', function ($query) use ($request) {
                $query->where(
                    'orcamento_id_orcamento',
                    $request->id_orcamento
                );
            });
        }

        if ($request->filled('cliente')) {
            $query->whereHas('financeiro', function ($query) use ($request) {
                $query->where(
                    'fin_nome_cliente',
                    'like',
                    '%' . trim($request->cliente) . '%'
                );
            });
        }

        if ($request->filled('tipo_pagamento')) {
            $query->whereHas('tipoPagamento', function ($query) use ($request) {
                $query->where(
                    'tipo_plano_fin',
                    'like',
                    '%' . trim($request->tipo_pagamento) . '%'
                );
            });
        }

        $formasPagamento = $query
            ->orderBy('id_forma_pag', 'desc')
            ->paginate(10)
            ->withQueryString();

        foreach ($formasPagamento as $forma) {

            foreach ($forma->detalhes as $parcela) {

                $parcela->cor_status = match ($parcela->det_situacao) {
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

        return view('view_cobranca.index', compact('formasPagamento', 'tiposPagamento'));
    }

    public function create()
    {
        abort(404);
    }

    public function store(Request $request)
    {
        abort(404);
    }

    public function show($id)
    {
        $formaPagamento = FormaPagamento::with([
            'financeiro.orcamento',
            'tipoPagamento',
            'detalhes'
        ])->findOrFail($id);

        return view('view_cobranca.show', compact('formaPagamento'));
    }

    public function edit(Request $request, $id)
    {
        $detalhe = DetalhesFormaPag::findOrFail($id);

        $jurosMulta = JurosMulta::first();

        $filtros = $request->only([
            'id_financeiro',
            'id_orcamento',
            'cliente',
            'tipo_pagamento',
            'page',
        ]);

        return view('view_cobranca.edit', compact(
            'detalhe',
            'jurosMulta',
            'filtros'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $detalhe = DetalhesFormaPag::findOrFail($id);

            $validated = $request->validate([
                'det_forma_data_venc' => 'required|date',
                'det_forma_valor_parcela' => 'required|numeric|min:0',
                'det_situacao' => 'required|string|max:45',
                'desconto_multa' => 'nullable|numeric|min:0',
                'desconto_juros' => 'nullable|numeric|min:0',
                'data_original' => 'required|date',
            ]);

            $valorOriginal = (float) $detalhe->det_forma_valor_original;

            $dataOriginal = Carbon::parse(
                $detalhe->det_forma_data_venc
            );

            $novaData = Carbon::parse(
                $validated['det_forma_data_venc']
            );

            $diasAtraso = max(
                0,
                $dataOriginal->diffInDays($novaData, false)
            );

            $jurosMulta = JurosMulta::first();

            $indiceMulta = (float) ($jurosMulta->indice_multa ?? 2);
            $indiceJuros = (float) ($jurosMulta->indice_juros ?? 1);

            $multaCalculada =
                $valorOriginal * ($indiceMulta / 100);

            $jurosCalculado =
                $valorOriginal *
                ($indiceJuros / 100 / 30) *
                $diasAtraso;

            $descontoMulta = min(
                $multaCalculada,
                max(0, (float) ($validated['desconto_multa'] ?? 0))
            );

            $descontoJuros = min(
                $jurosCalculado,
                max(0, (float) ($validated['desconto_juros'] ?? 0))
            );

            $multaFinal =
                max(0, $multaCalculada - $descontoMulta);

            $jurosFinal =
                max(0, $jurosCalculado - $descontoJuros);

            $valorFinal =
                $valorOriginal +
                $multaFinal +
                $jurosFinal;

            $detalhe->update([
                'det_forma_valor_parcela' => $valorFinal,
                'det_forma_data_venc' => $validated['det_forma_data_venc'],
                'det_situacao' => 'Acordo',
            ]);

            return redirect()
                ->route('cobranca.index', $request->only([
                    'id_financeiro',
                    'id_orcamento',
                    'cliente',
                    'tipo_pagamento',
                    'page',
                ]))
                ->with('success', 'Acordo realizado com sucesso!');
        } catch (ValidationException $e) {

            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->with('error', 'Erro ao realizar acordo: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        abort(404);
    }
}
