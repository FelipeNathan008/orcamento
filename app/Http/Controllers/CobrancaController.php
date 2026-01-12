<?php

namespace App\Http\Controllers;

use App\Models\Cobranca;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CobrancaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cobrancas = Cobranca::with([
            'tipoPagamento',
            'detalhesCobranca' => function ($query) {
                $query->whereDate('det_cobr_data_venc', '<=', \Carbon\Carbon::today());
            }
        ])
        ->whereHas('detalhesCobranca')
        ->get();

        return view('view_cobranca.index', compact('cobrancas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('view_cobranca.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'cobr_cliente'      => 'required|string|max:90',
                'cobr_id_fin'       => 'required|integer',
                'cobr_id_orc'       => 'required|integer',
                'cobr_id_tipo'      => 'required|integer',
                'cobr_status'       => 'required|string|max:45',
            ]);

            Cobranca::create($validated);

            return redirect()->route('cobranca.index')
                ->with('success', 'Cobrança criada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar cobrança: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $cobranca = Cobranca::findOrFail($id);
        return view('view_cobranca.show', compact('cobranca'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $cobranca = Cobranca::findOrFail($id);
        return view('view_cobranca.edit', compact('cobranca'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $cobranca = Cobranca::findOrFail($id);

            $validated = $request->validate([
                'cobr_cliente'      => 'required|string|max:90',
                'cobr_id_fin'       => 'required|integer',
                'cobr_id_orc'       => 'required|integer',
                'cobr_id_tipo'      => 'required|integer',
                'cobr_status'       => 'required|string|max:45',
            ]);

            $cobranca->update($validated);

            return redirect()->route('cobranca.index')
                ->with('success', 'Cobrança atualizada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar cobrança: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $cobranca = Cobranca::findOrFail($id);
        $cobranca->delete();

        return redirect()->route('cobranca.index')
            ->with('success', 'Cobrança excluída com sucesso!');
    }
}
