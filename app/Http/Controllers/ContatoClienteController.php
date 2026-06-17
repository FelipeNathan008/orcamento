<?php

namespace App\Http\Controllers;

use App\Models\ContatoCliente;
use App\Models\ClienteOrcamento; // Certifique-se de importar o modelo ClienteOrcamento
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

class ContatoClienteController extends Controller
{

    public function index(Request $request)
    {
        $clienteId = $request->cliente_orcamento;
        $clienteSelecionado = ClienteOrcamento::findOrFail($clienteId);
        $search = $request->search;
        $contatosCliente = ContatoCliente::where(
            'cliente_orcamento_id_co',
            $clienteId
        )
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {

                    $q->where('cont_nome', 'like', "%{$search}%")
                        ->orWhere('cont_email', 'like', "%{$search}%");
                });
            })
            ->with('clienteOrcamento')
            ->paginate(10)
            ->withQueryString();

        return view('view_contato_cliente.index', [
            'contatosCliente' => $contatosCliente,
            'clienteSelecionado' => $clienteSelecionado
        ]);
    }

    public function create($cliente_orcamento)
    {
        $clienteSelecionado = ClienteOrcamento::findOrFail($cliente_orcamento);

        return view('view_contato_cliente.create', compact('clienteSelecionado'));
    }

    public function store(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'cliente_orcamento_id_co' => 'required|exists:cliente_orcamento,id_co',
                'cont_nome' => 'required|string|max:45',
                'cont_celular' => 'required|string|max:20',
                'cont_telefone' => 'nullable|string|max:20',
                'cont_tipo' => 'required|in:administrativo,comercial,financeiro,rh,compras,socio',
                'cont_email' => 'required|email|max:45',
                'cont_descricao' => 'nullable|string|max:500',
            ]);

            // Limpar máscaras
            $validatedData['cont_celular'] = preg_replace('/\D/', '', $validatedData['cont_celular']);

            if (!empty($validatedData['cont_telefone'])) {
                $validatedData['cont_telefone'] = preg_replace('/\D/', '', $validatedData['cont_telefone']);
            }

            ContatoCliente::create($validatedData);

            return redirect()->route('contato_cliente.index', [
                'cliente_orcamento' => $validatedData['cliente_orcamento_id_co']
            ])->with('success', 'Contato criado com sucesso!');
        } catch (ValidationException $e) {

            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {

            return redirect()->back()
                ->with('error', 'Erro ao salvar contato: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function show($id)
    {
        $contatoCliente = ContatoCliente::with('clienteOrcamento')->findOrFail($id);
        return view('view_contato_cliente.show', compact('contatoCliente'));
    }


    public function edit($id)
    {
        $contatoCliente = ContatoCliente::findOrFail($id);
        $clientesOrcamento = ClienteOrcamento::all();
        return view('view_contato_cliente.edit', compact('contatoCliente', 'clientesOrcamento'));
    }


    public function update(Request $request, $id)
    {
        try {
            $contatoCliente = ContatoCliente::findOrFail($id);

            $validatedData = $request->validate([
                'cliente_orcamento_id_co' => 'required|exists:cliente_orcamento,id_co',
                'cont_nome' => 'required|string|max:45',
                'cont_celular' => 'required|string|max:20',
                'cont_telefone' => 'nullable|string|max:20',
                'cont_tipo' => 'required|in:administrativo,comercial,financeiro,rh,compras,socio',
                'cont_email' => 'required|email|max:45',
                'cont_descricao' => 'nullable|string|max:500',
            ]);

            // Limpar máscaras
            $validatedData['cont_celular'] = preg_replace('/\D/', '', $validatedData['cont_celular']);

            if (!empty($validatedData['cont_telefone'])) {
                $validatedData['cont_telefone'] = preg_replace('/\D/', '', $validatedData['cont_telefone']);
            }

            $contatoCliente->update($validatedData);

            return redirect()->route('contato_cliente.index', [
                'cliente_orcamento' => $contatoCliente->cliente_orcamento_id_co
            ])->with('success', 'Contato atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível atualizar o contato: ' . $e->getMessage())
                ->withInput();
        }
    }


    public function destroy($id)
    {
        $contatoCliente = ContatoCliente::findOrFail($id);

        $clienteId = $contatoCliente->cliente_orcamento_id_co;

        $contatoCliente->delete();

        return redirect()->route('contato_cliente.index', [
            'cliente_orcamento' => $clienteId
        ])->with('success', 'Contato de Cliente excluído com sucesso!');
    }
}
