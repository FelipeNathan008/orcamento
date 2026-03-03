<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::all();
        return view('view_empresa.index', compact('empresas'));
    }

    public function create()
    {
        return view('view_empresa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'emp_nome' => 'required|max:85',
            'emp_cnpj' => 'required|unique:empresa,emp_cnpj|max:45',
            'emp_logradouro' => 'required|max:85',
            'emp_bairro' => 'required|max:45',
            'emp_cidade' => 'required|max:45',
            'emp_uf' => 'required|max:2',
            'emp_cep' => 'required|max:45',
        ], [
            'emp_cnpj.unique' => 'Já existe uma empresa cadastrada com este CNPJ. Verifique os dados informados.',
            'emp_cnpj.required' => 'O CNPJ é obrigatório.',
        ]);

        Empresa::create($request->all());

        return redirect()->route('empresa.index')->with('success', 'Empresa cadastrada com sucesso!');
    }

    public function show($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('view_empresa.show', compact('empresa'));
    }

    public function edit($id)
    {
        $empresa = Empresa::findOrFail($id);
        return view('view_empresa.edit', compact('empresa'));
    }

    public function update(Request $request, $id)
    {
        $empresa = Empresa::findOrFail($id);

        $request->validate([
            'emp_nome' => 'required|max:85',
            'emp_cnpj' => 'required|unique:empresa,emp_cnpj,' . $id . ',id_emp|max:45',
            'emp_logradouro' => 'required|max:85',
            'emp_bairro' => 'required|max:45',
            'emp_cidade' => 'required|max:45',
            'emp_uf' => 'required|max:2',
            'emp_cep' => 'required|max:45',
        ], [
            'emp_cnpj.unique' => 'Já existe uma empresa cadastrada com este CNPJ. Verifique os dados informados.',
            'emp_cnpj.required' => 'O CNPJ é obrigatório.',
        ]);

        $empresa->update($request->all());

        return redirect()->route('empresa.index')->with('success', 'Empresa atualizada com sucesso!');
    }

    public function destroy($id)
    {
        $empresa = Empresa::findOrFail($id);
        $empresa->delete();

        return redirect()->route('empresa.index')->with('success', 'Empresa deletada com sucesso!');
    }
}
