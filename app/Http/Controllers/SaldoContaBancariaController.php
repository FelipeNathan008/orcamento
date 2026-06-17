<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContaBancaria;
use App\Models\SaldoConta;

class SaldoContaBancariaController extends Controller
{
    public function index()
    {
        $contas = ContaBancaria::with('saldoConta')->get();

        return view('view_saldo_conta_bancaria.index', compact('contas'));
    }

    public function create()
    {
        return view('view_saldo_conta_bancaria.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'conta_nome_banco' => 'required|string|max:255',
            'conta_cod_banco' => 'required|string|max:20',
            'conta_agencia' => 'required|string|max:20',
            'numero_conta_corrente' => 'required|string|max:30',
            'numero_digito_corrente' => 'required|string|max:10',
            'conta_desc' => 'nullable|string|max:255',
            'saldo_conta_valor' => 'required|numeric|min:0',
        ]);

        $conta = ContaBancaria::create([
            'conta_nome_banco' => $validatedData['conta_nome_banco'],
            'conta_cod_banco' => $validatedData['conta_cod_banco'],
            'conta_agencia' => $validatedData['conta_agencia'],
            'numero_conta_corrente' => $validatedData['numero_conta_corrente'],
            'numero_digito_corrente' => $validatedData['numero_digito_corrente'],
            'conta_desc' => $validatedData['conta_desc'],
        ]);

        SaldoConta::create([
            'id_conta_bancaria_id' => $conta->id_conta,
            'saldo_conta_valor' => $validatedData['saldo_conta_valor'],
        ]);

        return redirect()->route('saldo_conta_bancaria.index')
            ->with('success', 'Conta bancária criada com sucesso!');
    }

    public function show(string $id)
    {
        $conta = ContaBancaria::with('saldoConta')->findOrFail($id);

        return view('view_saldo_conta_bancaria.show', compact('conta'));
    }

    public function edit(string $id)
    {
        $conta = ContaBancaria::with('saldoConta')->findOrFail($id);

        return view('view_saldo_conta_bancaria.edit', compact('conta'));
    }

    public function update(Request $request, string $id)
    {
        $validatedData = $request->validate([
            'conta_nome_banco' => 'required|string|max:255',
            'conta_cod_banco' => 'required|string|max:20',
            'conta_agencia' => 'required|string|max:20',
            'numero_conta_corrente' => 'required|string|max:30',
            'numero_digito_corrente' => 'required|string|max:10',
            'conta_desc' => 'nullable|string|max:255',
            'saldo_conta_valor' => 'required|numeric|min:0',
        ]);

        $conta = ContaBancaria::findOrFail($id);

        $conta->update([
            'conta_nome_banco' => $validatedData['conta_nome_banco'],
            'conta_cod_banco' => $validatedData['conta_cod_banco'],
            'conta_agencia' => $validatedData['conta_agencia'],
            'numero_conta_corrente' => $validatedData['numero_conta_corrente'],
            'numero_digito_corrente' => $validatedData['numero_digito_corrente'],
            'conta_desc' => $validatedData['conta_desc'],
        ]);

        $saldo = SaldoConta::where(
            'id_conta_bancaria_id',
            $conta->id_conta
        )->first();

        if ($saldo) {

            $saldo->update([
                'saldo_conta_valor' => $validatedData['saldo_conta_valor'],
            ]);

        } else {

            SaldoConta::create([
                'id_conta_bancaria_id' => $conta->id_conta,
                'saldo_conta_valor' => $validatedData['saldo_conta_valor'],
            ]);
        }

        return redirect()->route('saldo_conta_bancaria.index')
            ->with('success', 'Conta bancária atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        $conta = ContaBancaria::findOrFail($id);

        SaldoConta::where(
            'id_conta_bancaria_id',
            $conta->id_conta
        )->delete();

        $conta->delete();

        return redirect()->route('saldo_conta_bancaria.index')
            ->with('success', 'Conta bancária excluída com sucesso!');
    }
}