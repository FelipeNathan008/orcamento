<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use Illuminate\Http\Request;
use App\Models\NotaFiscal;
use App\Models\Orcamento;
use App\Models\TipoFluxoCaixa;
use App\Models\Movimentacao;

class NotaFiscalController extends Controller
{
    private const SESSION_KEY = 'nota_fiscal.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('nota_fiscal.index'));
    }

    private function queryNotas(Request $request)
    {
        $query = NotaFiscal::with(['orcamento', 'tipo', 'movimentacao']);

        if ($request->filled('data')) {
            $query->whereDate('nota_data', $request->data);
        }

        if ($request->filled('tipo')) {
            $query->whereHas('tipo', function ($q) use ($request) {
                $q->where('tipo_flu_nome', 'like', '%' . $request->tipo . '%');
            });
        }

        return $query;
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $notas = $this->queryNotas($request)
            ->orderBy('nota_data', 'desc')
            ->paginate(10)
            ->withQueryString();

        $tipos = TipoFluxoCaixa::all();

        return view('view_nota_fiscal.index', compact('notas', 'tipos'));
    }

    public function create(Request $request)
    {
        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex();

        session([self::SESSION_KEY => $urlVoltar]);

        return view('view_nota_fiscal.create', [
            'tipos' => TipoFluxoCaixa::all(),
            'movimentacoes' => Movimentacao::all(),
            'urlVoltar' => $urlVoltar,
        ]);
    }

    public function buscarOrcamentos(Request $request)
    {
        $busca = trim($request->input('busca', ''));

        $orcamentos = Orcamento::with('clienteOrcamento')
            ->when($busca !== '', function ($query) use ($busca) {
                $query->where(function ($q) use ($busca) {
                    $q->where('id_orcamento', 'like', '%' . $busca . '%')
                        ->orWhere('orc_cod_interno', 'like', '%' . $busca . '%')
                        ->orWhere('orc_cod_fabrica', 'like', '%' . $busca . '%')
                        ->orWhereHas('clienteOrcamento', function ($cliente) use ($busca) {
                            $cliente->where('clie_orc_nome', 'like', '%' . $busca . '%');
                        });
                });
            })
            ->orderByDesc('id_orcamento')
            ->paginate(5);

        return response()->json([
            'orcamentos' => $orcamentos->map(function ($orcamento) {
                return [
                    'id' => $orcamento->id_orcamento,
                    'cod_interno' => $orcamento->orc_cod_interno,
                    'cod_fabrica' => $orcamento->orc_cod_fabrica,
                    'cliente' => $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A',
                    'data' => $orcamento->orc_data_inicio
                        ? \Carbon\Carbon::parse($orcamento->orc_data_inicio)->format('d/m/Y')
                        : 'N/A',
                ];
            }),
            'current_page' => $orcamentos->currentPage(),
            'last_page' => $orcamentos->lastPage(),
            'total' => $orcamentos->total(),
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'orcamento_id_orcamento' => 'required|integer|exists:orcamento,id_orcamento',
            'nota_numero' => 'required|string|max:100',
            'nota_id_tipo' => 'required|integer|exists:tipo_fluxo_caixa,id_tipo_fluxo',
            'nota_data' => 'required|date',
            'nota_id_movimentacao' => 'required|integer|exists:movimentacao,id_movimentacao',
            'nota_valor' => 'required|numeric',
            'nota_desc' => 'required|string|max:255',
        ], [
            'orcamento_id_orcamento.required' => 'A escolha do orçamento é obrigatória.',
        ]);

        NotaFiscal::create($validatedData);

        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex();

        session([self::SESSION_KEY => $urlVoltar]);

        return redirect($urlVoltar)
            ->with('success', 'Nota fiscal cadastrada com sucesso!');
    }

    public function show(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);

        $nota = NotaFiscal::with([
            'orcamento.clienteOrcamento',
            'tipo',
            'movimentacao'
        ])->findOrFail($id);

        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex();

        session([self::SESSION_KEY => $urlVoltar]);

        return view('view_nota_fiscal.show', compact('nota', 'urlVoltar'));
    }

    public function edit(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);

        $nota = NotaFiscal::with(['orcamento.clienteOrcamento', 'tipo'])
            ->findOrFail($id);

        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex();

        session([self::SESSION_KEY => $urlVoltar]);

        $categoriaNota = strtolower($nota->tipo->tipo_despesa ?? '');

        if (strtolower($nota->tipo->tipo_flu_nome ?? '') === 'caixa') {
            $categoriaNota = 'caixa';
        }

        return view('view_nota_fiscal.edit', [
            'nota' => $nota,
            'tipos' => TipoFluxoCaixa::all(),
            'movimentacoes' => Movimentacao::all(),
            'categoriaNota' => $categoriaNota,
            'urlVoltar' => $urlVoltar,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);

        $validatedData = $request->validate([
            'orcamento_id_orcamento' => 'required|integer|exists:orcamento,id_orcamento',
            'nota_numero' => 'required|string|max:100',
            'nota_id_tipo' => 'required|integer|exists:tipo_fluxo_caixa,id_tipo_fluxo',
            'nota_data' => 'required|date',
            'nota_id_movimentacao' => 'required|integer|exists:movimentacao,id_movimentacao',
            'nota_valor' => 'required|numeric',
            'nota_desc' => 'required|string|max:255',
        ]);

        $nota = NotaFiscal::findOrFail($id);
        $nota->update($validatedData);

        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex();

        session([self::SESSION_KEY => $urlVoltar]);

        return redirect($urlVoltar)
            ->with('success', 'Nota fiscal atualizada com sucesso!');
    }

    public function destroy(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);

        $nota = NotaFiscal::findOrFail($id);
        $nota->delete();

        $urlVoltar = url()->previous();
        $url = parse_url($urlVoltar);

        parse_str($url['query'] ?? '', $query);

        $paginaAtual = max(1, (int) ($query['page'] ?? 1));

        if ($paginaAtual > 1) {
            $requestFiltros = Request::create($urlVoltar, 'GET');
            $totalRestante = $this->queryNotas($requestFiltros)->count();
            $ultimaPagina = max(1, (int) ceil($totalRestante / 10));

            if ($paginaAtual > $ultimaPagina) {
                $query['page'] = $ultimaPagina;
                $urlVoltar = ($url['path'] ?? route('nota_fiscal.index')) . '?' . http_build_query($query);
            }
        }

        session([self::SESSION_KEY => $urlVoltar]);

        return redirect($urlVoltar)
            ->with('success', 'Nota fiscal excluída com sucesso!');
    }
}
