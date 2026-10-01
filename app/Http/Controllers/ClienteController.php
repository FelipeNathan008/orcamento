<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    private const SESSION_KEY = 'cliente.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('cliente.index'));
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $documento = preg_replace('/\D/', '', $request->documento);

        $clientes = Cliente::query()
            ->when($request->nome, function ($query) use ($request) {
                $query->where('clie_nome', 'like', '%' . $request->nome . '%');
            })
            ->when($request->email, function ($query) use ($request) {
                $query->where('clie_email', 'like', '%' . $request->email . '%');
            })
            ->when($request->celular, function ($query) use ($request) {
                $celular = preg_replace('/\D/', '', $request->celular);
                $query->whereRaw(
                    "REPLACE(REPLACE(REPLACE(clie_celular,'-',''),' ',''),'(','') LIKE ?",
                    ["%{$celular}%"]
                );
            })
            ->when($request->tipo_doc, function ($query) use ($request) {
                $query->where('clie_tipo_doc', $request->tipo_doc);
            })
            ->when($documento, function ($query) use ($documento) {
                $query->where(function ($q) use ($documento) {
                    $q->whereRaw(
                        "REPLACE(REPLACE(REPLACE(clie_cpf,'.',''),'-',''),' ','') LIKE ?",
                        ["%{$documento}%"]
                    )->orWhereRaw(
                        "REPLACE(REPLACE(REPLACE(clie_cnpj,'.',''),'-',''),' ','') LIKE ?",
                        ["%{$documento}%"]
                    );
                });
            })
            ->paginate(10)
            ->withQueryString();

        return view('view_cliente.index', compact('clientes'));
    }

    public function create()
    {
        return view('view_cliente.create', [
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function store(Request $request)
    {
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
            'clie_email.unique' => 'Este e-mail já está cadastrado para outra prospecção.',
            'clie_email.required' => 'O e-mail é obrigatório.',
            'clie_email.email' => 'Por favor, informe um e-mail válido.',
        ]);

        $docNumeroLimpo = preg_replace('/[.\-\/]/', '', $validatedData['clie_doc_numero']);
        $telefoneLimpo = preg_replace('/\D/', '', $validatedData['clie_telefone'] ?? '');
        $celularLimpo = preg_replace('/\D/', '', $validatedData['clie_celular'] ?? '');
        $cepLimpo = preg_replace('/\D/', '', $validatedData['clie_cep']);

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
        $cliente->clie_cpf = $validatedData['clie_tipo_doc'] === 'CPF' ? $docNumeroLimpo : null;
        $cliente->clie_cnpj = $validatedData['clie_tipo_doc'] === 'CNPJ' ? $docNumeroLimpo : null;
        $cliente->save();

        return redirect($this->urlIndex())
            ->with('success', 'Prospecção cadastrada com sucesso!');
    }

    public function show($id)
    {
        $id = CryptHelper::decrypt($id);
        $cliente = Cliente::findOrFail($id);

        return view('view_cliente.show', [
            'cliente' => $cliente,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit($id)
    {
        $id = CryptHelper::decrypt($id);
        $cliente = Cliente::findOrFail($id);

        return view('view_cliente.edit', [
            'cliente' => $cliente,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);
        $cliente = Cliente::findOrFail($id);

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
            'clie_email.unique' => 'Este e-mail já está cadastrado em outra prospecção.',
            'clie_email.required' => 'O e-mail é obrigatório.',
            'clie_email.email' => 'Por favor, informe um e-mail válido.',
        ]);

        $docNumeroLimpo = preg_replace('/[.\-\/]/', '', $validatedData['clie_doc_numero'] ?? '');
        $telefoneLimpo = preg_replace('/\D/', '', $validatedData['clie_telefone'] ?? '');
        $celularLimpo = preg_replace('/\D/', '', $validatedData['clie_celular'] ?? '');
        $cepLimpo = preg_replace('/\D/', '', $validatedData['clie_cep'] ?? '');

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
            'clie_cpf' => null,
            'clie_cnpj' => null,
        ];

        if (($validatedData['clie_tipo_doc'] ?? $cliente->clie_tipo_doc) === 'CPF') {
            $clienteData['clie_cpf'] = $docNumeroLimpo;
        } else {
            $clienteData['clie_cnpj'] = $docNumeroLimpo;
        }

        $cliente->update($clienteData);

        return redirect($this->urlIndex())
            ->with('success', 'Prospecção atualizada com sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        try {
            $id = CryptHelper::decrypt($id);
            $cliente = Cliente::findOrFail($id);
            $cliente->delete();

            $urlVoltar = url()->previous();
            $url = parse_url($urlVoltar);
            parse_str($url['query'] ?? '', $query);

            $paginaAtual = max(1, (int) ($query['page'] ?? 1));

            if ($paginaAtual > 1) {
                $requestFiltros = Request::create($urlVoltar, 'GET');

                $queryClientes = Cliente::query();

                if ($requestFiltros->filled('nome')) {
                    $queryClientes->where(
                        'clie_nome',
                        'like',
                        '%' . trim($requestFiltros->nome) . '%'
                    );
                }

                if ($requestFiltros->filled('email')) {
                    $queryClientes->where(
                        'clie_email',
                        'like',
                        '%' . trim($requestFiltros->email) . '%'
                    );
                }

                if ($requestFiltros->filled('celular')) {
                    $celular = preg_replace('/\D/', '', $requestFiltros->celular);

                    $queryClientes->whereRaw(
                        "REPLACE(REPLACE(REPLACE(clie_celular,'-',''),' ',''),'(','') LIKE ?",
                        ["%{$celular}%"]
                    );
                }

                if ($requestFiltros->filled('tipo_doc')) {
                    $queryClientes->where('clie_tipo_doc', $requestFiltros->tipo_doc);
                }

                if ($requestFiltros->filled('documento')) {
                    $documento = preg_replace('/\D/', '', $requestFiltros->documento);

                    $queryClientes->where(function ($q) use ($documento) {
                        $q->whereRaw(
                            "REPLACE(REPLACE(REPLACE(clie_cpf,'.',''),'-',''),' ','') LIKE ?",
                            ["%{$documento}%"]
                        )->orWhereRaw(
                            "REPLACE(REPLACE(REPLACE(clie_cnpj,'.',''),'-',''),' ','') LIKE ?",
                            ["%{$documento}%"]
                        );
                    });
                }

                $totalRestante = $queryClientes->count();
                $ultimaPagina = max(1, (int) ceil($totalRestante / 10));

                if ($paginaAtual > $ultimaPagina) {
                    $query['page'] = $ultimaPagina;
                    $urlVoltar = ($url['path'] ?? route('cliente.index')) . '?' . http_build_query($query);
                }
            }

            session([self::SESSION_KEY => $urlVoltar]);

            return redirect($urlVoltar)
                ->with('success', 'Prospecção excluída com sucesso!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível excluir a prospecção porque existem registros vinculados a ela.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível excluir a prospecção: ' . $e->getMessage());
        }
    }
}