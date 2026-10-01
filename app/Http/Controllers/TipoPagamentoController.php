<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\TipoPagamento;
use Illuminate\Http\Request;

class TipoPagamentoController extends Controller
{
    private const SESSION_KEY = 'tipo_pagamento.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('tipo_pagamento.index'));
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $tiposPagamento = TipoPagamento::query()
            ->when($request->tipo, function ($query) use ($request) {
                $query->where('tipo_plano_fin', 'like', '%' . $request->tipo . '%');
            })
            ->orderBy('id_tipo_pagamento')
            ->paginate(10)
            ->withQueryString();

        return view('view_tipo_pagamento.index', compact('tiposPagamento'));
    }

    public function create()
    {
        return view('view_tipo_pagamento.create', [
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_plano_fin' => 'required|string|max:45',
        ]);

        TipoPagamento::create($validated);

        return redirect($this->urlIndex())
            ->with('success', 'Tipo de pagamento criado com sucesso!');
    }

    public function show(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $tipo = TipoPagamento::findOrFail($id);

        return view('view_tipo_pagamento.show', [
            'tipo' => $tipo,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $tipo = TipoPagamento::findOrFail($id);

        return view('view_tipo_pagamento.edit', [
            'tipo' => $tipo,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);
        $tipo = TipoPagamento::findOrFail($id);

        $validated = $request->validate([
            'tipo_plano_fin' => 'required|string|max:45',
        ]);

        $tipo->update($validated);

        return redirect($this->urlIndex())
            ->with('success', 'Tipo de pagamento atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $tipo = TipoPagamento::findOrFail($id);

        $tipo->delete();

        return redirect()->route('tipo_pagamento.index')
            ->with('success', 'Tipo de pagamento excluído com sucesso!');
    }
}
