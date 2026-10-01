<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\Empresa;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    private const SESSION_KEY = 'empresa.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('empresa.index'));
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $cnpj = preg_replace('/\D/', '', $request->cnpj);

        $empresas = Empresa::query()
            ->when($request->nome, function ($query) use ($request) {
                $query->where('emp_nome', 'like', '%' . $request->nome . '%');
            })
            ->when($cnpj, function ($query) use ($cnpj) {
                $query->whereRaw(
                    "REPLACE(REPLACE(REPLACE(REPLACE(emp_cnpj,'.',''),'/',''),'-',''),' ','') LIKE ?",
                    ["%{$cnpj}%"]
                );
            })
            ->when($request->cidade, function ($query) use ($request) {
                $query->where('emp_cidade', 'like', '%' . $request->cidade . '%');
            })
            ->when($request->uf, function ($query) use ($request) {
                $query->where('emp_uf', $request->uf);
            })
            ->paginate(10)
            ->withQueryString();

        return view('view_empresa.index', compact('empresas'));
    }

    public function create()
    {
        return view('view_empresa.create', [
            'urlVoltar' => $this->urlIndex(),
        ]);
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

        return redirect($this->urlIndex())
            ->with('success', 'Empresa cadastrada com sucesso!');
    }

    public function show($id)
    {
        $id = CryptHelper::decrypt($id);
        $empresa = Empresa::findOrFail($id);

        return view('view_empresa.show', [
            'empresa' => $empresa,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit($id)
    {
        $id = CryptHelper::decrypt($id);
        $empresa = Empresa::findOrFail($id);

        return view('view_empresa.edit', [
            'empresa' => $empresa,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);
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

        return redirect($this->urlIndex())
            ->with('success', 'Empresa atualizada com sucesso!');
    }

    public function destroy(Request $request, $id)
    {
        try {
            $id = CryptHelper::decrypt($id);
            $empresa = Empresa::findOrFail($id);
            $empresa->delete();

            $urlVoltar = url()->previous();
            $url = parse_url($urlVoltar);
            parse_str($url['query'] ?? '', $query);

            $paginaAtual = max(1, (int) ($query['page'] ?? 1));

            if ($paginaAtual > 1) {
                $requestFiltros = Request::create($urlVoltar, 'GET');

                $queryEmpresas = Empresa::query();

                if ($requestFiltros->filled('nome')) {
                    $queryEmpresas->where(
                        'emp_nome',
                        'like',
                        '%' . trim($requestFiltros->nome) . '%'
                    );
                }

                if ($requestFiltros->filled('cnpj')) {
                    $cnpj = preg_replace('/\D/', '', $requestFiltros->cnpj);

                    $queryEmpresas->whereRaw(
                        "REPLACE(REPLACE(REPLACE(REPLACE(emp_cnpj,'.',''),'/',''),'-',''),' ','') LIKE ?",
                        ["%{$cnpj}%"]
                    );
                }

                if ($requestFiltros->filled('cidade')) {
                    $queryEmpresas->where(
                        'emp_cidade',
                        'like',
                        '%' . trim($requestFiltros->cidade) . '%'
                    );
                }

                if ($requestFiltros->filled('uf')) {
                    $queryEmpresas->where('emp_uf', $requestFiltros->uf);
                }

                $totalRestante = $queryEmpresas->count();
                $ultimaPagina = max(1, (int) ceil($totalRestante / 10));

                if ($paginaAtual > $ultimaPagina) {
                    $query['page'] = $ultimaPagina;
                    $urlVoltar = ($url['path'] ?? route('empresa.index')) . '?' . http_build_query($query);
                }
            }

            session([self::SESSION_KEY => $urlVoltar]);

            return redirect($urlVoltar)
                ->with('success', 'Empresa deletada com sucesso!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível excluir a empresa porque existem registros vinculados a ela.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível excluir a empresa: ' . $e->getMessage());
        }
    }
}
