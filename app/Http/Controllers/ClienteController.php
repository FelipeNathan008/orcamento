<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Importar para regras de validação 'unique'

class ClienteController extends Controller
{
    // Listar todos os clientes
    public function index()
    {
        $clientes = Cliente::all();
        return view('view_cliente.index', compact('clientes'));
    }

    // Mostrar o formulário de criação
    public function create()
    {
        return view('view_cliente.create');
    }

    // Salvar novo cliente
    public function store(Request $request)
    {
        // 1. Validação dos dados
        $validatedData = $request->validate([
            'clie_nome' => 'required|string|max:80',
            'clie_email' => 'required|email|max:85|unique:cliente,clie_email',
            'clie_logradouro' => 'required|string|max:100',
            'clie_bairro' => 'required|string|max:80',
            'clie_tipo_doc' => 'required|in:CPF,CNPJ',
            'clie_doc_numero' => 'required|string|max:18',
            'clie_telefone' => 'required_without:clie_celular|nullable|string|max:20',
            'clie_celular' => 'required_without:clie_telefone|nullable|string|max:45',
            'clie_cep' => 'required|string|max:9',
            'clie_cidade' => 'required|string|max:60',
            'clie_uf' => 'required|string|max:2',
        ], [
            'clie_email.unique' => 'Este e-mail já está cadastrado em nosso sistema.',
            'clie_email.required' => 'O e-mail é obrigatório.',
            'clie_email.email' => 'Por favor, informe um e-mail válido.',
        ]);

        // 2. Limpar os dados com máscara (remover caracteres não numéricos)
        $docNumeroLimpo = preg_replace('/\D/', '', $validatedData['clie_doc_numero']);
        $telefoneLimpo = preg_replace('/\D/', '', $validatedData['clie_telefone']);
        $celularLimpo = preg_replace('/\D/', '', $validatedData['clie_celular']);
        $cepLimpo = preg_replace('/\D/', '', $validatedData['clie_cep']);

        // 3. Preparar os dados para salvar
        $clienteData = [
            'clie_nome' => $validatedData['clie_nome'],
            'clie_email' => $validatedData['clie_email'],
            'clie_logradouro' => $validatedData['clie_logradouro'],
            'clie_bairro' => $validatedData['clie_bairro'],
            'clie_tipo_doc' => $validatedData['clie_tipo_doc'],
            'clie_telefone' => $telefoneLimpo,
            'clie_celular' => $celularLimpo,
            'clie_cep' => $cepLimpo,
            'clie_cidade' => $validatedData['clie_cidade'],
            'clie_uf' => $validatedData['clie_uf'],
            'clie_cpf' => null, // Inicializa como null
            'clie_cnpj' => null, // Inicializa como null
        ];

        // 4. Lógica condicional para CPF/CNPJ
        if ($validatedData['clie_tipo_doc'] === 'CPF') {
            $clienteData['clie_cpf'] = $docNumeroLimpo;
        } elseif ($validatedData['clie_tipo_doc'] === 'CNPJ') {
            $clienteData['clie_cnpj'] = $docNumeroLimpo;
        }

        // 5. Criar o Cliente no banco de dados
        $cliente = new Cliente();
        $cliente->clie_nome = $validatedData['clie_nome'];
        $cliente->clie_email = $validatedData['clie_email'];
        $cliente->clie_logradouro = $validatedData['clie_logradouro'];
        $cliente->clie_bairro = $validatedData['clie_bairro'];
        $cliente->clie_tipo_doc = $validatedData['clie_tipo_doc'];
        $cliente->clie_telefone = $telefoneLimpo;
        $cliente->clie_celular = $celularLimpo;
        $cliente->clie_cep = $cepLimpo;
        $cliente->clie_cidade = $validatedData['clie_cidade'];
        $cliente->clie_uf = $validatedData['clie_uf'];

        if ($validatedData['clie_tipo_doc'] === 'CPF') {
            $cliente->clie_cpf = $docNumeroLimpo;
        } else {
            $cliente->clie_cnpj = $docNumeroLimpo;
        }

        $cliente->save();
        // 6. Redirecionar com mensagem de sucesso
        return redirect()->route('cliente.index')->with('success', 'Prospecção cadastrado com sucesso!');
    }

