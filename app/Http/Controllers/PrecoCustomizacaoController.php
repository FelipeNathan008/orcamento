<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrecoCustomizacao; // Importe o modelo

class PrecoCustomizacaoController extends Controller
{
    /**
     * Exibe uma listagem do recurso.
     */
    public function index()
    {
        // Busca todos os registros do modelo PrecoCustomizacao
        $precosCustomizacao = PrecoCustomizacao::all();

        // Passa a variável $precosCustomizacao para a view
        return view('view_preco_customizacao.index', compact('precosCustomizacao'));
    }

    /**
     * Mostra o formulário para criar um novo recurso.
     */
    public function create()
    {
        // Retorna a view para o formulário de criação
        return view('view_preco_customizacao.create');
    }

    /**
     * Armazena um recurso recém-criado no armazenamento.
     */
    public function store(Request $request)
    {
        // 1. Valida os dados da requisição
        $validatedData = $request->validate([
            'preco_tipo' => 'required|string|max:45',
            'preco_tamanho' => 'required|string|max:30',
            'preco_valor' => 'required|numeric|max:99999.99',
        ]);

        // 2. Cria um novo registro no banco de dados com os dados validados
        PrecoCustomizacao::create($validatedData);

        // 3. Redireciona o usuário de volta com uma mensagem de sucesso
        return redirect()->route('preco_customizacao.index')
                         ->with('success', 'Preço de customização criado com sucesso!');
    }

    /**
     * Exibe o recurso especificado.
     */
    public function show(string $id)
    {
        // Encontra o preço de customização pelo ID ou retorna um erro 404
        $precoCustomizacao = PrecoCustomizacao::findOrFail($id);

        // Retorna a view 'show' com o recurso encontrado
        return view('preco_customizacao.show', compact('precoCustomizacao'));
    }

    /**
     * Mostra o formulário para editar o recurso especificado.
     */
    public function edit(string $id)
    {
        // Encontra o preço de customização pelo ID ou retorna um erro 404
        $precoCustomizacao = PrecoCustomizacao::findOrFail($id);

        // Retorna a view 'edit' com o recurso encontrado
        return view('view_preco_customizacao.edit', compact('precoCustomizacao'));
    }

    /**
     * Atualiza o recurso especificado no armazenamento.
     */
    public function update(Request $request, string $id)
    {
        // 1. Valida os dados da requisição
        $validatedData = $request->validate([
            'preco_tipo' => 'required|string|max:45',
            'preco_tamanho' => 'required|string|max:30',
            'preco_valor' => 'required|numeric|max:99999.99',
        ]);

        // 2. Encontra o preço de customização pelo ID ou retorna um erro 404
        $precoCustomizacao = PrecoCustomizacao::findOrFail($id);

        // 3. Atualiza o registro com os dados validados
        $precoCustomizacao->update($validatedData);

        // 4. Redireciona o usuário de volta com uma mensagem de sucesso
        return redirect()->route('preco_customizacao.index')
                         ->with('success', 'Preço de customização atualizado com sucesso!');
    }

    /**
     * Remove o recurso especificado do armazenamento.
     */
    public function destroy(string $id)
    {
        // 1. Encontra o preço de customização pelo ID ou retorna um erro 404
        $precoCustomizacao = PrecoCustomizacao::findOrFail($id);

        // 2. Deleta o registro do banco de dados
        $precoCustomizacao->delete();

        // 3. Redireciona o usuário de volta com uma mensagem de sucesso
        return redirect()->route('preco_customizacao.index')
                         ->with('success', 'Preço de customização excluído com sucesso!');
    }
}
