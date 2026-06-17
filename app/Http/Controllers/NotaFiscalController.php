<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotaFiscal;
use App\Models\Orcamento;
use App\Models\TipoFluxoCaixa;
use App\Models\Movimentacao;

class NotaFiscalController extends Controller
{
    public function index()
    {
        $notas = NotaFiscal::with(['orcamento', 'tipo', 'movimentacao'])->get();
        $tipos = TipoFluxoCaixa::all();

        return view('view_nota_fiscal.index', compact('notas', 'tipos'));;
    }

    public function create()
    {
        $orcamentos = Orcamento::all();
        $tipos = TipoFluxoCaixa::all();
        $movimentacoes = Movimentacao::all();

        return view('view_nota_fiscal.create', compact('orcamentos', 'tipos', 'movimentacoes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'orcamento_id_orcamento' => 'required|integer|exists:orcamento,id_orcamento',
            'nota_numero' => 'required|string|max:100',
            'nota_id_tipo' => 'required|integer|exists:tipo_fluxo_caixa,id_tipo_fluxo',
            'nota_data' => 'required|date',
            'nota_id_movimentacao' => 'required|integer|exists:movimentacao,id_movimentacao',
            'nota_valor' => 'required|numeric',
            'nota_desc' => 'required|string|max:255',
        ]);

        NotaFiscal::create($validatedData);

        return redirect()->route('nota_fiscal.index')
            ->with('success', 'Nota fiscal cadastrada com sucesso!');
    }

    public function show(string $id)
    {
        $nota = NotaFiscal::with(['orcamento', 'tipo', 'movimentacao'])->findOrFail($id);

        return view('view_nota_fiscal.show', compact('nota'));
    }

    public function edit(string $id)
    {
        $nota = NotaFiscal::findOrFail($id);
        $orcamentos = Orcamento::all();
        $tipos = TipoFluxoCaixa::all();
        $movimentacoes = Movimentacao::all();

        return view('view_nota_fiscal.edit', compact('nota', 'orcamentos', 'tipos', 'movimentacoes'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'orcamento_id_orcamento' => 'required|integer|exists:orcamento,id_orcamento',
            'nota_numero' => 'required|string|max:100',
            'nota_id_tipo' => 'required|integer|exists:tipo_fluxo_caixa,id_tipo_fluxo',
            'nota_data' => 'required|date',
            'nota_id_movimentacao' => 'required|integer|exists:movimentacao,id_movimentacao',
            'nota_valor' => 'required|numeric',
            'nota_desc' => 'required|string|max:255',
        ]);

        $nota = NotaFiscal::findOrFail($id);
        $nota->update($validatedData);

        return redirect()->route('nota_fiscal.index')
            ->with('success', 'Nota fiscal atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        $nota = NotaFiscal::findOrFail($id);
        $nota->delete();

        return redirect()->route('nota_fiscal.index')
            ->with('success', 'Nota fiscal excluída com sucesso!');
    }
}
