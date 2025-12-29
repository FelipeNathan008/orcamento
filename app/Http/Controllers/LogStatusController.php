<?php

namespace App\Http\Controllers;

use App\Models\LogStatus;
use App\Models\StatusMercadoria;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;

class LogStatusController extends Controller
{
    public function index()
    {
        $logs = LogStatus::with('statusMercadoria')->get();
        return view('view_log_status.index', compact('logs'));
    }

    public function create()
    {
        $status = StatusMercadoria::all();
        return view('view_log_status.create', compact('status'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'status_mercadoria_id_status' => 'required|exists:status_mercadoria,id_status_merc',
                'log_id_orcamento' => 'required|integer',
                'log_id_cliente' => 'required|integer',
                'log_nome_status' => 'required|string|max:90',
                'log_situacao' => 'required|integer|min:0|max:1'
            ]);

            LogStatus::create($validated);

            return redirect()->route('log_status.index')
                ->with('success', 'Log de Status criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao criar: '.$e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $log = LogStatus::with('statusMercadoria')->findOrFail($id);
        return view('view_log_status.show', compact('log'));
    }

    public function edit($id)
    {
        $log = LogStatus::findOrFail($id);
        $status = StatusMercadoria::all();

        return view('view_log_status.edit', compact('log', 'status'));
    }

    public function update(Request $request, $id)
    {
        try {
            $log = LogStatus::findOrFail($id);

            $validated = $request->validate([
                'status_mercadoria_id_status' => 'sometimes|required|exists:status_mercadoria,id_status_merc',
                'log_id_orcamento' => 'sometimes|required|integer',
                'log_id_cliente' => 'sometimes|required|integer',
                'log_nome_status' => 'sometimes|required|string|max:90',
                'log_situacao' => 'sometimes|required|integer|min:0|max:1'
            ]);

            $log->update($validated);

            return redirect()->route('log_status.show', $id)
                ->with('success', 'Log de Status atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao atualizar: '.$e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $log = LogStatus::findOrFail($id);
        $log->delete();

        return redirect()->route('log_status.index')
            ->with('success', 'Log de Status excluído com sucesso!');
    }
}
