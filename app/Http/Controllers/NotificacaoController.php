<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use App\Models\DetalhesFormaPag;
use App\Models\TipoPagamento;
use App\Helpers\CryptHelper;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotificacaoController extends Controller
{
    public function index(Request $request, $id_det_forma = null)
    {
        $tiposPagamento = TipoPagamento::all();

        $query = Notificacao::with([
            'detalheFormaPag.formaPagamento.financeiro.orcamento',
            'detalheFormaPag.formaPagamento.tipoPagamento'
        ]);

        if ($id_det_forma) {
            $id_det_forma = CryptHelper::decrypt($id_det_forma);
            $query->where('id_det_forma', $id_det_forma);
        }

        if ($request->filled('id_financeiro')) {
            $query->whereHas('detalheFormaPag.formaPagamento', function ($query) use ($request) {
                $query->where('financeiro_id_fin', $request->id_financeiro);
            });
        }

        if ($request->filled('id_orcamento')) {
            $query->whereHas('detalheFormaPag.formaPagamento.financeiro', function ($query) use ($request) {
                $query->where('orcamento_id_orcamento', $request->id_orcamento);
            });
        }

        if ($request->filled('cliente')) {
            $query->whereHas('detalheFormaPag.formaPagamento.financeiro', function ($query) use ($request) {
                $query->where('fin_nome_cliente', 'like', '%' . trim($request->cliente) . '%');
            });
        }

        if ($request->filled('forma_pagamento')) {
            $query->whereHas('detalheFormaPag.formaPagamento.tipoPagamento', function ($query) use ($request) {
                $query->where('tipo_plano_fin', $request->forma_pagamento);
            });
        }

        $todasNotificacoes = $query
            ->orderBy('id_det_forma')
            ->orderBy('id_notificacao')
            ->get();

        $gruposTodos = $todasNotificacoes->groupBy(function ($notificacao) {
            $detalhe = $notificacao->detalheFormaPag;
            $formaPagamento = $detalhe?->formaPagamento;
            $financeiro = $formaPagamento?->financeiro;

            return $financeiro?->id_fin . '-' . $formaPagamento?->id_forma_pag;
        });

        $porPagina = 10;
        $paginaAtual = $request->input('page', 1);

        $totalGrupos = $gruposTodos->count();

        $grupos = $gruposTodos
            ->forPage($paginaAtual, $porPagina);

        $notificacoes = new \Illuminate\Pagination\LengthAwarePaginator(
            $grupos,
            $totalGrupos,
            $porPagina,
            $paginaAtual,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('view_notificacao.index', compact(
            'grupos',
            'notificacoes',
            'tiposPagamento'
        ));
    }

    public function create(Request $request, $id_det_forma)
    {
        $id_det_forma = CryptHelper::decrypt($id_det_forma);

        $detalheForma = DetalhesFormaPag::with([
            'formaPagamento.financeiro.orcamento',
            'formaPagamento.tipoPagamento',
            'notificacoes'
        ])->findOrFail($id_det_forma);

        $proximoTipo = $detalheForma->notificacoes->count() + 1;

        $filtros = $request->only([
            'id_financeiro',
            'id_orcamento',
            'cliente',
            'forma_pagamento',
            'page',
        ]);

        return view('view_notificacao.create', compact(
            'detalheForma',
            'proximoTipo',
            'filtros'
        ));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'id_det_forma' => 'required|integer|exists:detalhes_forma_pag,id_det_forma',
                'not_tipo' => 'required|string|max:45',
                'not_descricao' => 'required|string',
            ]);

            Notificacao::create($validatedData);

            return redirect()
                ->route('notificacao.show', CryptHelper::encrypt($validatedData['id_det_forma']))
                ->with('success', 'Notificação criada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Não foi possível criar a Notificação: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);

        $detalheForma = DetalhesFormaPag::with([
            'formaPagamento.financeiro.orcamento',
            'formaPagamento.tipoPagamento',
            'notificacoes'
        ])->findOrFail($id);

        $filtros = $request->only([
            'id_financeiro',
            'id_orcamento',
            'cliente',
            'forma_pagamento',
            'page',
        ]);

        return view('view_notificacao.show', compact(
            'detalheForma',
            'filtros'
        ));
    }

    public function edit(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);

        $notificacao = Notificacao::with([
            'detalheFormaPag.formaPagamento.financeiro.orcamento',
            'detalheFormaPag.formaPagamento.tipoPagamento'
        ])->findOrFail($id);

        $filtros = $request->only([
            'id_financeiro',
            'id_orcamento',
            'cliente',
            'forma_pagamento',
            'page',
        ]);

        return view('view_notificacao.edit', compact(
            'notificacao',
            'filtros'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $id = CryptHelper::decrypt($id);

            $notificacao = Notificacao::findOrFail($id);

            $validatedData = $request->validate([
                'id_det_forma' => 'sometimes|required|integer|exists:detalhes_forma_pag,id_det_forma',
                'not_tipo' => 'sometimes|required|string|max:45',
                'not_descricao' => 'sometimes|required|string',
            ]);

            $notificacao->update($validatedData);

            $filtros = $request->only([
                'id_financeiro',
                'id_orcamento',
                'cliente',
                'forma_pagamento',
                'page',
            ]);

            return redirect()
                ->route('notificacao.show', array_merge(
                    ['id' => CryptHelper::encrypt($notificacao->id_det_forma)],
                    $filtros
                ))
                ->with('success', 'Notificação atualizada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Não foi possível atualizar a Notificação: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $id = CryptHelper::decrypt($id);

        $notificacao = Notificacao::findOrFail($id);
        $idDetalhe = $notificacao->id_det_forma;

        $notificacao->delete();

        return redirect()
            ->route('notificacao.show', CryptHelper::encrypt($idDetalhe))
            ->with('success', 'Notificação excluída com sucesso!');
    }
}
