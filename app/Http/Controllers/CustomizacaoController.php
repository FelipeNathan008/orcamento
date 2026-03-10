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

        $customizacoes = Customizacao::with([
            'detalhesOrcamento.produto',
            'detalhesOrcamento.orcamento.clienteOrcamento'
        ])
            ->where('detalhes_orcamento_id_det', $request->id_det)
            ->get();

        return view('view_customizacao.index', [
            'customizacoes' => $customizacoes,
            'detalhe' => $detalhe,
            'orcamento' => $detalhe->orcamento,
            'cliente' => $detalhe->orcamento->clienteOrcamento
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
                $customizacaoData['cust_imagem'] = file_get_contents($image->getRealPath()); // Salva como BINARY/BLOB
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
                $customizacaoData['cust_imagem'] = file_get_contents($image->getRealPath());
            } else {
                // Se nenhum novo arquivo foi enviado, mantenha a imagem existente
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
