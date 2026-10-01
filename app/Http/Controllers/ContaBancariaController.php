<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\ContaBancaria;
use App\Models\SaldoConta;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ContaBancariaController extends Controller
{
    private const SESSION_KEY = 'conta_bancaria.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('conta_bancaria.index'));
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $contas = ContaBancaria::with(['saldoConta', 'formasPagamento', 'fluxos'])
            ->when($request->banco, function ($query) use ($request) {
                $query->where('conta_nome_banco', 'like', '%' . $request->banco . '%');
            })
            ->when($request->codigo, function ($query) use ($request) {
                $query->where('conta_cod_banco', 'like', '%' . $request->codigo . '%');
            })
            ->when($request->agencia, function ($query) use ($request) {
                $query->where('conta_agencia', 'like', '%' . $request->agencia . '%');
            })
            ->when($request->conta, function ($query) use ($request) {
                $query->where('numero_conta_corrente', 'like', '%' . $request->conta . '%');
            })
            ->paginate(10)
            ->withQueryString();

        return view('view_conta_bancaria.index', compact('contas'));
    }

    public function adicionarSaldo(Request $request, string $id)
    {
        $request->validate([
            'valor' => 'required|numeric|min:0.01',
        ]);

        $id = CryptHelper::decrypt($id);
        $conta = ContaBancaria::findOrFail($id);

        $valor = $request->valor;

        $saldo = SaldoConta::where(
            'id_conta_bancaria_id',
            $conta->id_conta
        )->first();

        if ($saldo) {
            $saldo->saldo_conta_valor += $valor;
            $saldo->save();
        } else {
            SaldoConta::create([
                'id_conta_bancaria_id' => $conta->id_conta,
                'saldo_conta_valor' => $valor,
            ]);
        }

        return redirect($this->urlIndex())
            ->with('success', 'Saldo adicionado com sucesso!');
    }

    public function create()
    {
        return view('view_conta_bancaria.create', [
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'conta_nome_banco' => 'required|string|max:200',
            'conta_cod_banco' => 'required|string|max:10',
            'numero_conta_corrente' => 'required|string|max:100',
            'conta_agencia' => 'required|string|max:50',
            'numero_digito_corrente' => 'required|string|max:90',
            'conta_desc' => 'nullable|string|max:255',
        ]);

        ContaBancaria::create($validatedData);

        return redirect($this->urlIndex())
            ->with('success', 'Conta bancária criada com sucesso!');
    }

    public function show(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $conta = ContaBancaria::with('saldoConta')->findOrFail($id);

        return view('view_conta_bancaria.show', [
            'conta' => $conta,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $conta = ContaBancaria::with('saldoConta')->findOrFail($id);

        return view('view_conta_bancaria.edit', [
            'conta' => $conta,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);
        $conta = ContaBancaria::findOrFail($id);

        $validatedData = $request->validate([
            'conta_nome_banco' => 'required|string|max:200',
            'conta_cod_banco' => 'required|string|max:10',
            'conta_agencia' => 'required|string|max:50',
            'numero_conta_corrente' => 'required|string|max:100',
            'numero_digito_corrente' => 'required|string|max:90',
            'conta_desc' => 'nullable|string|max:255',
        ]);

        $conta->update($validatedData);

        return redirect($this->urlIndex())
            ->with('success', 'Conta bancária atualizada com sucesso!');
    }

    public function destroy(Request $request, string $id)
    {
        try {
            $id = CryptHelper::decrypt($id);

            $conta = ContaBancaria::with([
                'formasPagamento',
                'fluxos',
                'saldoConta'
            ])->findOrFail($id);

            if (
                $conta->formasPagamento->count() > 0 ||
                $conta->fluxos->count() > 0 ||
                $conta->saldoConta
            ) {
                return redirect($this->urlIndex())
                    ->with('error', 'Não é possível excluir esta conta pois ela possui registros vinculados.');
            }

            $conta->delete();

            $urlVoltar = url()->previous();
            $url = parse_url($urlVoltar);
            parse_str($url['query'] ?? '', $query);
            $paginaAtual = max(1, (int) ($query['page'] ?? 1));

            if ($paginaAtual > 1) {
                $requestFiltros = Request::create($urlVoltar, 'GET');
                $queryContas = ContaBancaria::query();

                if ($requestFiltros->filled('banco')) {
                    $queryContas->where(
                        'conta_nome_banco',
                        'like',
                        '%' . trim($requestFiltros->banco) . '%'
                    );
                }

                if ($requestFiltros->filled('codigo')) {
                    $queryContas->where(
                        'conta_cod_banco',
                        'like',
                        '%' . trim($requestFiltros->codigo) . '%'
                    );
                }

                if ($requestFiltros->filled('agencia')) {
                    $queryContas->where(
                        'conta_agencia',
                        'like',
                        '%' . trim($requestFiltros->agencia) . '%'
                    );
                }

                if ($requestFiltros->filled('conta')) {
                    $queryContas->where(
                        'numero_conta_corrente',
                        'like',
                        '%' . trim($requestFiltros->conta) . '%'
                    );
                }

                $totalRestante = $queryContas->count();
                $ultimaPagina = max(1, (int) ceil($totalRestante / 10));

                if ($paginaAtual > $ultimaPagina) {
                    $query['page'] = $ultimaPagina;
                    $urlVoltar = ($url['path'] ?? route('conta_bancaria.index'));

                    if (!empty($query)) {
                        $urlVoltar .= '?' . http_build_query($query);
                    }
                }
            }

            session([self::SESSION_KEY => $urlVoltar]);

            return redirect($urlVoltar)
                ->with('success', 'Conta bancária excluída com sucesso!');
        } catch (QueryException $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível excluir a conta bancária porque existem registros vinculados a ela.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível excluir a conta bancária: ' . $e->getMessage());
        }
    }
}
