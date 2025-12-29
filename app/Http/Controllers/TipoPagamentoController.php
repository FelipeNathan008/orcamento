<?php

namespace App\Http\Controllers;

use App\Models\TipoPagamento;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TipoPagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tiposPagamento  = TipoPagamento::all();
        return view('view_tipo_pagamento.index', compact('tiposPagamento'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('view_tipo_pagamento.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tipo_plano_fin' => 'required|string|max:45'
            ]);

            TipoPagamento::create($validated);

            return redirect()->route('tipo_pagamento.index')
                ->with('success', 'Tipo de pagamento criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar tipo de pagamento: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $tipo = TipoPagamento::findOrFail($id);
        return view('tipo_pagamento.show', compact('tipo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $tipo = TipoPagamento::findOrFail($id);
        return view('view_tipo_pagamento.edit', compact('tipo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $tipo = TipoPagamento::findOrFail($id);

            $validated = $request->validate([
                'tipo_plano_fin' => 'required|string|max:45'
            ]);

            $tipo->update($validated);

            return redirect()->route('tipo_pagamento.index', $id)
                ->with('success', 'Tipo de pagamento atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar tipo de pagamento: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $tipo = TipoPagamento::findOrFail($id);
        $tipo->delete();

        return redirect()->route('tipo_pagamento.index')
            ->with('success', 'Tipo de pagamento excluído com sucesso!');
    }
}
