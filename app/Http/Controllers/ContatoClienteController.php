<?php

namespace App\Http\Controllers;

use App\Models\ContatoCliente;
use App\Models\ClienteOrcamento; // Certifique-se de importar o modelo ClienteOrcamento
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

class ContatoClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Carrega os contatos e eager-loads o relacionamento com clienteOrcamento
        $query = ContatoCliente::with('clienteOrcamento');

        // Se houver um termo de busca na query string, aplique o filtro
        if ($request->filled('search_query')) {
            $searchTerm = $request->input('search_query');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('cont_nome', 'like', "%{$searchTerm}%")
                    ->orWhere('cont_email', 'like', "%{$searchTerm}%")
                    // Busca também pelo nome do cliente de orçamento associado
                    ->orWhereHas('clienteOrcamento', function ($qr) use ($searchTerm) {
                        $qr->where('clie_orc_nome', 'like', "%{$searchTerm}%");
                    });
            });
        }

        $contatosCliente = $query->get(); // Busca todos os contatos que correspondem à query

        return view('view_contato_cliente.index', compact('contatosCliente'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $clientesOrcamento = ClienteOrcamento::all();
        $preselectedClientId = $request->route('cliente_orcamento'); // Pega o ID da rota com parâmetro

        return view('view_contato_cliente.create', compact('clientesOrcamento', 'preselectedClientId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'cliente_orcamento_id_co' => 'required|exists:cliente_orcamento,id_co',
                'cont_nome' => 'required|string|max:45',
                'cont_celular' => 'required|string|max:20',
                'cont_telefone' => 'nullable|string|max:20',
                'cont_tipo' => 'required|in:administrativo,comercial,financeiro',
                'cont_email' => 'required|email|max:45', // 'unique' removido aqui
                'cont_descricao' => 'nullable|string|max:500',
            ]);

            // Limpar máscaras
            $validatedData['cont_celular'] = preg_replace('/\D/', '', $validatedData['cont_celular']);
            if (isset($validatedData['cont_telefone'])) {
                $validatedData['cont_telefone'] = preg_replace('/\D/', '', $validatedData['cont_telefone']);
            }

            ContatoCliente::create($validatedData);

            return redirect()->route('contato_cliente.index')->with('success', 'Contato de Cliente criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível criar o Contato de Cliente: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $contatoCliente = ContatoCliente::with('clienteOrcamento')->findOrFail($id);
        return view('view_contato_cliente.show', compact('contatoCliente'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $contatoCliente = ContatoCliente::findOrFail($id);
        $clientesOrcamento = ClienteOrcamento::all();
        return view('view_contato_cliente.edit', compact('contatoCliente', 'clientesOrcamento'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $contatoCliente = ContatoCliente::findOrFail($id);

            $validatedData = $request->validate([
                'cliente_orcamento_id_co' => 'sometimes|required|exists:cliente_orcamento,id_co',
                'cont_nome' => 'sometimes|required|string|max:45',
                'cont_celular' => 'sometimes|required|string|max:20',
                'cont_telefone' => 'nullable|string|max:20',
                'cont_tipo' => 'sometimes|required|in:administrativo,comercial,financeiro',
                'cont_email' => 'sometimes|required|email|max:45', // A validação 'unique' foi removida
                'cont_descricao' => 'nullable|string|max:500',
            ]);

            // Limpar máscaras
            if (isset($validatedData['cont_celular'])) {
                $validatedData['cont_celular'] = preg_replace('/\D/', '', $validatedData['cont_celular']);
            }
            if (isset($validatedData['cont_telefone'])) {
                $validatedData['cont_telefone'] = preg_replace('/\D/', '', $validatedData['cont_telefone']);
            }

            $contatoCliente->update($validatedData);

            return redirect()->route('contato_cliente.index')->with('success', 'Contato de Cliente atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível atualizar o Contato de Cliente: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $contatoCliente = ContatoCliente::findOrFail($id);
        $contatoCliente->delete();

        return redirect()->route('contato_cliente.index')->with('success', 'Contato de Cliente excluído com sucesso!');
    }
}
