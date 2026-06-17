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

    public function index(Request $request): View|RedirectResponse
    {
        if (!$request->orcamento_id) {
            return redirect()->route('cliente_orcamento.index')
                ->with('error', 'Selecione um cliente para visualizar os orçamentos.');
        }

        $orcamento = Orcamento::with('clienteOrcamento')
            ->where('id_orcamento', $request->orcamento_id)
            ->firstOrFail();

        $query = DetalhesOrcamento::with('produto')
            ->where('orcamento_id_orcamento', $orcamento->id_orcamento);

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

        // Família
        if ($request->filled('familia')) {

            $query->whereHas('produto', function ($q) use ($request) {
                $q->where('prod_familia', $request->familia);
            });
        }

        $detalhesOrcamento = $query
            ->orderBy('id_det')
            ->paginate(10)
            ->withQueryString();

        $familias = Produto::query()
            ->select('prod_familia')
            ->distinct()
            ->orderBy('prod_familia')
            ->pluck('prod_familia');

        return view(
            'view_detalhes_orcamento.index',
            compact(
                'detalhesOrcamento',
                'orcamento',
                'familias'
            )
        );
    }

    public function create(Request $request, $orcamento_id = null)
    {
        $orcamento = Orcamento::with('clienteOrcamento')
            ->where('id_orcamento', $request->orcamento_id)
            ->firstOrFail();
        $produtos = Produto::all();
        $selectedOrcamentoId = $orcamento_id ?? $request->query('orcamento_id');

        return view('view_detalhes_orcamento.create', compact('orcamento', 'produtos', 'selectedOrcamentoId'));
    }


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
                'det_caract' => 'required|string|max:255',
                'det_observacao' => 'nullable|string|max:255',
                'det_anotacao' => 'nullable|string|max:255',
            ], [
                'det_caract.required' => 'Selecione uma característica do produto.',
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


    public function show($id)
    {
        $detalheOrcamento = DetalhesOrcamento::with(['produto', 'orcamento.clienteOrcamento'])->findOrFail($id);
        return view('view_detalhes_orcamento.show', compact('detalheOrcamento'));
    }


    public function edit($id)
    {
        $detalheOrcamento = DetalhesOrcamento::findOrFail($id);
        $orcamentos = Orcamento::with('clienteOrcamento')->get();
        $produtos = Produto::all();
        $orcamento = Orcamento::with('clienteOrcamento')
            ->find($detalheOrcamento->orcamento_id_orcamento);
        return view('view_detalhes_orcamento.edit', compact('detalheOrcamento', 'orcamentos', 'produtos', 'orcamento'));
    }


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

    public function destroy($id)
    {
        $detalheOrcamento = DetalhesOrcamento::findOrFail($id);

        $detalheOrcamento->delete();

        return redirect()->route('detalhes_orcamento.index', [
            'orcamento_id' => $detalheOrcamento->orcamento_id_orcamento
        ])->with('success', 'Detalhe de orçamento atualizado com sucesso!');
    }
}
