<?php

namespace App\Http\Controllers;

use App\Models\DetalhesOrcamentoFracionado;
use App\Models\OrcamentoFracionado;
use App\Models\Orcamento;
use App\Models\Produto;
use App\Models\DetalhesOrcamento;
use App\Models\CustomizacaoFracionada;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DetalhesOrcamentoFracionadoController extends Controller
{
    public function index(Request $request, $id): View|RedirectResponse
    {
        // Busca o orçamento fracionado
        $orcamentoFracionado = OrcamentoFracionado::findOrFail($id);

        // Busca o orçamento principal
        $orcamento = Orcamento::with('clienteOrcamento')
            ->where('id_orcamento', $orcamentoFracionado->orcamento_id_orcamento)
            ->firstOrFail();

        // Query dos detalhes
        $query = DetalhesOrcamentoFracionado::with([
            'produto',
            'customizacoes'
        ])->where(
            'orcamento_fracionado_id',
            $orcamentoFracionado->id_orcamento_fracionado
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

        $detalhesOrcamento = $query
            ->orderBy('id_det_fracionado')
            ->paginate(10)
            ->withQueryString();

        $familias = Produto::query()
            ->select('prod_familia')
            ->whereNotNull('prod_familia')
            ->distinct()
            ->orderBy('prod_familia')
            ->pluck('prod_familia');

        $todosDetalhes = DetalhesOrcamentoFracionado::with('customizacoes')
            ->where(
                'orcamento_fracionado_id',
                $orcamentoFracionado->id_orcamento_fracionado
            )
            ->get();

        $totalDetalhes = 0;
        $totalCustomizacoes = 0;

        foreach ($todosDetalhes as $detalhe) {

            $quantidade = (int) ($detalhe->det_quantidade ?? 0);
            $valorUnitario = (float) ($detalhe->det_valor_unit ?? 0);

            $totalDetalhes += $quantidade * $valorUnitario;

            foreach ($detalhe->customizacoes as $customizacao) {

                $valorCustomizacao = (float) ($customizacao->cust_valor ?? 0);

                // Cada customização é aplicada a cada unidade do produto,
                // igual ao cálculo feito em DetalhesOrcamento
                $totalCustomizacoes += $quantidade * $valorCustomizacao;
            }
        }

        $totalGeral = $totalDetalhes + $totalCustomizacoes;

        $totalGeral = $totalDetalhes + $totalCustomizacoes;

        return view(
            'view_detalhes_orcamento_fracionado.index',
            compact(
                'detalhesOrcamento',
                'orcamentoFracionado',
                'orcamento',
                'familias',
                'totalDetalhes',
                'totalCustomizacoes',
                'totalGeral'
            )
        );
    }

    public function create(Request $request, $id): View
    {
        $orcamentoFracionado = OrcamentoFracionado::findOrFail($id);

        $orcamento = Orcamento::with('clienteOrcamento')
            ->where('id_orcamento', $orcamentoFracionado->orcamento_id_orcamento)
            ->firstOrFail();

        // Subquery com a soma já alocada de cada item original em QUALQUER fracionado
        $alocadosSub = DetalhesOrcamentoFracionado::query()
            ->select('detalhes_orcamento_id_det')
            ->selectRaw('SUM(det_quantidade) as qtd_alocada')
            ->groupBy('detalhes_orcamento_id_det');

        // Query dos detalhes do orçamento ORIGINAL, com os mesmos filtros do molde,
        // já descontando o que está alocado em qualquer fracionado e escondendo
        // os itens 100% alocados (não sobra nada para incluir).
        $query = DetalhesOrcamento::with(['produto', 'customizacoes'])
            ->where('detalhes_orcamento.orcamento_id_orcamento', $orcamento->id_orcamento)
            ->leftJoinSub($alocadosSub, 'alocados', function ($join) {
                $join->on('detalhes_orcamento.id_det', '=', 'alocados.detalhes_orcamento_id_det');
            })
            ->select('detalhes_orcamento.*')
            ->selectRaw('COALESCE(alocados.qtd_alocada, 0) as qtd_alocada')
            ->whereRaw('detalhes_orcamento.det_quantidade > COALESCE(alocados.qtd_alocada, 0)');

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
                $q->where('prod_familia', $request->familia);
            });
        }

        $detalhesOrcamento = $query
            ->orderBy('detalhes_orcamento.id_det')
            ->paginate(10)
            ->withQueryString();

        $familias = Produto::query()
            ->select('prod_familia')
            ->whereNotNull('prod_familia')
            ->distinct()
            ->orderBy('prod_familia')
            ->pluck('prod_familia');

        return view(
            'view_detalhes_orcamento_fracionado.create',
            compact(
                'orcamento',
                'orcamentoFracionado',
                'detalhesOrcamento',
                'familias'
            )
        );
    }

    public function store(Request $request, $id): RedirectResponse
    {
        $orcamentoFracionado = OrcamentoFracionado::findOrFail($id);

        $orcamento = Orcamento::where(
            'id_orcamento',
            $orcamentoFracionado->orcamento_id_orcamento
        )->firstOrFail();

        $validator = Validator::make($request->all(), [
            'itens' => 'required|array|min:1',
            'itens.*' => 'required|integer|exists:detalhes_orcamento,id_det',
        ], [
            'itens.required' => 'Selecione ao menos um item para adicionar ao fracionado.',
        ]);

        // Validação "manual" das quantidades: nunca confiamos no que veio do
        // front (max do input pode ter sido alterado no navegador), então
        // recalculamos o restante disponível de cada item no servidor.
        $validator->after(function ($validator) use ($request) {

            $itens = $request->input('itens', []);
            $quantidades = $request->input('quantidades', []);

            foreach ($itens as $itemId) {

                $quantidadeInformada = $quantidades[$itemId] ?? null;

                // Campo vazio -> não avança de jeito nenhum
                if ($quantidadeInformada === null || trim((string) $quantidadeInformada) === '') {
                    $validator->errors()->add(
                        "quantidades.$itemId",
                        'Informe a quantidade deste item.'
                    );
                    continue;
                }

                if (!is_numeric($quantidadeInformada) || (int) $quantidadeInformada < 1) {
                    $validator->errors()->add(
                        "quantidades.$itemId",
                        'Quantidade inválida.'
                    );
                    continue;
                }

                $detalheOriginal = DetalhesOrcamento::find($itemId);

                if (!$detalheOriginal) {
                    continue;
                }

                $alocado = DetalhesOrcamentoFracionado::where(
                    'detalhes_orcamento_id_det',
                    $itemId
                )->sum('det_quantidade');

                $restante = $detalheOriginal->det_quantidade - $alocado;

                if ($restante <= 0) {
                    $validator->errors()->add(
                        "quantidades.$itemId",
                        'Este item já foi totalmente incluído em outro fracionado.'
                    );
                } elseif ((int) $quantidadeInformada > $restante) {
                    $validator->errors()->add(
                        "quantidades.$itemId",
                        "A quantidade máxima disponível para este item é {$restante}."
                    );
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        foreach ($validated['itens'] as $idDetOriginal) {

            $detalheOriginal = DetalhesOrcamento::with('customizacoes')
                ->findOrFail($idDetOriginal);

            $quantidade = (int) $request->input("quantidades.$idDetOriginal");

            // Trava final de segurança (redundante com a validação acima)
            $alocado = DetalhesOrcamentoFracionado::where(
                'detalhes_orcamento_id_det',
                $idDetOriginal
            )->sum('det_quantidade');

            $restante = $detalheOriginal->det_quantidade - $alocado;
            $quantidade = min($quantidade, max($restante, 0));

            if ($quantidade <= 0) {
                continue;
            }

            // Se esse MESMO item original já existe dentro DESTE fracionado,
            // apenas soma a quantidade no registro existente — não duplica
            // o detalhe nem as customizações.
            $detalheFracionadoExistente = DetalhesOrcamentoFracionado::where(
                'orcamento_fracionado_id',
                $orcamentoFracionado->id_orcamento_fracionado
            )
                ->where('detalhes_orcamento_id_det', $detalheOriginal->id_det)
                ->first();

            if ($detalheFracionadoExistente) {

                $detalheFracionadoExistente->increment('det_quantidade', $quantidade);

                // Segue para o próximo item sem recriar customizações
                continue;
            }

            $novoDetalhe = DetalhesOrcamentoFracionado::create([
                'orcamento_fracionado_id' => $orcamentoFracionado->id_orcamento_fracionado,
                'orcamento_cliente_orcamento_id_co' => $orcamento->cliente_orcamento_id_co,
                'produto_id_produto' => $detalheOriginal->produto_id_produto,
                'det_cod' => $detalheOriginal->det_cod,
                'det_categoria' => $detalheOriginal->det_categoria,
                'det_modelo' => $detalheOriginal->det_modelo,
                'det_cor' => $detalheOriginal->det_cor,
                'det_tamanho' => $detalheOriginal->det_tamanho,
                'det_quantidade' => $quantidade,
                'det_valor_unit' => $detalheOriginal->det_valor_unit,
                'det_genero' => $detalheOriginal->det_genero,
                'det_caract' => $detalheOriginal->det_caract,
                'det_observacao' => $detalheOriginal->det_observacao,
                'det_anotacao' => $detalheOriginal->det_anotacao,
                'detalhes_orcamento_id_det' => $detalheOriginal->id_det,
            ]);

            // Copia automaticamente todas as customizações do item original
            // (só na primeira vez que este item entra neste fracionado)
            foreach ($detalheOriginal->customizacoes as $customizacao) {
                CustomizacaoFracionada::create([
                    'detalhes_orcamento_fracionado_id' => $novoDetalhe->id_det_fracionado,
                    'cust_tipo' => $customizacao->cust_tipo,
                    'cust_local' => $customizacao->cust_local,
                    'cust_posicao' => $customizacao->cust_posicao,
                    'cust_tamanho' => $customizacao->cust_tamanho,
                    'cust_formatacao' => $customizacao->cust_formatacao,
                    'cust_descricao' => $customizacao->cust_descricao,
                    'cust_imagem' => $customizacao->cust_imagem,
                    'cust_valor' => $customizacao->cust_valor,
                ]);
            }
        }

        return redirect()
            ->route('detalhes_orcamento_fracionado.index', $orcamentoFracionado->id_orcamento_fracionado)
            ->with('success', 'Item(ns) adicionado(s) ao orçamento fracionado com sucesso!');
    }

    public function show($id): View
    {
        $detalheOrcamento = DetalhesOrcamentoFracionado::with([
            'produto',
            'customizacoes',
            'orcamentoFracionado'
        ])->findOrFail($id);

        // Busca o orçamento fracionado
        $orcamentoFracionado = OrcamentoFracionado::findOrFail(
            $detalheOrcamento->orcamento_fracionado_id
        );

        // Busca o orçamento principal
        $orcamento = Orcamento::with('clienteOrcamento')
            ->where(
                'id_orcamento',
                $orcamentoFracionado->orcamento_id_orcamento
            )
            ->firstOrFail();

        return view(
            'view_detalhes_orcamento_fracionado.show',
            compact(
                'detalheOrcamento',
                'orcamentoFracionado',
                'orcamento'
            )
        );
    }

    public function destroy($id): RedirectResponse
    {
        $detalhe = DetalhesOrcamentoFracionado::findOrFail($id);

        $orcamentoFracionadoId = $detalhe->orcamento_fracionado_id;

        $detalhe->customizacoes()->delete();

        $detalhe->delete();

        return redirect()
            ->route(
                'detalhes_orcamento_fracionado.index',
                $orcamentoFracionadoId
            )
            ->with(
                'success',
                'Detalhe de orçamento fracionado e suas customizações foram excluídos com sucesso!'
            );
    }
}
