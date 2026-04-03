<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use Illuminate\Http\Request;
use App\Models\TipoFluxoCaixa;
use PhpParser\Node\Expr\AssignOp\Mod;

class TipoFluxoCaixaController extends Controller
{
    public function index()
    {
        $tiposFluxo = TipoFluxoCaixa::all();
        return view('view_tipo_fluxo_caixa.index', compact('tiposFluxo'));
    }

    public function create()
    {
        $movimentacoes = Movimentacao::all();
        return view('view_tipo_fluxo_caixa.create', compact('movimentacoes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tipo_flu_nome' => 'required|string|max:120',
            'tipo_despesa' => 'required|string|max:90',
            'tipo_desc' => 'required|string|max:180',
        ]);

        TipoFluxoCaixa::create($validatedData);

        return redirect()->route('tipo_fluxo_caixa.index')
            ->with('success', 'Tipo de fluxo de caixa criado com sucesso!');
    }

    public function show(string $id)
    {
        $tipoFluxo = TipoFluxoCaixa::findOrFail($id);
        return view('view_tipo_fluxo_caixa.show', compact('tipoFluxo'));
    }

    public function edit(string $id)
    {
        $tipoFluxo = TipoFluxoCaixa::findOrFail($id);
        $movimentacoes = Movimentacao::all();

        return view('view_tipo_fluxo_caixa.edit', compact('tipoFluxo', 'movimentacoes'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'tipo_flu_nome' => 'required|string|max:120',
            'tipo_despesa' => 'required|string|max:90',
            'tipo_desc' => 'required|string|max:180',
        ]);

        $tipoFluxo = TipoFluxoCaixa::findOrFail($id);
        $tipoFluxo->update($validatedData);

        return redirect()->route('tipo_fluxo_caixa.index')
            ->with('success', 'Tipo de fluxo de caixa atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $tipoFluxo = TipoFluxoCaixa::findOrFail($id);
        $tipoFluxo->delete();

        return redirect()->route('tipo_fluxo_caixa.index')
            ->with('success', 'Tipo de fluxo de caixa excluído com sucesso!');
    }
}
