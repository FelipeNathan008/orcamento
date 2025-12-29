<?php

namespace App\Http\Controllers;

use App\Models\ClienteOrcamento;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class ClienteOrcamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $clientesOrcamento = ClienteOrcamento::all();
        return view('view_cliente_orcamento.index', compact('clientesOrcamento'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $clienteToCopy = null;
        if ($request->has('cliente_id')) {
            $clienteToCopy = Cliente::find($request->input('cliente_id'));
        }
        return view('view_cliente_orcamento.create', compact('clienteToCopy'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'clie_orc_nome' => 'required|string|max:45',
                'clie_orc_email' => [
                    'required',
                    'string',
                    'email',
                    'max:45',
                    Rule::unique('cliente_orcamento', 'clie_orc_email'),
                ],
                'clie_orc_logradouro' => 'required|string|max:45',
                'clie_orc_bairro' => 'required|string|max:45',
                'clie_orc_tipo_doc' => 'required|in:CPF,CNPJ,RG',

                // Validação condicional para o campo do documento
                'clie_orc_doc_numero' => [
                    'required',
                    'string',
                    'max:18',
                    Rule::when($request->input('clie_orc_tipo_doc') === 'CPF', [
                        Rule::unique('cliente_orcamento', 'clie_orc_cpf')
                    ]),
                    Rule::when($request->input('clie_orc_tipo_doc') === 'CNPJ', [
                        Rule::unique('cliente_orcamento', 'clie_orc_cnpj')
                    ]),
                    Rule::when($request->input('clie_orc_tipo_doc') === 'RG', [
                        Rule::unique('cliente_orcamento', 'clie_orc_rg')
                    ]),
                ],

                'clie_orc_telefone' => 'required_without:clie_orc_celular|nullable|string|max:14',
                'clie_orc_celular' => 'required_without:clie_orc_telefone|nullable|string|max:15',
                'clie_orc_cep' => 'required|string|max:9',
                'clie_orc_cidade' => 'required|string|max:45',
                'clie_orc_uf' => 'required|string|max:2',
            ]);

            $docNumeroLimpo = preg_replace('/\D/', '', $validatedData['clie_orc_doc_numero']);
            $celularLimpo = preg_replace('/\D/', '', $validatedData['clie_orc_celular']);
            $telefoneLimpo = preg_replace('/\D/', '', $request->input('clie_orc_telefone'));
            $cepLimpo = preg_replace('/\D/', '', $validatedData['clie_orc_cep']);

            $clienteOrcamentoData = [
                'clie_orc_nome' => $validatedData['clie_orc_nome'],
                'clie_orc_email' => $validatedData['clie_orc_email'],
                'clie_orc_celular' => $celularLimpo,
                'clie_orc_telefone' => $telefoneLimpo,
                'clie_orc_tipo_doc' => $validatedData['clie_orc_tipo_doc'],
                'clie_orc_logradouro' => $validatedData['clie_orc_logradouro'],
                'clie_orc_bairro' => $validatedData['clie_orc_bairro'],
                'clie_orc_cep' => $cepLimpo,
                'clie_orc_cidade' => $validatedData['clie_orc_cidade'],
                'clie_orc_uf' => $validatedData['clie_orc_uf'],
                'clie_orc_cpf' => null,
                'clie_orc_cnpj' => null,
                'clie_orc_rg' => null,
            ];

            if ($validatedData['clie_orc_tipo_doc'] === 'CPF') {
                $clienteOrcamentoData['clie_orc_cpf'] = $docNumeroLimpo;
            } elseif ($validatedData['clie_orc_tipo_doc'] === 'CNPJ') {
                $clienteOrcamentoData['clie_orc_cnpj'] = $docNumeroLimpo;
            } elseif ($validatedData['clie_orc_tipo_doc'] === 'RG') {
                $clienteOrcamentoData['clie_orc_rg'] = $docNumeroLimpo;
            }

            ClienteOrcamento::create($clienteOrcamentoData);

            return redirect()->route('cliente_orcamento.index')->with('success', 'Cliente de Orçamento criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível criar o Cliente de Orçamento: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $clienteOrcamento = ClienteOrcamento::findOrFail($id);
        return view('view_cliente_orcamento.show', compact('clienteOrcamento'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $clienteOrcamento = ClienteOrcamento::findOrFail($id);
        return view('view_cliente_orcamento.edit', compact('clienteOrcamento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $clienteOrcamento = ClienteOrcamento::findOrFail($id);

            $validatedData = $request->validate([
                'clie_orc_nome' => 'sometimes|required|string|max:45',
                'clie_orc_email' => [
                    'sometimes',
                    'required',
                    'email',
                    'max:45',
                    Rule::unique('cliente_orcamento', 'clie_orc_email')->ignore($clienteOrcamento->id_co, 'id_co'),
                ],
                'clie_orc_celular' => 'sometimes|required_without:clie_orc_telefone|nullable|string|max:15',
                'clie_orc_telefone' => 'sometimes|required_without:clie_orc_celular|nullable|string|max:14',
                'clie_orc_tipo_doc' => 'sometimes|required|in:CPF,CNPJ,RG',

                // Validação condicional corrigida para o método update
                'clie_orc_doc_numero' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:18',
                    Rule::when($request->input('clie_orc_tipo_doc') === 'CPF', [
                        Rule::unique('cliente_orcamento', 'clie_orc_cpf')->ignore($id, 'id_co')
                    ]),
                    Rule::when($request->input('clie_orc_tipo_doc') === 'CNPJ', [
                        Rule::unique('cliente_orcamento', 'clie_orc_cnpj')->ignore($id, 'id_co')
                    ]),
                    Rule::when($request->input('clie_orc_tipo_doc') === 'RG', [
                        Rule::unique('cliente_orcamento', 'clie_orc_rg')->ignore($id, 'id_co')
                    ]),
                ],
                'clie_orc_logradouro' => 'sometimes|required|string|max:45',
                'clie_orc_bairro' => 'sometimes|required|string|max:45',
                'clie_orc_cep' => 'sometimes|required|string|max:9',
                'clie_orc_cidade' => 'sometimes|required|string|max:45',
                'clie_orc_uf' => 'sometimes|required|string|max:2',
            ]);

            $docNumeroLimpo = preg_replace('/\D/', '', $validatedData['clie_orc_doc_numero'] ?? '');
            $celularLimpo = preg_replace('/\D/', '', $validatedData['clie_orc_celular'] ?? '');
            $telefoneLimpo = preg_replace('/\D/', '', $validatedData['clie_orc_telefone'] ?? '');
            $cepLimpo = preg_replace('/\D/', '', $validatedData['clie_orc_cep'] ?? '');

            $clienteOrcamentoData = [
                'clie_orc_nome' => $validatedData['clie_orc_nome'] ?? $clienteOrcamento->clie_orc_nome,
                'clie_orc_email' => $validatedData['clie_orc_email'] ?? $clienteOrcamento->clie_orc_email,
                'clie_orc_celular' => $celularLimpo,
                'clie_orc_telefone' => $telefoneLimpo,
                'clie_orc_tipo_doc' => $validatedData['clie_orc_tipo_doc'] ?? $clienteOrcamento->clie_orc_tipo_doc,
                'clie_orc_logradouro' => $validatedData['clie_orc_logradouro'] ?? $clienteOrcamento->clie_orc_logradouro,
                'clie_orc_bairro' => $validatedData['clie_orc_bairro'] ?? $clienteOrcamento->clie_orc_bairro,
                'clie_orc_cep' => $cepLimpo,
                'clie_orc_cidade' => $validatedData['clie_orc_cidade'] ?? $clienteOrcamento->clie_orc_cidade,
                'clie_orc_uf' => $validatedData['clie_orc_uf'] ?? $clienteOrcamento->clie_orc_uf,
                'clie_orc_cpf' => null,
                'clie_orc_cnpj' => null,
                'clie_orc_rg' => null,
            ];

            if ($clienteOrcamentoData['clie_orc_tipo_doc'] === 'CPF') {
                $clienteOrcamentoData['clie_orc_cpf'] = $docNumeroLimpo;
            } elseif ($clienteOrcamentoData['clie_orc_tipo_doc'] === 'CNPJ') {
                $clienteOrcamentoData['clie_orc_cnpj'] = $docNumeroLimpo;
            } elseif ($clienteOrcamentoData['clie_orc_tipo_doc'] === 'RG') {
                $clienteOrcamentoData['clie_orc_rg'] = $docNumeroLimpo;
            }

            $clienteOrcamento->update($clienteOrcamentoData);

            return redirect()->route('cliente_orcamento.show', $clienteOrcamento->id_co)->with('success', 'Cliente de Orçamento atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível atualizar o Cliente de Orçamento: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $clienteOrcamento = ClienteOrcamento::findOrFail($id);
        $clienteOrcamento->delete();

        return redirect()->route('cliente_orcamento.index')->with('success', 'Cliente de Orçamento excluído com sucesso!');
    }
}
