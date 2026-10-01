<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\TipoFluxoCaixa;
use Illuminate\Http\Request;

class TipoFluxoCaixaController extends Controller
{
    private const SESSION_KEY = 'tipo_fluxo_caixa.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('tipo_fluxo_caixa.index'));
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $tiposFluxo = TipoFluxoCaixa::query()
            ->when($request->nome, function ($query) use ($request) {
                $query->where('tipo_flu_nome', 'like', '%' . $request->nome . '%');
            })
            ->when($request->tipo_despesa, function ($query) use ($request) {
                $query->where('tipo_despesa', $request->tipo_despesa);
            })
            ->orderBy('id_tipo_fluxo')
            ->paginate(10)
            ->withQueryString();

        return view('view_tipo_fluxo_caixa.index', compact('tiposFluxo'));
    }

    public function create()
    {
        return view('view_tipo_fluxo_caixa.create', ['urlVoltar' => $this->urlIndex()]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tipo_flu_nome' => 'required|string|max:120',
            'tipo_despesa' => 'required|in:Fixa,Variavel',
            'tipo_desc' => 'required|string|max:180',
        ]);

        TipoFluxoCaixa::create($validatedData);

        return redirect($this->urlIndex())
            ->with('success', 'Tipo de fluxo de caixa criado com sucesso!');
    }

    public function show(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $tipoFluxo = TipoFluxoCaixa::findOrFail($id);

        return view('view_tipo_fluxo_caixa.show', [
            'tipoFluxo' => $tipoFluxo,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $tipoFluxo = TipoFluxoCaixa::findOrFail($id);

        return view('view_tipo_fluxo_caixa.edit', [
            'tipoFluxo' => $tipoFluxo,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);
        $tipoFluxo = TipoFluxoCaixa::findOrFail($id);

        $validatedData = $request->validate([
            'tipo_flu_nome' => 'required|string|max:120',
            'tipo_despesa' => 'required|in:Fixa,Variavel',
            'tipo_desc' => 'required|string|max:180',
        ]);

        $tipoFluxo->update($validatedData);

        return redirect($this->urlIndex())
            ->with('success', 'Tipo de fluxo de caixa atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $tipoFluxo = TipoFluxoCaixa::findOrFail($id);

        $tipoFluxo->delete();

        return redirect()->route('tipo_fluxo_caixa.index')
            ->with('success', 'Tipo de fluxo de caixa excluído com sucesso!');
    }
}
