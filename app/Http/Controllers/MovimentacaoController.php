<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movimentacao;

class MovimentacaoController extends Controller
{
    public function index()
    {
        $movimentacoes = Movimentacao::all();
        return view('view_movimentacao.index', compact('movimentacoes'));
    }

    public function create()
    {
        return view('view_movimentacao.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'mov_nome' => 'required|string|max:60',
        ]);

        Movimentacao::create($validatedData);

        return redirect()->route('movimentacao.index')
                         ->with('success', 'Movimentação criada com sucesso!');
    }

    public function show(string $id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        return view('movimentacao.show', compact('movimentacao'));
    }

    public function edit(string $id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        return view('view_movimentacao.edit', compact('movimentacao'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'mov_nome' => 'required|string|max:60',
        ]);

        $movimentacao = Movimentacao::findOrFail($id);
        $movimentacao->update($validatedData);

        return redirect()->route('movimentacao.index')
                         ->with('success', 'Movimentação atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        $movimentacao->delete();

        return redirect()->route('movimentacao.index')
                         ->with('success', 'Movimentação excluída com sucesso!');
    }
}