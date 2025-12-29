<?php

namespace App\Http\Controllers;

use App\Models\StatusMercadoria;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class StatusMercadoriaController extends Controller
{
    public function index()
    {
        $status = StatusMercadoria::all();
        return view('status_mercadoria.index', compact('status'));
    }

    public function create()
    {
        return view('status_mercadoria.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'status_merc_nome' => 'required|string|max:90',
            ]);

            StatusMercadoria::create($validated);

            return redirect()->route('status_mercadoria.index')
                ->with('success', 'Status de Mercadoria criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao criar: '.$e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $status = StatusMercadoria::findOrFail($id);
        return view('view_status_mercadoria.show', compact('status'));
    }

    public function edit($id)
    {
        $status = StatusMercadoria::findOrFail($id);
        return view('view_status_mercadoria.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        try {
            $status = StatusMercadoria::findOrFail($id);

            $validated = $request->validate([
                'status_merc_nome' => 'sometimes|required|string|max:90'
            ]);

            $status->update($validated);

            return redirect()->route('view_status_mercadoria.show', $id)
                ->with('success', 'Status de Mercadoria atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar: '.$e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $status = StatusMercadoria::findOrFail($id);
        $status->delete();

        return redirect()->route('status_mercadoria.index')
            ->with('success', 'Status de Mercadoria excluído com sucesso!');
    }
}
