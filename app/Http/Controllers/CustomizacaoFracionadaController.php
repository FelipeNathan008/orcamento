<?php

namespace App\Http\Controllers;

use App\Models\CustomizacaoFracionada;
use App\Models\DetalhesOrcamentoFracionado;
use App\Models\OrcamentoFracionado;
use App\Models\Orcamento;
use App\Models\PrecoCustomizacao;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;

class CustomizacaoFracionadaController extends Controller
{
    public function index(Request $request): View|RedirectResponse
    {
        if (!$request->id_det_fracionado) {
            return redirect()->route('financeiro.index')
                ->with('error', 'Selecione um produto para visualizar as customizações.');
        }

        $detalhe = DetalhesOrcamentoFracionado::with('produto')
            ->findOrFail($request->id_det_fracionado);

        $orcamentoFracionado = OrcamentoFracionado::findOrFail($detalhe->orcamento_fracionado_id);

        $orcamento = Orcamento::with('clienteOrcamento')
            ->where('id_orcamento', $orcamentoFracionado->orcamento_id_orcamento)
            ->firstOrFail();

        $query = CustomizacaoFracionada::where(
            'detalhes_orcamento_fracionado_id',
            $detalhe->id_det_fracionado
        );

        if ($request->filled('tipo')) {
            $query->where('cust_tipo', $request->tipo);
        }

        if ($request->filled('local')) {
            $query->where('cust_local', $request->local);
        }

        if ($request->filled('posicao')) {
            $query->where('cust_posicao', $request->posicao);
        }

        if ($request->filled('formatacao')) {
            $query->where('cust_formatacao', $request->formatacao);
        }

        $customizacoes = $query
            ->orderBy('id_customizacao_fracionada')
            ->paginate(10)
            ->withQueryString();

        $baseQuery = fn() => CustomizacaoFracionada::where(
            'detalhes_orcamento_fracionado_id',
            $detalhe->id_det_fracionado
        );

        $tipos = $baseQuery()->select('cust_tipo')->distinct()->orderBy('cust_tipo')->pluck('cust_tipo');
        $locais = $baseQuery()->select('cust_local')->distinct()->orderBy('cust_local')->pluck('cust_local');
        $posicoes = $baseQuery()->select('cust_posicao')->distinct()->orderBy('cust_posicao')->pluck('cust_posicao');
        $formatacoes = $baseQuery()->select('cust_formatacao')->distinct()->orderBy('cust_formatacao')->pluck('cust_formatacao');

        // Totais calculados sobre TODAS as customizações do item (não só a página filtrada)
        $quantidade = (int) ($detalhe->det_quantidade ?? 0);
        $valorUnitario = (float) ($detalhe->det_valor_unit ?? 0);
        $totalItem = $quantidade * $valorUnitario;
        $totalCustomizacoes = $quantidade * $baseQuery()->sum('cust_valor');

        $totalGeral = $totalItem + $totalCustomizacoes;

        return view('view_customizacao_fracionada.index', [
            'customizacoes' => $customizacoes,
            'detalhe' => $detalhe,
            'orcamentoFracionado' => $orcamentoFracionado,
            'orcamento' => $orcamento,
            'cliente' => $orcamento->clienteOrcamento,
            'tipos' => $tipos,
            'locais' => $locais,
            'posicoes' => $posicoes,
            'formatacoes' => $formatacoes,
            'totalItem' => $totalItem,
            'totalCustomizacoes' => $totalCustomizacoes,
            'totalGeral' => $totalGeral,
        ]);
    }