    // Mostrar um cliente específico (opcional)
    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('view_cliente.show', compact('cliente'));
    }

    // Mostrar formulário de edição
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        return view('view_cliente.edit', compact('cliente'));
    }

    // Atualizar cliente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        // 1. Validação dos dados (ajustadas para 'sometimes' e regras de unicidade)
        $validatedData = $request->validate([
            'clie_nome' => 'sometimes|required|string|max:80',
            'clie_email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('cliente', 'clie_email')->ignore($id, 'id_cliente'),
            ],
            'clie_logradouro' => 'sometimes|required|string|max:100',
            'clie_bairro' => 'sometimes|required|string|max:80',
            'clie_tipo_doc' => 'sometimes|required|in:CPF,CNPJ',
            'clie_doc_numero' => 'sometimes|required|string|max:18',
            'clie_telefone' => 'sometimes|required_without:clie_celular|nullable|string|max:20',
            'clie_celular' => 'sometimes|required_without:clie_telefone|nullable|string|max:45',
            'clie_cep' => 'sometimes|required|string|max:9',
            'clie_cidade' => 'sometimes|required|string|max:60',
            'clie_uf' => 'sometimes|required|string|max:2',
        ], [
            'clie_doc_numero.unique' =>
            $request->clie_tipo_doc === 'CPF'
                ? 'Este CPF já pertence a outro cliente cadastrado.'
                : 'Este CNPJ já pertence a outro cliente cadastrado.',
            'clie_email.unique' => 'Este e-mail já está cadastrado em nosso sistema.',
            'clie_email.required' => 'O e-mail é obrigatório.',
            'clie_email.email' => 'Por favor, informe um e-mail válido.',
        ]);

        // 2. Limpar os dados com máscara (remover caracteres não numéricos)
        $docNumeroLimpo = preg_replace('/\D/', '', $validatedData['clie_doc_numero'] ?? '');
        $telefoneLimpo = preg_replace('/\D/', '', $validatedData['clie_telefone'] ?? '');
        $celularLimpo = preg_replace('/\D/', '', $validatedData['clie_celular'] ?? '');
        $cepLimpo = preg_replace('/\D/', '', $validatedData['clie_cep'] ?? '');

        // 3. Preparar os dados para atualizar
        $clienteData = [
            'clie_nome' => $validatedData['clie_nome'] ?? $cliente->clie_nome,
            'clie_email' => $validatedData['clie_email'] ?? $cliente->clie_email,
            'clie_logradouro' => $validatedData['clie_logradouro'] ?? $cliente->clie_logradouro,
            'clie_bairro' => $validatedData['clie_bairro'] ?? $cliente->clie_bairro,
            'clie_tipo_doc' => $validatedData['clie_tipo_doc'] ?? $cliente->clie_tipo_doc,
            'clie_telefone' => $telefoneLimpo,
            'clie_celular' => $celularLimpo,
            'clie_cep' => $cepLimpo,
            'clie_cidade' => $validatedData['clie_cidade'] ?? $cliente->clie_cidade,
            'clie_uf' => $validatedData['clie_uf'] ?? $cliente->clie_uf,
            'clie_cpf' => null, // Resetar para garantir que apenas o campo correto seja preenchido
            'clie_cnpj' => null,
            // 'clie_rg' => null, // REMOVIDO
        ];

        // 4. Lógica condicional para CPF/CNPJ para ATUALIZAÇÃO
        if ($validatedData['clie_tipo_doc'] === 'CPF') {
            $clienteData['clie_cpf'] = $docNumeroLimpo;
            // Opcional: Se estava CNPJ antes, limpar o CNPJ
            // $clienteData['clie_cnpj'] = null;
        } elseif ($validatedData['clie_tipo_doc'] === 'CNPJ') {
            $clienteData['clie_cnpj'] = $docNumeroLimpo;
            // Opcional: Se estava CPF antes, limpar o CPF
            // $clienteData['clie_cpf'] = null;
        }
        // Removido elseif para RG

        // 5. Atualizar o Cliente no banco de dados
        $cliente->update($clienteData);

        return redirect()->route('cliente.index')->with('success', 'Prospecção atualizado com sucesso!');
    }

    // Deletar cliente
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('cliente.index')->with('success', 'Prospecção deletado com sucesso!');
    }
}
