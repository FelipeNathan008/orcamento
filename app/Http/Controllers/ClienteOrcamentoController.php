<?php

namespace App\Http\Controllers;

use App\Models\ClienteOrcamento;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Helpers\CryptHelper;
use Illuminate\Validation\Rule;
use Exception;


class ClienteOrcamentoController extends Controller
{
    private const SESSION_KEY = 'cliente_orcamento.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('cliente_orcamento.index'));
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $query = ClienteOrcamento::query();

        if ($request->filled('nome')) {
            $query->where('clie_orc_nome', 'like', '%' . trim($request->nome) . '%');
        }

        if ($request->filled('email')) {
            $query->where('clie_orc_email', 'like', '%' . trim($request->email) . '%');
        }

        if ($request->filled('codigo')) {
            $query->where('clie_orc_cod_interno', 'like', '%' . trim($request->codigo) . '%');
        }

        if ($request->filled('id_orcamento')) {
            $query->whereHas('orcamentos', function ($q) use ($request) {
                $q->where('id_orcamento', $request->id_orcamento);
            });
        }

        $clientesOrcamento = $query
            ->orderBy('clie_orc_nome')
            ->paginate(10)
            ->withQueryString();

        return view('view_cliente_orcamento.index', compact('clientesOrcamento'));
    }

    public function create(Request $request, $clienteId = null)
    {
        $cliente = null;

        if ($clienteId) {
            $id = CryptHelper::decrypt($clienteId);
            $cliente = Cliente::find($id);
        }

        if ($request->filled('cliente_id')) {
            $cliente = Cliente::findOrFail($request->cliente_id);
        }

        return view('view_cliente_orcamento.create', [
            'cliente' => $cliente,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $this->validateData($request);

        ClienteOrcamento::create(
            $this->prepareData($validatedData)
        );

        return redirect($this->urlIndex())
            ->with('success', 'Cliente de Orçamento criado com sucesso!');
    }

    public function show($id)
    {
        $id = CryptHelper::decrypt($id);
        $clienteOrcamento = ClienteOrcamento::findOrFail($id);

        return view('view_cliente_orcamento.show', [
            'clienteOrcamento' => $clienteOrcamento,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit($id)
    {
        $id = CryptHelper::decrypt($id);
        $clienteOrcamento = ClienteOrcamento::findOrFail($id);

        return view('view_cliente_orcamento.edit', [
            'clienteOrcamento' => $clienteOrcamento,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);
        $clienteOrcamento = ClienteOrcamento::findOrFail($id);
        $validatedData = $this->validateData($request, $id);

        $clienteOrcamento->update(
            $this->prepareData($validatedData)
        );

        return redirect($this->urlIndex())
            ->with('success', 'Cliente de Orçamento atualizado com sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        try {
            $id = CryptHelper::decrypt($id);
            $clienteOrcamento = ClienteOrcamento::findOrFail($id);

            if ($clienteOrcamento->orcamentos()->exists()) {
                return redirect()->back()
                    ->with('error', 'Não é possível excluir este cliente porque ele possui orçamentos cadastrados.');
            }

            if ($clienteOrcamento->contatos()->exists()) {
                return redirect()->back()
                    ->with('error', 'Não é possível excluir este cliente porque ele possui contatos cadastrados.');
            }

            $clienteOrcamento->delete();

            $urlVoltar = url()->previous();
            $url = parse_url($urlVoltar);

            parse_str($url['query'] ?? '', $query);

            $paginaAtual = max(1, (int) ($query['page'] ?? 1));

            if ($paginaAtual > 1) {
                $requestFiltros = Request::create($urlVoltar, 'GET');

                $queryClientes = ClienteOrcamento::query();

                if ($requestFiltros->filled('nome')) {
                    $queryClientes->where(
                        'clie_orc_nome',
                        'like',
                        '%' . trim($requestFiltros->nome) . '%'
                    );
                }

                if ($requestFiltros->filled('email')) {
                    $queryClientes->where(
                        'clie_orc_email',
                        'like',
                        '%' . trim($requestFiltros->email) . '%'
                    );
                }

                if ($requestFiltros->filled('codigo')) {
                    $queryClientes->where(
                        'clie_orc_cod_interno',
                        'like',
                        '%' . trim($requestFiltros->codigo) . '%'
                    );
                }

                if ($requestFiltros->filled('id_orcamento')) {
                    $queryClientes->whereHas('orcamentos', function ($q) use ($requestFiltros) {
                        $q->where('id_orcamento', $requestFiltros->id_orcamento);
                    });
                }

                $totalRestante = $queryClientes->count();

                $ultimaPagina = max(1, (int) ceil($totalRestante / 1));

                if ($paginaAtual > $ultimaPagina) {
                    $query['page'] = $ultimaPagina;
                    $urlVoltar = ($url['path'] ?? '') . '?' . http_build_query($query);
                }
            }

            return redirect($urlVoltar)
                ->with('success', 'Cliente de Orçamento excluído com sucesso!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível excluir o cliente porque existem registros vinculados a ele.');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível excluir o Cliente de Orçamento: ' . $e->getMessage());
        }
    }


    private function validateData(Request $request, $id = null)
    {
        return $request->validate([
            'clie_orc_nome' => 'required|string|max:45',
            'clie_orc_email' => [
                'required',
                'email',
                'max:45',
                Rule::unique('cliente_orcamento', 'clie_orc_email')
                    ->ignore($id, 'id_co'),
            ],
            'clie_orc_telefone' => 'required_without:clie_orc_celular|nullable|string|max:14',
            'clie_orc_celular' => 'required_without:clie_orc_telefone|nullable|string|max:15',
            'clie_orc_tipo_doc' => 'required|in:CPF,CNPJ,RG',
            'clie_orc_doc_numero' => [
                'required',
                'string',
                'max:18',
                Rule::when(
                    $request->clie_orc_tipo_doc === 'CPF',
                    Rule::unique('cliente_orcamento', 'clie_orc_cpf')
                        ->ignore($id, 'id_co')
                ),
                Rule::when(
                    $request->clie_orc_tipo_doc === 'CNPJ',
                    Rule::unique('cliente_orcamento', 'clie_orc_cnpj')
                        ->ignore($id, 'id_co')
                ),
                Rule::when(
                    $request->clie_orc_tipo_doc === 'RG',
                    Rule::unique('cliente_orcamento', 'clie_orc_rg')
                        ->ignore($id, 'id_co')
                ),
            ],
            'clie_orc_ie' => 'required|string|max:90',
            'clie_orc_logradouro' => 'required|string|max:45',
            'clie_orc_bairro' => 'required|string|max:45',
            'clie_orc_cep' => 'required|string|max:9',
            'clie_orc_cidade' => 'required|string|max:45',
            'clie_orc_uf' => 'required|string|max:2',
            'clie_orc_cod_interno' => [
                'required',
                'string',
                'max:60',
                Rule::unique('cliente_orcamento', 'clie_orc_cod_interno')
                    ->ignore($id, 'id_co'),
            ],
        ], [
            'clie_orc_email.unique' => 'Este e-mail já está cadastrado.',
            'clie_orc_cod_interno.unique' => 'Este código interno já pertence a outro cliente.',
        ]);
    }

    private function prepareData(array $data)
    {
        $docNumero = preg_replace('/\D/', '', $data['clie_orc_doc_numero']);

        return [
            'clie_orc_nome' => $data['clie_orc_nome'],
            'clie_orc_email' => $data['clie_orc_email'],
            'clie_orc_telefone' => preg_replace('/\D/', '', $data['clie_orc_telefone'] ?? ''),
            'clie_orc_celular' => preg_replace('/\D/', '', $data['clie_orc_celular'] ?? ''),
            'clie_orc_tipo_doc' => $data['clie_orc_tipo_doc'],
            'clie_orc_ie' => $data['clie_orc_ie'],
            'clie_orc_logradouro' => $data['clie_orc_logradouro'],
            'clie_orc_bairro' => $data['clie_orc_bairro'],
            'clie_orc_cep' => preg_replace('/\D/', '', $data['clie_orc_cep']),
            'clie_orc_cidade' => $data['clie_orc_cidade'],
            'clie_orc_uf' => $data['clie_orc_uf'],
            'clie_orc_cod_interno' => $data['clie_orc_cod_interno'],
            'clie_orc_cpf' => $data['clie_orc_tipo_doc'] === 'CPF' ? $docNumero : null,
            'clie_orc_cnpj' => $data['clie_orc_tipo_doc'] === 'CNPJ' ? $docNumero : null,
            'clie_orc_rg' => $data['clie_orc_tipo_doc'] === 'RG' ? $docNumero : null,
        ];
    }
}
