<?php

namespace App\Http\Controllers;

use App\Models\Notificacao;
use App\Models\Cobranca;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class NotificacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Notificacao::with('cobranca');

        if ($request->filled('cobranca_id')) {
            $query->where('cobranca_id_cobranca', $request->cobranca_id);
        }

        $notificacoes = $query->get();

        return view('view_notificacao.index', compact('notificacoes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $cobranca = null;
        $proximoTipo = 1; // começa com Aviso Bancário

        if ($request->has('cobranca_id')) {
            $cobranca = Cobranca::with('notificacoes')->find($request->input('cobranca_id'));

            if ($cobranca) {
                $qtdNotificacoes = $cobranca->notificacoes->count();
                $proximoTipo = $qtdNotificacoes + 1;
            }
        }

        return view('view_notificacao.create', compact('cobranca', 'proximoTipo'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'cobranca_id_cobranca' => 'required|integer|exists:cobranca,id_cobranca',
                'not_tipo' => 'required|string|max:45',
                'not_descricao' => 'required|string',
            ]);

            Notificacao::create($validatedData);

            return redirect()
                ->route('notificacao.index')
                ->with('success', 'Notificação criada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível criar a Notificação: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $notificacao = Notificacao::findOrFail($id);
        return view('view_notificacao.show', compact('notificacao'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $notificacao = Notificacao::findOrFail($id);
        return view('view_notificacao.edit', compact('notificacao'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $notificacao = Notificacao::findOrFail($id);

            $validatedData = $request->validate([
                'cobranca_id_cobranca' => 'sometimes|required|integer|exists:cobranca,id_cobranca',
                'not_tipo' => 'sometimes|required|string|max:45',
                'not_descricao' => 'sometimes|required|string',
            ]);

            $notificacao->update($validatedData);

            return redirect()
                ->route('notificacao.index', $notificacao->id_notificacao)
                ->with('success', 'Notificação atualizada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível atualizar a Notificação: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $notificacao = Notificacao::findOrFail($id);
        $notificacao->delete();

        return redirect()
            ->route('notificacao.index')
            ->with('success', 'Notificação excluída com sucesso!');
    }
}
