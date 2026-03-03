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

        $clienteId = $request->cliente_orcamento;

        $clienteSelecionado = ClienteOrcamento::findOrFail($clienteId);

        $contatosCliente = ContatoCliente::where('cliente_orcamento_id_co', $clienteId)
            ->with('clienteOrcamento')
            ->get();

        return view('view_contato_cliente.index', [
            'contatosCliente' => $contatosCliente,
            'clienteSelecionado' => $clienteSelecionado
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function create($cliente_orcamento)
    {
        $clienteSelecionado = ClienteOrcamento::findOrFail($cliente_orcamento);

        return view('view_contato_cliente.create', compact('clienteSelecionado'));
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
                'cliente_orcamento_id_co' => 'required|exists:cliente_orcamento,id_co',
                'cont_nome' => 'required|string|max:45',
                'cont_celular' => 'required|string|max:20',
                'cont_telefone' => 'nullable|string|max:20',
                'cont_tipo' => 'required|in:administrativo,comercial,financeiro',
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
