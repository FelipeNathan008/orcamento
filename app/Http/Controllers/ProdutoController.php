<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProdutoController extends Controller
{
    private const SESSION_KEY = 'produto.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('produto.index'));
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $produtos = Produto::query()
            ->when($request->busca, function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('prod_nome', 'like', '%' . $request->busca . '%')
                        ->orWhere('prod_cod', 'like', '%' . $request->busca . '%');
                });
            })
            ->when($request->familia, function ($query) use ($request) {
                $query->where('prod_familia', $request->familia);
            })
            ->when($request->categoria, function ($query) use ($request) {
                $query->where('prod_categoria', $request->categoria);
            })
            ->orderBy('id_produto')
            ->paginate(10)
            ->withQueryString();

        $familias = Produto::select('prod_familia')
            ->distinct()
            ->orderBy('prod_familia')
            ->pluck('prod_familia');

        $categorias = Produto::select('prod_categoria')
            ->distinct()
            ->orderBy('prod_categoria')
            ->pluck('prod_categoria');

        return view('view_produto.index', compact('produtos', 'familias', 'categorias'));
    }

    public function create()
    {
        return view('view_produto.create', [
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'prod_cod' => 'required|string|max:45|unique:produto',
                'prod_nome' => 'required|string|max:85',
                'prod_familia' => 'required|string|max:25',
                'prod_categoria' => 'required|string|max:45',
                'prod_material' => 'required|string|max:45',
                'prod_genero' => 'required|string|max:20',
                'prod_modelo' => 'required|string|max:70',
                'prod_caract' => 'required|string|max:55',
                'prod_cor' => 'required|string|max:20',
                'prod_preco' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'prod_tamanho' => 'required|array',
                'prod_tamanho.*' => 'string|max:10',
            ], [
                'prod_cod.unique' => 'Já existe um produto cadastrado com este código.',
                'prod_tamanho.required' => 'Selecione pelo menos um tamanho para o produto.',
            ]);

            Produto::create($validatedData);

            return redirect($this->urlIndex())
                ->with('success', 'Produto criado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Não foi possível criar o produto: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $produto = Produto::findOrFail($id);

        return view('view_produto.show', [
            'produto' => $produto,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $produto = Produto::findOrFail($id);

        return view('view_produto.edit', [
            'produto' => $produto,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        try {
            $id = CryptHelper::decrypt($id);
            $produto = Produto::findOrFail($id);

            $validatedData = $request->validate([
                'prod_cod' => 'required|string|max:45|unique:produto,prod_cod,' . $id . ',id_produto',
                'prod_nome' => 'required|string|max:85',
                'prod_familia' => 'required|string|max:25',
                'prod_categoria' => 'required|string|max:45',
                'prod_material' => 'required|string|max:45',
                'prod_genero' => 'required|string|max:20',
                'prod_modelo' => 'required|string|max:70',
                'prod_caract' => 'required|string|max:55',
                'prod_cor' => 'required|string|max:20',
                'prod_preco' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'prod_tamanho' => 'required|array',
                'prod_tamanho.*' => 'string|max:10',
            ], [
                'prod_cod.unique' => 'Já existe um produto cadastrado com este código.',
                'prod_tamanho.required' => 'Selecione pelo menos um tamanho para o produto.',
            ]);

            $produto->update($validatedData);

            return redirect($this->urlIndex())
                ->with('success', 'Produto atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            report($e);

            return redirect()->back()
                ->with('error', 'Não foi possível atualizar o produto: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        $id = CryptHelper::decrypt($id);
        $produto = Produto::withCount('detalhesOrcamento')->findOrFail($id);

        if ($produto->detalhes_orcamento_count > 0) {
            return redirect($this->urlIndex())
                ->with('error', 'Não é possível excluir este produto pois ele está vinculado a ' . $produto->detalhes_orcamento_count . ' detalhe(s) de orçamento');
        }

        try {
            $produto->delete();

            return redirect($this->urlIndex())
                ->with('success', 'Produto excluído com sucesso!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect($this->urlIndex())
                    ->with('error', 'Não foi possível excluir o produto pois ele está vinculado a outros registros.');
            }

            return redirect($this->urlIndex())
                ->with('error', 'Ocorreu um erro inesperado ao tentar excluir o produto.');
        }
    }
}