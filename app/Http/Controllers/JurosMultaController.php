<?php

namespace App\Http\Controllers;

use App\Models\JurosMulta;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JurosMultaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jurosMulta = JurosMulta::all();
        return view('view_juros_multa.index', compact('jurosMulta'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('view_juros_multa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'indice_juros' => 'required|numeric',
                'indice_multa' => 'required|numeric'
            ]);

            JurosMulta::create($validated);

            return redirect()->route('juros_multa.index')
                ->with('success', 'Valores de juros e multa criados com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao criar registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $juros = JurosMulta::findOrFail($id);
        return view('view_juros_multa.show', compact('juros'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $juros = JurosMulta::findOrFail($id);
        return view('view_juros_multa.edit', compact('juros'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $juros = JurosMulta::findOrFail($id);

            $validated = $request->validate([
                'indice_juros' => 'required|numeric',
                'indice_multa' => 'required|numeric'
            ]);

            $juros->update($validated);

            return redirect()->route('juros_multa.index')
                ->with('success', 'Valores de juros e multa atualizados com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erro ao atualizar registro: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $juros = JurosMulta::findOrFail($id);
        $juros->delete();

        return redirect()->route('juros_multa.index')
            ->with('success', 'Registro excluído com sucesso!');
    }
}
