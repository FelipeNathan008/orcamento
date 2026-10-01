<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\DetalhesOrcamento;
use App\Models\Orcamento;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DetalhesOrcamentoController extends Controller
{
    private const SESSION_KEY = 'detalhes_orcamento.index_url';

    private function urlIndex($orcamento): string
    {
        return session(self::SESSION_KEY, route('detalhes_orcamento.index', ['id' => CryptHelper::encrypt($orcamento->id_orcamento)]));
    }
    public function index(Request $request, $id): View|RedirectResponse
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $orcamentoId = CryptHelper::decrypt($id);

        $orcamento = Orcamento::with([
            'clienteOrcamento',
            'detalhesOrcamento.customizacoes',
            'detalhesOrcamento.produto'
        ])->findOrFail($orcamentoId);

        if ($request->has('scroll')) {
            session(['detalhes_orcamento.index_scroll' => (int) $request->input('scroll')]);
        }

        $detalhesOrcamento = $this->queryDetalhesOrcamento(
            $request,
            $orcamento->id_orcamento
        )
            ->with(['produto', 'customizacoes'])
            ->orderBy('id_det', 'asc')
            ->paginate(10)
            ->withQueryString();

        $urlOrcamento = session(
            'orcamento.index_url',
            route('orcamento.index', [
                'id' => CryptHelper::encrypt($orcamento->cliente_orcamento_id_co)
            ])
        );

        $familias = Produto::query()
            ->select('prod_familia')
            ->whereNotNull('prod_familia')
            ->where('prod_familia', '!=', '')
            ->distinct()
            ->orderBy('prod_familia')
            ->pluck('prod_familia');

        return view('view_detalhes_orcamento.index', compact(
            'detalhesOrcamento',
            'orcamento',
            'familias',
            'urlOrcamento'
        ));
    }

    private function queryDetalhesOrcamento(Request $request, int $orcamentoId)
    {
        $query = DetalhesOrcamento::where(
            'orcamento_id_orcamento',
            $orcamentoId
        );

        if ($request->filled('produto')) {
            $query->whereHas('produto', function ($q) use ($request) {
                $q->where(
                    'prod_nome',
                    'like',
                    '%' . trim($request->produto) . '%'
                );
            });
        }

        if ($request->filled('categoria')) {
            $query->whereHas('produto', function ($q) use ($request) {
                $q->where(
                    'prod_categoria',
                    'like',
                    '%' . trim($request->categoria) . '%'
                );
            });
        }

        if ($request->filled('cod_ref')) {
            $query->whereHas('produto', function ($q) use ($request) {
                $q->where(
                    'prod_cod',
                    'like',
                    '%' . trim($request->cod_ref) . '%'
                );
            });
        }

        if ($request->filled('familia')) {
            $query->whereHas('produto', function ($q) use ($request) {
                $q->where(
                    'prod_familia',
                    $request->familia
                );
            });
        }

        return $query;
    }

    public function create(Request $request, $id)
    {
        $orcamentoId = CryptHelper::decrypt($id);
        $orcamento = Orcamento::with('clienteOrcamento')->findOrFail($orcamentoId);

        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex($orcamento);

        session([self::SESSION_KEY => $urlVoltar]);

        return view('view_detalhes_orcamento.create', [
            'orcamento' => $orcamento,
            'selectedOrcamentoId' => $orcamento->id_orcamento,
            'urlVoltar' => $urlVoltar,
        ]);
    }

    public function buscarProdutos(Request $request)
    {
        $produtos = Produto::query()
            ->when($request->filled('produto_id'), function ($query) use ($request) {
                $query->where('id_produto', $request->produto_id);
            })
            ->when(
                !$request->filled('produto_id') && $request->filled('busca'),
                function ($query) use ($request) {
                    $busca = trim($request->busca);

                    $query->where(function ($q) use ($busca) {
                        $q->where('prod_cod', 'like', '%' . $busca . '%')
                            ->orWhere('prod_nome', 'like', '%' . $busca . '%')
                            ->orWhere('prod_categoria', 'like', '%' . $busca . '%')
                            ->orWhere('prod_modelo', 'like', '%' . $busca . '%')
                            ->orWhere('prod_cor', 'like', '%' . $busca . '%');
                    });
                }
            )
            ->orderBy('prod_nome')
            ->paginate(5);

        return response()->json([
            'produtos' => $produtos->map(function ($produto) {
                $tamanhos = is_array($produto->prod_tamanho)
                    ? $produto->prod_tamanho
                    : explode(',', $produto->prod_tamanho ?? '');

                return [
                    'id' => $produto->id_produto,
                    'nome' => $produto->prod_nome,
                    'cod' => $produto->prod_cod,
                    'familia' => $produto->prod_familia,
                    'material' => $produto->prod_material,
                    'categoria' => $produto->prod_categoria,
                    'modelo' => $produto->prod_modelo,
                    'cor' => $produto->prod_cor,
                    'genero' => $produto->prod_genero,
                    'caract' => $produto->prod_caract,
                    'tamanhos' => array_values(
                        array_filter(
                            array_map('trim', $tamanhos)
                        )
                    ),
                    'preco' => number_format(
                        $produto->prod_preco,
                        2,
                        ',',
                        ''
                    ),
                ];
            }),
            'current_page' => $produtos->currentPage(),
            'last_page' => $produtos->lastPage(),
            'total' => $produtos->total(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $valor = preg_replace(
                '/[^\d,]/',
                '',
                $request->input('det_valor_unit', '')
            );

            $valor = str_replace(',', '.', $valor);

            $quantidade = $request->input('det_quantidade');

            if ($quantidade !== null) {
                $quantidade = ltrim((string) $quantidade, '0');
                $quantidade = $quantidade === '' ? '0' : $quantidade;
            }

            $request->merge([
                'det_valor_unit' => $valor,
                'det_quantidade' => $quantidade,
            ]);

            $validatedData = $request->validate([
                'orcamento_id_orcamento' => 'required|integer|exists:orcamento,id_orcamento',
                'produto_id_produto' => 'required|integer|exists:produto,id_produto',
                'det_cod' => 'required|string|max:45',
                'det_categoria' => 'required|string|max:45',
                'det_modelo' => 'required|string|max:70',
                'det_cor' => 'required|string|max:20',
                'det_tamanho' => 'required|string|max:10',
                'det_quantidade' => 'required|integer|min:1',
                'det_valor_unit' => 'required|numeric|min:0|decimal:0,2',
                'det_genero' => 'required|string|max:20',
                'det_caract' => 'required|string|max:255',
                'det_observacao' => 'nullable|string|max:255',
                'det_anotacao' => 'nullable|string|max:255',
            ], [
                'det_caract.required' => 'Selecione uma característica do produto.',
            ]);


            $orcamento = Orcamento::find(
                $validatedData['orcamento_id_orcamento']
            );

            if (!$orcamento) {
                throw ValidationException::withMessages([
                    'orcamento_id_orcamento' => 'Orçamento selecionado não encontrado.'
                ]);
            }

            $produto = Produto::find(
                $validatedData['produto_id_produto']
            );

            if (!$produto) {
                throw ValidationException::withMessages([
                    'produto_id_produto' => 'Produto selecionado não encontrado.'
                ]);
            }

            $validatedData['det_nome'] = $produto->prod_nome;
            $validatedData['det_familia'] = $produto->prod_familia;
            $validatedData['det_material'] = $produto->prod_material;
            $validatedData['det_cod'] = $produto->prod_cod;
            $validatedData['det_categoria'] = $produto->prod_categoria;
            $validatedData['det_modelo'] = $produto->prod_modelo;
            $validatedData['det_cor'] = $produto->prod_cor;
            $validatedData['det_genero'] = $produto->prod_genero;

            $validatedData['orcamento_cliente_orcamento_id_co'] =
                $orcamento->cliente_orcamento_id_co;

            $validatedData['orcamento_cliente_id_cliente'] =
                $orcamento->cliente_orcamento_id_co;

            DetalhesOrcamento::create($validatedData);

            $urlVoltar = $request->filled('return_url') ? $request->input('return_url') : $this->urlIndex($orcamento);

            session([self::SESSION_KEY => $urlVoltar]);

            return redirect($urlVoltar)
                ->with('success', 'Detalhe de orçamento adicionado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Não foi possível adicionar o Detalhe de Orçamento: ' . $e->getMessage()
                )
                ->withInput();
        }
    }

    public function show(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);

        $detalheOrcamento = DetalhesOrcamento::with(['produto', 'orcamento.clienteOrcamento'])->findOrFail($id);

        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex($detalheOrcamento->orcamento);

        session([self::SESSION_KEY => $urlVoltar]);

        return view('view_detalhes_orcamento.show', ['detalheOrcamento' => $detalheOrcamento, 'urlVoltar' => $urlVoltar,]);
    }

    public function edit(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);
        $detalheOrcamento = DetalhesOrcamento::findOrFail($id);

        $orcamento = Orcamento::with('clienteOrcamento')
            ->findOrFail($detalheOrcamento->orcamento_id_orcamento);

        $urlVoltar = $request->filled('return_url')
            ? $request->input('return_url')
            : $this->urlIndex($orcamento);

        session([self::SESSION_KEY => $urlVoltar]);

        return view('view_detalhes_orcamento.edit', [
            'detalheOrcamento' => $detalheOrcamento,
            'orcamento' => $orcamento,
            'orcamentos' => Orcamento::with('clienteOrcamento')->get(),
            'urlVoltar' => $urlVoltar,
        ]);
    }

    public function update(Request $request, $id)
    {
        $id = CryptHelper::decrypt($id);

        $detalheOrcamento = DetalhesOrcamento::findOrFail($id);

        try {
            $valor = preg_replace(
                '/[^\d,]/',
                '',
                $request->input('det_valor_unit', '')
            );

            $valor = str_replace(',', '.', $valor);

            $quantidade = $request->input('det_quantidade');

            if ($quantidade !== null) {
                $quantidade = ltrim((string) $quantidade, '0');
                $quantidade = $quantidade === '' ? '0' : $quantidade;
            }

            $request->merge([
                'det_valor_unit' => $valor,
                'det_quantidade' => $quantidade,
            ]);

            $validatedData = $request->validate([
                'orcamento_id_orcamento' => 'required|integer|exists:orcamento,id_orcamento',
                'produto_id_produto' => 'required|integer|exists:produto,id_produto',
                'det_cod' => 'required|string|max:45',
                'det_categoria' => 'required|string|max:45',
                'det_modelo' => 'required|string|max:70',
                'det_cor' => 'required|string|max:20',
                'det_tamanho' => 'required|string|max:10',
                'det_quantidade' => 'required|integer|min:1',
                'det_valor_unit' => 'required|numeric|min:0|decimal:0,2',
                'det_genero' => 'required|string|max:20',
                'det_caract' => 'required|string|max:255',
                'det_observacao' => 'nullable|string|max:255',
                'det_anotacao' => 'nullable|string|max:255',
            ], [
                'det_caract.required' => 'Selecione uma característica do produto.',
            ]);

            $orcamento = Orcamento::find(
                $validatedData['orcamento_id_orcamento']
            );

            if (!$orcamento) {
                throw ValidationException::withMessages([
                    'orcamento_id_orcamento' => 'Orçamento selecionado não encontrado.'
                ]);
            }

            if (
                (int) $validatedData['produto_id_produto'] !==
                (int) $detalheOrcamento->produto_id_produto
            ) {
                $produto = Produto::find(
                    $validatedData['produto_id_produto']
                );

                if (!$produto) {
                    throw ValidationException::withMessages([
                        'produto_id_produto' => 'Produto selecionado não encontrado.'
                    ]);
                }

                $validatedData['det_nome'] = $produto->prod_nome;
                $validatedData['det_familia'] = $produto->prod_familia;
                $validatedData['det_material'] = $produto->prod_material;
                $validatedData['det_cod'] = $produto->prod_cod;
                $validatedData['det_categoria'] = $produto->prod_categoria;
                $validatedData['det_modelo'] = $produto->prod_modelo;
                $validatedData['det_cor'] = $produto->prod_cor;
                $validatedData['det_genero'] = $produto->prod_genero;
            } else {
                $validatedData['det_nome'] = $detalheOrcamento->det_nome;
                $validatedData['det_familia'] = $detalheOrcamento->det_familia;
                $validatedData['det_material'] = $detalheOrcamento->det_material;
                $validatedData['det_cod'] = $detalheOrcamento->det_cod;
                $validatedData['det_categoria'] = $detalheOrcamento->det_categoria;
                $validatedData['det_modelo'] = $detalheOrcamento->det_modelo;
                $validatedData['det_cor'] = $detalheOrcamento->det_cor;
                $validatedData['det_genero'] = $detalheOrcamento->det_genero;
            }

            $validatedData['orcamento_cliente_orcamento_id_co'] =
                $orcamento->cliente_orcamento_id_co;

            $validatedData['orcamento_cliente_id_cliente'] =
                $orcamento->cliente_orcamento_id_co;

            $detalheOrcamento->update($validatedData);

            $urlVoltar = $request->filled('return_url') ? $request->input('return_url') : $this->urlIndex($orcamento);

            session([self::SESSION_KEY => $urlVoltar]);

            return redirect($urlVoltar)
                ->with('success', 'Detalhe de orçamento editado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Não foi possível atualizar o Detalhe de Orçamento: ' . $e->getMessage()
                )
                ->withInput();
        }
    }

    public function destroy($id)
    {
        $id = CryptHelper::decrypt($id);

        $detalheOrcamento = DetalhesOrcamento::withCount('customizacoes')
            ->findOrFail($id);

        $orcamento = Orcamento::findOrFail(
            $detalheOrcamento->orcamento_id_orcamento
        );

        if ($detalheOrcamento->customizacoes_count > 0) {
            return redirect()
                ->back()
                ->with(
                    'error',
                    'Não é possível excluir este detalhe pois ele possui ' .
                        $detalheOrcamento->customizacoes_count .
                        ' customização(ões) vinculada(s). Exclua as customizações primeiro.'
                );
        }

        $detalheOrcamento->delete();

        $urlVoltar = url()->previous();
        $url = parse_url($urlVoltar);

        parse_str($url['query'] ?? '', $query);

        $paginaAtual = max(1, (int) ($query['page'] ?? 1));

        if ($paginaAtual > 1) {
            $requestFiltros = Request::create($urlVoltar, 'GET');

            $totalRestante = $this->queryDetalhesOrcamento(
                $requestFiltros,
                $orcamento->id_orcamento
            )->count();

            $ultimaPagina = max(1, (int) ceil($totalRestante / 2));

            if ($paginaAtual > $ultimaPagina) {
                $query['page'] = $ultimaPagina;

                $urlVoltar = ($url['path'] ?? '') . '?' . http_build_query($query);
            }
        }

        return redirect($urlVoltar)
            ->with(
                'success',
                'Detalhe de orçamento excluído com sucesso!'
            );
    }
}
