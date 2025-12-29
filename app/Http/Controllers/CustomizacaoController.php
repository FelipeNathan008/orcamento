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

class CustomizacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Customizacao::with('detalhesOrcamento.orcamento.clienteOrcamento');

        // Filtro por ID do Orçamento (mantém busca por contenção)
        if ($request->filled('search_orcamento_id')) {
            $searchTerm = $request->input('search_orcamento_id');
            $query->whereHas('detalhesOrcamento.orcamento', function ($q) use ($searchTerm) {
                // Usar 'like' com curingas para buscar IDs que contenham o termo
                $q->where('id_orcamento', 'like', "%{$searchTerm}%");
            });
        }

        // Filtro por Nome do Cliente (mantém LIKE com curingas)
        if ($request->filled('search_cliente_nome')) {
            $searchTerm = $request->input('search_cliente_nome');
            $query->whereHas('detalhesOrcamento.orcamento.clienteOrcamento', function ($q) use ($searchTerm) {
                $q->where('clie_orc_nome', 'like', "%{$searchTerm}%");
            });
        }

        // Filtro por ID do Detalhe do Orçamento (AGORA BUSCA EXATA)
        if ($request->filled('search_detalhes_id')) {
            $searchTerm = $request->input('search_detalhes_id');
            $query->whereHas('detalhesOrcamento', function ($q) use ($searchTerm) {
                // ALTERADO: Usar '=' para buscar o ID exato
                $q->where('id_det', $searchTerm);
            });
        }

        $customizacoes = $query->get();

        // Carrega todos os detalhes de orçamento para popular o select na view index
        $detalhesOrcamento = DetalhesOrcamento::with(['produto', 'orcamento.clienteOrcamento'])->get();

        // Passa ambas as variáveis para a view
        return view('view_customizacao.index', compact('customizacoes', 'detalhesOrcamento'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        // Busca todos os preços de customização.
        $precos = PrecoCustomizacao::all();

        // Busca todos os detalhes de orçamento com os respectivos produtos e clientes.
        $detalhesOrcamento = DetalhesOrcamento::with(['produto', 'orcamento.clienteOrcamento', 'customizacoes'])->get();

        // Busca todas as customizações para que o JavaScript possa filtrar.
        $customizacoes = Customizacao::all();

        $detalheId = $request->query('detalhe_id');

        return view('view_customizacao.create', compact('detalhesOrcamento', 'precos', 'customizacoes', 'detalheId'));
    }

    /**
     * Store a newly created resource in storage.
     */
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

            return redirect()->route('customizacao.index')->with('success', 'Customização criada com sucesso!');
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
        $customizacao = Customizacao::findOrFail($id);
        // Garante que os relacionamentos necessários para o select de detalhes de orçamento
        // e para a exibição do produto estejam carregados.
        $detalhesOrcamento = DetalhesOrcamento::with(['produto', 'orcamento.clienteOrcamento'])->get();
        
        // BUSCA TODOS OS PREÇOS DE CUSTOMIZAÇÃO PARA PASSAR PARA A VIEW
        $precos = PrecoCustomizacao::all();

        return view('view_customizacao.edit', compact('customizacao', 'detalhesOrcamento', 'precos'));
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

            return redirect()->route('customizacao.index', ['search_detalhes_id' => $customizacao->detalhes_orcamento_id_det])->with('success', 'Customização atualizada com sucesso!');
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
            $customizacao->delete();
            return redirect()->route('customizacao.index')->with('success', 'Customização excluída com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao excluir customização: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->back()->with('error', 'Não foi possível excluir a Customização: ' . $e->getMessage());
        }
    }

    /**
     * Retorna customizações para um dado detalhes_orcamento_id_det via API.
     */
    public function getCustomizacoesPorDetalhe($detalhes_orcamento_id_det)
    {
        // Busca as customizações associadas ao detalhe do orçamento,
        // incluindo os relacionamentos necessários para a tabela.
        $customizacoes = Customizacao::where('detalhes_orcamento_id_det', $detalhes_orcamento_id_det)
            ->with('detalhesOrcamento.orcamento.clienteOrcamento', 'detalhesOrcamento.produto')
            ->get();

        return response()->json($customizacoes);
    }
}
