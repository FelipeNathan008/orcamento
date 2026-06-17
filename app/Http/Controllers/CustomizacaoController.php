<?php

namespace App\Http\Controllers;

use App\Models\Customizacao;
use App\Models\DetalhesOrcamento;
use App\Models\PrecoCustomizacao;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;

class CustomizacaoController extends Controller
{

    public function index(Request $request): View|RedirectResponse
    {
        if (!$request->id_det) {
            return redirect()->route('cliente_orcamento.index')
                ->with('error', 'Selecione um produto para visualizar as customizações.');
        }

        $detalhe = DetalhesOrcamento::with([
            'produto',
            'orcamento.clienteOrcamento'
        ])->findOrFail($request->id_det);

        $query = Customizacao::with([
            'detalhesOrcamento.produto',
            'detalhesOrcamento.orcamento.clienteOrcamento'
        ])
            ->where('detalhes_orcamento_id_det', $request->id_det);

        // Tipo
        if ($request->filled('tipo')) {
            $query->where('cust_tipo', $request->tipo);
        }

        // Local
        if ($request->filled('local')) {
            $query->where('cust_local', $request->local);
        }

        // Posição
        if ($request->filled('posicao')) {
            $query->where('cust_posicao', $request->posicao);
        }

        // Formatação
        if ($request->filled('formatacao')) {
            $query->where('cust_formatacao', $request->formatacao);
        }

        $customizacoes = $query
            ->orderBy('id_customizacao')
            ->paginate(10)
            ->withQueryString();

        $tipos = Customizacao::where('detalhes_orcamento_id_det', $request->id_det)
            ->select('cust_tipo')
            ->distinct()
            ->orderBy('cust_tipo')
            ->pluck('cust_tipo');

        $locais = Customizacao::where('detalhes_orcamento_id_det', $request->id_det)
            ->select('cust_local')
            ->distinct()
            ->orderBy('cust_local')
            ->pluck('cust_local');

        $posicoes = Customizacao::where('detalhes_orcamento_id_det', $request->id_det)
            ->select('cust_posicao')
            ->distinct()
            ->orderBy('cust_posicao')
            ->pluck('cust_posicao');

        $formatacoes = Customizacao::where('detalhes_orcamento_id_det', $request->id_det)
            ->select('cust_formatacao')
            ->distinct()
            ->orderBy('cust_formatacao')
            ->pluck('cust_formatacao');

        return view('view_customizacao.index', [
            'customizacoes' => $customizacoes,
            'detalhe' => $detalhe,
            'orcamento' => $detalhe->orcamento,
            'cliente' => $detalhe->orcamento->clienteOrcamento,
            'tipos' => $tipos,
            'locais' => $locais,
            'posicoes' => $posicoes,
            'formatacoes' => $formatacoes,
        ]);
    }

    public function camisa($id)
    {
        $customizacao = Customizacao::with([
            'detalhesOrcamento.produto',
            'detalhesOrcamento.orcamento.clienteOrcamento'
        ])->findOrFail($id);

        // ID do detalhe do orçamento
        $detalheId = $customizacao->detalhes_orcamento_id_det;

        // Todas as customizações desse detalhe
        $allCustomizacoesForDetail = Customizacao::where(
            'detalhes_orcamento_id_det',
            $detalheId
        )->get();

        return view(
            'view_customizacao.camisa',
            compact('customizacao', 'allCustomizacoesForDetail')
        );
    }

    public function create(Request $request): View
    {
        $detalheId = $request->query('detalhe_id');

        // Impede acessar sem detalhe
        if (!$detalheId) {
            abort(404);
        }

        // Busca somente o detalhe específico
        $detalhe = DetalhesOrcamento::with([
            'produto',
            'orcamento.clienteOrcamento',
            'customizacoes'
        ])->findOrFail($detalheId);

        $precos = PrecoCustomizacao::all();
        $customizacoes = Customizacao::all();

        return view('view_customizacao.create', compact('detalhe', 'precos', 'customizacoes'));
    }


