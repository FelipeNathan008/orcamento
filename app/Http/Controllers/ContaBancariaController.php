<?php

namespace App\Http\Controllers;

use App\Models\ContaBancaria;
use Illuminate\Http\Request;

class ContaBancariaController extends Controller
{
    public function index()
    {
        $contas = ContaBancaria::all();
        return view('view_conta_bancaria.index', compact('contas'));
    }

    public function create()
    {
        return view('view_conta_bancaria.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'conta_nome_banco' => 'required|string|max:200',
            'conta_cod_banco' => 'required|string|max:10',
            'numero_conta_corrente' => 'required|string|max:100',
            'conta_agencia' => 'required|string|max:50',
            'numero_digito_corrente' => 'required|string|max:90',
            'conta_desc' => 'nullable|string|max:255',
        ]);

        ContaBancaria::create($validatedData);

        return redirect()->route('conta_bancaria.index')
            ->with('success', 'Conta bancária criada com sucesso!');
    }

    public function show(string $id)
    {
        $conta = ContaBancaria::findOrFail($id);
        return view('view_conta_bancaria.show', compact('conta'));
    }

    public function edit(string $id)
    {
        $conta = ContaBancaria::findOrFail($id);
        return view('view_conta_bancaria.edit', compact('conta'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'conta_nome_banco' => 'required|string|max:200',
            'conta_cod_banco' => 'required|string|max:10',
            'conta_agencia' => 'required|string|max:50',
            'numero_conta_corrente' => 'required|string|max:100',
            'numero_digito_corrente' => 'required|string|max:90',
            'conta_desc' => 'nullable|string|max:255',
        ]);

        $conta = ContaBancaria::findOrFail($id);
        $conta->update($validatedData);

        return redirect()->route('conta_bancaria.index')
            ->with('success', 'Conta bancária atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        $conta = ContaBancaria::findOrFail($id);
        $conta->delete();

        return redirect()->route('conta_bancaria.index')
            ->with('success', 'Conta bancária excluída com sucesso!');
    }
}
