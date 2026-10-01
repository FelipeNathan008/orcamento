<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\ClienteOrcamento;
use App\Models\ContatoCliente;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContatoClienteController extends Controller
{
    private const SESSION_KEY = 'contato_cliente.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('cliente_orcamento.index'));
    }

    public function index(Request $request, $id)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $clienteId = CryptHelper::decrypt($id);
        $clienteSelecionado = ClienteOrcamento::findOrFail($clienteId);

        $query = ContatoCliente::where('cliente_orcamento_id_co', $clienteId);

        if ($request->filled('nome')) {
            $query->where('cont_nome', 'like', '%' . trim($request->nome) . '%');
        }

        if ($request->filled('email')) {
            $query->where('cont_email', 'like', '%' . trim($request->email) . '%');
        }

        if ($request->filled('celular')) {
            $celular = preg_replace('/\D/', '', $request->celular);
            $query->where('cont_celular', 'like', '%' . $celular . '%');
        }

        if ($request->filled('tipo')) {
            $query->where('cont_tipo', $request->tipo);
        }

        $contatosCliente = $query
            ->with('clienteOrcamento')
            ->orderBy('cont_nome')
            ->paginate(1)
            ->withQueryString();

        $urlClienteOrcamento = session(
            'cliente_orcamento.index_url',
            route('cliente_orcamento.index')
        );

        return view('view_contato_cliente.index', compact(
            'contatosCliente',
            'clienteSelecionado',
            'urlClienteOrcamento'
        ));
    }

    public function create($id)
    {
        $clienteId = CryptHelper::decrypt($id);
        $clienteSelecionado = ClienteOrcamento::findOrFail($clienteId);

        return view('view_contato_cliente.create', [
            'clienteSelecionado' => $clienteSelecionado,
            'urlVoltar' => $this->urlIndex(),
        ]);
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

            $validatedData['cont_celular'] = preg_replace(
                '/\D/',
                '',
                $validatedData['cont_celular']
            );

            if (!empty($validatedData['cont_telefone'])) {
                $validatedData['cont_telefone'] = preg_replace(
                    '/\D/',
                    '',
                    $validatedData['cont_telefone']
                );
            }

            ContatoCliente::create($validatedData);

            return redirect($this->urlIndex())
                ->with('success', 'Contato criado com sucesso!');
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
        $id = CryptHelper::decrypt($id);

        $contatoCliente = ContatoCliente::with('clienteOrcamento')
            ->findOrFail($id);

        return view('view_contato_cliente.show', [
            'contatoCliente' => $contatoCliente,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit($id)
    {
        $id = CryptHelper::decrypt($id);

        $contatoCliente = ContatoCliente::findOrFail($id);
        $clientesOrcamento = ClienteOrcamento::all();

        return view('view_contato_cliente.edit', [
            'contatoCliente' => $contatoCliente,
            'clientesOrcamento' => $clientesOrcamento,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $id = CryptHelper::decrypt($id);
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

            $validatedData['cont_celular'] = preg_replace(
                '/\D/',
                '',
                $validatedData['cont_celular']
            );

            if (!empty($validatedData['cont_telefone'])) {
                $validatedData['cont_telefone'] = preg_replace(
                    '/\D/',
                    '',
                    $validatedData['cont_telefone']
                );
            }

            $contatoCliente->update($validatedData);

            return redirect($this->urlIndex())
                ->with('success', 'Contato atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível atualizar o contato: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $id = CryptHelper::decrypt($id);
        $contatoCliente = ContatoCliente::findOrFail($id);

        $contatoCliente->delete();

        return redirect($this->urlIndex())
            ->with('success', 'Contato de cliente excluído com sucesso!');
    }
}
