<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use App\Models\DetalhesFormaPag;
use App\Models\TipoPagamento;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotificacaoController extends Controller
{

    public function index(Request $request)
    {
        $tiposPagamento = TipoPagamento::all();

        $query = Notificacao::with([
            'detalheFormaPag.formaPagamento.financeiro.orcamento',
            'detalheFormaPag.formaPagamento.tipoPagamento'
        ]);

        if ($request->filled('id_financeiro')) {
            $query->whereHas('detalheFormaPag.formaPagamento', function ($query) use ($request) {
                $query->where(
                    'financeiro_id_fin',
                    $request->id_financeiro
                );
            });
        }

        if ($request->filled('id_orcamento')) {
            $query->whereHas('detalheFormaPag.formaPagamento.financeiro', function ($query) use ($request) {
                $query->where(
                    'orcamento_id_orcamento',
                    $request->id_orcamento
                );
            });
        }

        if ($request->filled('cliente')) {
            $query->whereHas('detalheFormaPag.formaPagamento.financeiro', function ($query) use ($request) {
                $query->where(
                    'fin_nome_cliente',
                    'like',
                    '%' . trim($request->cliente) . '%'
                );
            });
        }

        if ($request->filled('forma_pagamento')) {
            $query->whereHas('detalheFormaPag.formaPagamento.tipoPagamento', function ($query) use ($request) {
                $query->where(
                    'tipo_plano_fin',
                    $request->forma_pagamento
                );
            });
        }

        $notificacoes = $query
            ->orderBy('id_det_forma')
            ->orderBy('id_notificacao')
            ->paginate(10)
            ->withQueryString();

        $grupos = $notificacoes->getCollection()->groupBy(function ($notificacao) {
            $detalhe = $notificacao->detalheFormaPag;
            $formaPagamento = $detalhe?->formaPagamento;
            $financeiro = $formaPagamento?->financeiro;

            return $financeiro?->id_fin . '-' . $formaPagamento?->id_forma_pag;
        });

        return view('view_notificacao.index', compact('grupos', 'notificacoes', 'tiposPagamento'));
    }


    public function create(Request $request)
    {
        $detalheForma = null;
        $proximoTipo = 1;

        if ($request->has('id_det_forma')) {

            $detalheForma = DetalhesFormaPag::with([
                'formaPagamento.financeiro.orcamento',
                'formaPagamento.tipoPagamento',
                'notificacoes'
            ])->find($request->input('id_det_forma'));

            if ($detalheForma) {
                $proximoTipo = $detalheForma->notificacoes->count() + 1;
            }
        }

        return view('view_notificacao.create', compact(
            'detalheForma',
            'proximoTipo'
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
                ->route('notificacao.show', $validatedData['id_det_forma'])
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

    public function show($id)
    {
        $detalheForma = DetalhesFormaPag::with([
            'formaPagamento.financeiro.orcamento',
            'formaPagamento.tipoPagamento',
            'notificacoes'
        ])->findOrFail($id);

        return view('view_notificacao.show', compact('detalheForma'));
    }

    public function edit($id)
    {
        $notificacao = Notificacao::with([
            'detalheFormaPag.formaPagamento.financeiro.orcamento',
            'detalheFormaPag.formaPagamento.tipoPagamento'
        ])->findOrFail($id);

        return view('view_notificacao.edit', compact('notificacao'));
    }

    public function update(Request $request, $id)
    {
        try {
            $notificacao = Notificacao::findOrFail($id);

            $validatedData = $request->validate([
                'id_det_forma' => 'sometimes|required|integer|exists:detalhes_forma_pag,id_det_forma',
                'not_tipo' => 'sometimes|required|string|max:45',
                'not_descricao' => 'sometimes|required|string',
            ]);

            $notificacao->update($validatedData);

            return redirect()
                ->route('notificacao.show', $notificacao->id_det_forma)
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
        $notificacao = Notificacao::findOrFail($id);
        $notificacao->delete();

        return redirect()
            ->route('notificacao.show', $notificacao->id_det_forma)
            ->with('success', 'Notificação excluída com sucesso!');
    }
}
