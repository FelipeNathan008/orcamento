<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FluxoCaixa;
use App\Models\TipoFluxoCaixa;
use App\Models\Movimentacao;

class FluxoCaixaController extends Controller
{
    public function index()
    {
        $fluxos = FluxoCaixa::with(['tipo', 'movimentacao'])->get();
        return view('view_fluxo_caixa.index', compact('fluxos'));
    }

    public function create()
    {
        $tipos = TipoFluxoCaixa::all();
        $movimentacoes = Movimentacao::all();

        return view('view_fluxo_caixa.create', compact('tipos', 'movimentacoes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'flu_data_despesa' => 'required|date',
            'flu_id_tipo' => 'required|integer',
            'flu_id_movimentacao' => 'required|integer',
            'flu_valor' => 'required|numeric',
            'flu_num_doc' => 'nullable|string|max:255',
            'flu_desc' => 'required|string|max:180',
        ]);

        FluxoCaixa::create($validatedData);

        return redirect()->route('fluxo_caixa.index')
            ->with('success', 'Registro de fluxo criado com sucesso!');
    }

    public function show(string $id)
    {
        $fluxo = FluxoCaixa::with(['tipo', 'movimentacao'])->findOrFail($id);
        return view('view_fluxo_caixa.show', compact('fluxo'));
    }

    public function edit(string $id)
    {
        $fluxo = FluxoCaixa::findOrFail($id);
        $tipos = TipoFluxoCaixa::all();
        $movimentacoes = Movimentacao::all();

        return view('view_fluxo_caixa.edit', compact('fluxo', 'tipos', 'movimentacoes'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'flu_data_despesa' => 'required|date',
            'flu_id_tipo' => 'required|integer',
            'flu_id_movimentacao' => 'required|integer',
            'flu_valor' => 'required|numeric',
            'flu_num_doc' => 'nullable|string|max:255',
            'flu_desc' => 'required|string|max:180',
        ]);

        $fluxo = FluxoCaixa::findOrFail($id);
        $fluxo->update($validatedData);

        return redirect()->route('fluxo_caixa.index')
            ->with('success', 'Registro de fluxo atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $fluxo = FluxoCaixa::findOrFail($id);
        $fluxo->delete();

        return redirect()->route('fluxo_caixa.index')
            ->with('success', 'Registro de fluxo excluído com sucesso!');
    }
}