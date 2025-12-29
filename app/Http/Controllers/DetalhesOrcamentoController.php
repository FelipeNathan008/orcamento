<?php

namespace App\Http\Controllers;

use App\Models\DetalhesOrcamento;
use App\Models\Orcamento;
use App\Models\Produto;
use App\Models\ClienteOrcamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DetalhesOrcamentoController extends Controller
{
    /**
     * Exibe uma lista de todos os detalhes de orçamento.
     * Usa eager loading para carregar os produtos e orçamentos relacionados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Eager loading das relações
        $query = DetalhesOrcamento::with(['produto', 'orcamento.clienteOrcamento']);

        // Filtro por 'Orçamento Ref.' (orcamento_id_orcamento)
        if ($request->filled('search_orcamento_id')) {
            $query->where('orcamento_id_orcamento', 'like', '%' . $request->input('search_orcamento_id') . '%');
        }

        // Filtro por 'Cliente' (nome do cliente no relacionamento)
        if ($request->filled('search_cliente_nome')) {
            $query->whereHas('orcamento.clienteOrcamento', function ($q) use ($request) {
                $q->where('clie_orc_nome', 'like', '%' . $request->input('search_cliente_nome') . '%');
            });
        }

        // Filtro por 'Cód. Detalhe' (det_cod)
        // Código CORRETO
        if ($request->filled('search_detalhe_cod')) {
            $query->where('id_det', 'like', '%' . $request->input('search_detalhe_cod') . '%');
        }
        // Obtém os resultados
        $detalhesOrcamento = $query->get();

        // Retorna a view com os resultados filtrados
        return view('view_detalhes_orcamento.index', compact('detalhesOrcamento'));
    }

    /**
     * Exibe o formulário para criar um novo detalhe de orçamento.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null  $orcamento_id  Opcional: ID do orçamento para pré-selecionar
     * @return \Illuminate\View\View
     */
    public function create(Request $request, $orcamento_id = null)
    {
        $orcamentos = Orcamento::with('clienteOrcamento')->get(); // Carrega orçamentos com os clientes
        $produtos = Produto::all(); // Carrega todos os produtos
        // Prioriza o parâmetro de rota, depois o query parameter, depois null
        $selectedOrcamentoId = $orcamento_id ?? $request->query('orcamento_id');

        return view('view_detalhes_orcamento.create', compact('orcamentos', 'produtos', 'selectedOrcamentoId'));
    }

    /**
     * Armazena um novo detalhe de orçamento no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        try {
            // Remove pontos de milhares e substitui vírgula por ponto decimal ANTES da validação
            $request->merge([
                'det_valor_unit' => str_replace(',', '.', str_replace('.', '', $request->input('det_valor_unit')))
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
                'det_caract' => 'required|string|max:255', // ALTERADO: Aumentado para 255
                'det_observacao' => 'nullable|string|max:255',
                'det_anotacao' => 'nullable|string|max:255',
            ]);

            // Preencher orcamento_cliente_orcamento_id_co e orcamento_cliente_id_cliente
            $orcamento = Orcamento::find($validatedData['orcamento_id_orcamento']);
            if (!$orcamento) {
                // Esta validação já é coberta pelo 'exists', mas é uma boa redundância.
                throw ValidationException::withMessages(['orcamento_id_orcamento' => 'Orçamento selecionado não encontrado.']);
            }
            $validatedData['orcamento_cliente_orcamento_id_co'] = $orcamento->cliente_orcamento_id_co;
            $validatedData['orcamento_cliente_id_cliente'] = $orcamento->cliente_orcamento_id_co; // Se for a mesma coluna, ok

            // IMPORTANTE: Certifique-se de que todas as chaves em $validatedData (incluindo
            // 'orcamento_cliente_orcamento_id_co' e 'orcamento_cliente_id_cliente')
            // estão listadas na propriedade $fillable do seu modelo DetalhesOrcamento.
            // Exemplo no modelo DetalhesOrcamento.php:
            // protected $fillable = [
            //     'orcamento_id_orcamento',
            //     'produto_id_produto',
            //     'det_cod',
            //     'det_categoria',
            //     'det_modelo',
            //     'det_cor',
            //     'det_tamanho',
            //     'det_quantidade',
            //     'det_valor_unit',
            //     'det_genero',
            //     'det_caract',
            //     'det_observacao',
            //     'det_anotacao',
            //     'orcamento_cliente_orcamento_id_co', // Adicione esta linha
            //     'orcamento_cliente_id_cliente',      // E esta linha
            // ];

            DetalhesOrcamento::create($validatedData);

            return redirect()->route('detalhes_orcamento.index')->with('success', 'Detalhe de orçamento adicionado com sucesso!');
        } catch (ValidationException $e) {
            // Se cair aqui, a validação falhou. Os erros devem ser exibidos no frontend.
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Se cair aqui, ocorreu um erro inesperado após a validação.
            // A mensagem de erro será exibida no frontend.
            // Removendo dd($e->getMessage()) para que o erro seja exibido no frontend.
            return redirect()->back()->with('error', 'Não foi possível adicionar o Detalhe de Orçamento: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Exibe os detalhes de um detalhe de orçamento específico.
     * Usa eager loading para carregar os produtos e orçamentos relacionados.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $detalheOrcamento = DetalhesOrcamento::with(['produto', 'orcamento.clienteOrcamento'])->findOrFail($id);
        return view('view_detalhes_orcamento.show', compact('detalheOrcamento'));
    }

    /**
     * Exibe o formulário para editar um detalhe de orçamento específico.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $detalheOrcamento = DetalhesOrcamento::findOrFail($id);
        $orcamentos = Orcamento::with('clienteOrcamento')->get();
        $produtos = Produto::all();
        return view('view_detalhes_orcamento.edit', compact('detalheOrcamento', 'orcamentos', 'produtos'));
    }

    /**
     * Atualiza um detalhe de orçamento existente no banco de dados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $detalheOrcamento = DetalhesOrcamento::findOrFail($id);

        try {
            // Remove pontos de milhares e substitui vírgula por ponto decimal ANTES da validação
            $request->merge([
                'det_valor_unit' => str_replace(',', '.', str_replace('.', '', $request->input('det_valor_unit')))
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
                'det_caract' => 'required|string|max:255', // ALTERADO: Aumentado para 255
                'det_observacao' => 'nullable|string|max:255',
                'det_anotacao' => 'nullable|string|max:255',
            ]);

            // Preencher orcamento_cliente_orcamento_id_co e orcamento_cliente_id_cliente
            $orcamento = Orcamento::find($validatedData['orcamento_id_orcamento']);
            if (!$orcamento) {
                throw ValidationException::withMessages(['orcamento_id_orcamento' => 'Orçamento selecionado não encontrado.']);
            }
            $validatedData['orcamento_cliente_orcamento_id_co'] = $orcamento->cliente_orcamento_id_co;
            $validatedData['orcamento_cliente_id_cliente'] = $orcamento->cliente_orcamento_id_co;


            $detalheOrcamento->update($validatedData);

            return redirect()->route('detalhes_orcamento.index')->with('success', 'Detalhe de orçamento atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível atualizar o Detalhe de Orçamento: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove um detalhe de orçamento específico do banco de dados.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $detalheOrcamento = DetalhesOrcamento::findOrFail($id);
        $detalheOrcamento->delete();

        return redirect()->route('detalhes_orcamento.index')->with('success', 'Detalhe de orçamento excluído com sucesso!');
    }
}
