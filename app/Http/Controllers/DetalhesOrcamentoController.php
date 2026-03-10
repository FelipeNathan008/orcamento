<?php

namespace App\Http\Controllers;

use App\Models\DetalhesOrcamento;
use App\Models\Orcamento;
use App\Models\Produto;
use App\Models\ClienteOrcamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DetalhesOrcamentoController extends Controller
{
    /**
     * Exibe uma lista de todos os detalhes de orçamento.
     * Usa eager loading para carregar os produtos e orçamentos relacionados.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View|RedirectResponse
    {
        if (!$request->orcamento_id) {
            return redirect()->route('cliente_orcamento.index')
                ->with('error', 'Selecione um cliente para visualizar os orçamentos.');
        }

        $orcamento = Orcamento::with('clienteOrcamento')
            ->where('id_orcamento', $request->orcamento_id)
            ->firstOrFail();

        $detalhesOrcamento = DetalhesOrcamento::with(['produto'])
            ->where('orcamento_id_orcamento', $orcamento->id_orcamento)
            ->get();

        return view('view_detalhes_orcamento.index', compact(
            'detalhesOrcamento',
            'orcamento'
        ));
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
        $orcamento = Orcamento::with('clienteOrcamento')
            ->where('id_orcamento', $request->orcamento_id)
            ->firstOrFail();
        $produtos = Produto::all();
        $selectedOrcamentoId = $orcamento_id ?? $request->query('orcamento_id');

        return view('view_detalhes_orcamento.create', compact('orcamento', 'produtos', 'selectedOrcamentoId'));
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



            DetalhesOrcamento::create($validatedData);

            return redirect()->route(
                'detalhes_orcamento.index',
                ['orcamento_id' => $request->orcamento_id_orcamento]
            )->with('success', 'Detalhe de orçamento adicionado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
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
        $orcamento = Orcamento::with('clienteOrcamento')
            ->find($detalheOrcamento->orcamento_id_orcamento);
        return view('view_detalhes_orcamento.edit', compact('detalheOrcamento', 'orcamentos', 'produtos','orcamento'));
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

            return redirect()->route(
                'detalhes_orcamento.index',
                ['orcamento_id' => $request->orcamento_id_orcamento]
            )->with('success', 'Detalhe de orçamento editado com sucesso!');
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

        return redirect()->route('detalhes_orcamento.index', [
            'orcamento_id' => $detalheOrcamento->orcamento_id_orcamento
        ])->with('success', 'Detalhe de orçamento atualizado com sucesso!');
    }
}