    public function create(Request $request): View
    {
        $detalheId = $request->query('detalhe_id');

        if (!$detalheId) {
            abort(404);
        }

        $detalhe = DetalhesOrcamentoFracionado::with('produto')->findOrFail($detalheId);

        $orcamentoFracionado = OrcamentoFracionado::findOrFail($detalhe->orcamento_fracionado_id);

        $orcamento = Orcamento::with('clienteOrcamento')
            ->where('id_orcamento', $orcamentoFracionado->orcamento_id_orcamento)
            ->firstOrFail();

        $precos = PrecoCustomizacao::all();

        return view(
            'view_customizacao_fracionada.create',
            compact('detalhe', 'orcamentoFracionado', 'orcamento', 'precos')
        );
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'detalhes_orcamento_fracionado_id' => 'required|exists:detalhes_orcamento_fracionado,id_det_fracionado',
                'cust_tipo' => 'required|string|max:45',
                'cust_local' => 'required|string|max:45',
                'cust_posicao' => 'required|string|max:45',
                'cust_tamanho' => 'required|string|max:45',
                'cust_formatacao' => 'required|string|max:45',
                'cust_valor' => 'required|string',
                'cust_descricao' => 'nullable|string|max:90',
                'cust_imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $customizacaoData = $validatedData;

            if (isset($customizacaoData['cust_valor'])) {
                $valorLimpo = str_replace(['.', ','], ['', '.'], $customizacaoData['cust_valor']);
                $customizacaoData['cust_valor'] = (float) $valorLimpo;
            }

            if ($request->hasFile('cust_imagem')) {
                $image = $request->file('cust_imagem');
                $nomeImagem = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images_customizacoes'), $nomeImagem);
                $customizacaoData['cust_imagem'] = $nomeImagem;
            }

            CustomizacaoFracionada::create($customizacaoData);

            return redirect()
                ->route('customizacao_fracionado.index', [
                    'id_det_fracionado' => $customizacaoData['detalhes_orcamento_fracionado_id']
                ])
                ->with('success', 'Customização criada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao criar customização fracionada: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Não foi possível criar a Customização: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $customizacao = CustomizacaoFracionada::findOrFail($id);

        $detalhe = DetalhesOrcamentoFracionado::with('produto')
            ->findOrFail($customizacao->detalhes_orcamento_fracionado_id);

        $orcamentoFracionado = OrcamentoFracionado::findOrFail($detalhe->orcamento_fracionado_id);

        $orcamento = Orcamento::with('clienteOrcamento')
            ->where('id_orcamento', $orcamentoFracionado->orcamento_id_orcamento)
            ->firstOrFail();

        return view(
            'view_customizacao_fracionada.show',
            compact('customizacao', 'detalhe', 'orcamentoFracionado', 'orcamento')
        );
    }

    public function edit($id): View
    {
        $customizacao = CustomizacaoFracionada::findOrFail($id);

        $detalhe = DetalhesOrcamentoFracionado::with('produto')
            ->findOrFail($customizacao->detalhes_orcamento_fracionado_id);

        $orcamentoFracionado = OrcamentoFracionado::findOrFail($detalhe->orcamento_fracionado_id);

        $orcamento = Orcamento::with('clienteOrcamento')
            ->where('id_orcamento', $orcamentoFracionado->orcamento_id_orcamento)
            ->firstOrFail();

        $precos = PrecoCustomizacao::all();

        return view(
            'view_customizacao_fracionada.edit',
            compact('customizacao', 'detalhe', 'orcamentoFracionado', 'orcamento', 'precos')
        );
    }

    public function update(Request $request, $id)
    {
        try {
            $customizacao = CustomizacaoFracionada::findOrFail($id);

            $validatedData = $request->validate([
                'detalhes_orcamento_fracionado_id' => 'sometimes|required|exists:detalhes_orcamento_fracionado,id_det_fracionado',
                'cust_tipo' => 'sometimes|required|string|max:45',
                'cust_local' => 'sometimes|required|string|max:45',
                'cust_posicao' => 'sometimes|required|string|max:45',
                'cust_tamanho' => 'sometimes|required|string|max:45',
                'cust_formatacao' => 'sometimes|required|string|max:45',
                'cust_valor' => 'sometimes|required|string',
                'cust_descricao' => 'nullable|string|max:90',
                'cust_imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $customizacaoData = $validatedData;

            if (isset($customizacaoData['cust_valor'])) {
                $valorLimpo = str_replace(['.', ','], ['', '.'], $customizacaoData['cust_valor']);
                $customizacaoData['cust_valor'] = (float) $valorLimpo;
            }

            if ($request->hasFile('cust_imagem')) {

                $image = $request->file('cust_imagem');

                if ($customizacao->cust_imagem) {
                    $caminhoAntigo = public_path('images_customizacoes/' . $customizacao->cust_imagem);
                    if (File::exists($caminhoAntigo)) {
                        File::delete($caminhoAntigo);
                    }
                }

                $nomeImagem = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images_customizacoes'), $nomeImagem);
                $customizacaoData['cust_imagem'] = $nomeImagem;
            } else {
                unset($customizacaoData['cust_imagem']);
            }

            $customizacao->update($customizacaoData);

            return redirect()
                ->route('customizacao_fracionado.index', [
                    'id_det_fracionado' => $customizacao->detalhes_orcamento_fracionado_id
                ])
                ->with('success', 'Customização atualizada com sucesso!');
        } catch (ValidationException $e) {
            Log::error('Erro de validação ao atualizar customização fracionada: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erro inesperado ao atualizar customização fracionada: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Não foi possível atualizar a Customização: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $customizacao = CustomizacaoFracionada::findOrFail($id);

            $idDetFracionado = $customizacao->detalhes_orcamento_fracionado_id;

            if ($customizacao->cust_imagem) {
                $caminhoImagem = public_path('images_customizacoes/' . $customizacao->cust_imagem);
                if (File::exists($caminhoImagem)) {
                    File::delete($caminhoImagem);
                }
            }

            $customizacao->delete();

            return redirect()
                ->route('customizacao_fracionado.index', [
                    'id_det_fracionado' => $idDetFracionado
                ])
                ->with('success', 'Customização excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir customização fracionada: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Não foi possível excluir a Customização: ' . $e->getMessage());
        }
    }
}