    public function store(Request $request)
    {
        try {
            // Adiciona a validação para o novo campo `cust_valor`
            $validatedData = $request->validate([
                'detalhes_orcamento_id_det' => 'required|exists:detalhes_orcamento,id_det',
                'cust_tipo' => 'required|string|max:45',
                'cust_local' => 'required|string|max:45',
                'cust_posicao' => 'required|string|max:45',
                'cust_tamanho' => 'required|string|max:45',
                'cust_formatacao' => 'required|string|max:45',
                'cust_valor' => 'required|string', // A validação de 'string' é temporária para o tratamento
                'cust_descricao' => 'nullable|string|max:90',
                'cust_imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            ]);

            $customizacaoData = $validatedData;

            // Tratamento do valor da moeda para remover máscara e converter para decimal
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

            Customizacao::create($customizacaoData);

            return redirect()
                ->route('customizacao.index', [
                    'id_det' => $customizacaoData['detalhes_orcamento_id_det']
                ])
                ->with('success', 'Customização criada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao criar customização: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Não foi possível criar a Customização: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Customizacao $customizacao)
    {
        $customizacao->load('detalhesOrcamento.orcamento.clienteOrcamento', 'detalhesOrcamento.produto');
        return view('view_customizacao.show', compact('customizacao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $customizacao = Customizacao::with([
            'detalhesOrcamento.produto',
            'detalhesOrcamento.orcamento.clienteOrcamento'
        ])->findOrFail($id);

        // pega o detalhe a partir da customização
        $detalhe = $customizacao->detalhesOrcamento;

        // preços de customização
        $precos = PrecoCustomizacao::all();

        // customizações desse mesmo detalhe (igual create)
        $customizacoes = Customizacao::where(
            'detalhes_orcamento_id_det',
            $detalhe->id_det
        )->get();

        return view(
            'view_customizacao.edit',
            compact('customizacao', 'detalhe', 'precos', 'customizacoes')
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $customizacao = Customizacao::findOrFail($id);

            // Validação dos dados, agora incluindo o novo campo `cust_valor`
            $validatedData = $request->validate([
                'detalhes_orcamento_id_det' => 'sometimes|required|exists:detalhes_orcamento,id_det',
                'cust_tipo' => 'sometimes|required|string|max:45',
                'cust_local' => 'sometimes|required|string|max:45',
                'cust_posicao' => 'sometimes|required|string|max:45',
                'cust_tamanho' => 'sometimes|required|string|max:45',
                'cust_formatacao' => 'sometimes|required|string|max:45',
                'cust_valor' => 'sometimes|required|string', // A validação de 'string' é temporária para o tratamento
                'cust_descricao' => 'nullable|string|max:90',
                'cust_imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            ]);

            $customizacaoData = $validatedData;

            // Tratamento do valor da moeda para remover máscara e converter para decimal
            if (isset($customizacaoData['cust_valor'])) {
                $valorLimpo = str_replace(['.', ','], ['', '.'], $customizacaoData['cust_valor']);
                $customizacaoData['cust_valor'] = (float) $valorLimpo;
            }

            if ($request->hasFile('cust_imagem')) {

                $image = $request->file('cust_imagem');

                // apagar imagem antiga
                if ($customizacao->cust_imagem) {

                    $caminhoAntigo = public_path('images_customizacoes/' . $customizacao->cust_imagem);

                    if (File::exists($caminhoAntigo)) {
                        File::delete($caminhoAntigo);
                    }
                }

                // salvar nova imagem
                $nomeImagem = time() . '_' . $image->getClientOriginalName();

                $image->move(public_path('images_customizacoes'), $nomeImagem);

                $customizacaoData['cust_imagem'] = $nomeImagem;
            } else {

                unset($customizacaoData['cust_imagem']);
            }

            $customizacao->update($customizacaoData);

            return redirect()
                ->route('customizacao.index', [
                    'id_det' => $customizacao->detalhes_orcamento_id_det
                ])
                ->with('success', 'Customização atualizada com sucesso!');
        } catch (ValidationException $e) {
            Log::error('Erro de validação ao atualizar customização: ' . $e->getMessage(), ['errors' => $e->errors()]);
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Erro inesperado ao atualizar customização: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Não foi possível atualizar a Customização: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            $customizacao = Customizacao::findOrFail($id);

            $idDet = $customizacao->detalhes_orcamento_id_det;

            // apagar imagem
            if ($customizacao->cust_imagem) {

                $caminhoImagem = public_path('images_customizacoes/' . $customizacao->cust_imagem);

                if (File::exists($caminhoImagem)) {
                    File::delete($caminhoImagem);
                }
            }

            $customizacao->delete();

            return redirect()
                ->route('customizacao.index', [
                    'id_det' => $idDet
                ])
                ->with('success', 'Customização excluída com sucesso!');
        } catch (\Exception $e) {

            Log::error('Erro ao excluir customização: ' . $e->getMessage(), ['exception' => $e]);

            return redirect()->back()->with('error', 'Não foi possível excluir a Customização: ' . $e->getMessage());
        }
    }
}
