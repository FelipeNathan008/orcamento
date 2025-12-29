<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $produtos = Produto::all();
        // Adicionado para obter as famílias únicas e passá-las para a view
        $familias = $produtos->pluck('prod_familia')->unique()->sort();

        return view('view_produto.index', compact('produtos', 'familias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('view_produto.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // ** DEBUG: Esta linha mostra os dados recebidos. Comente-a após a depuração. **
        // dd($request->all()); 

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
            ]);

            Produto::create($validatedData);

            return redirect()->route('produto.index')->with('success', 'Produto criado com sucesso!');
        } catch (ValidationException $e) {
            // ** DEBUG: Esta linha mostra os erros de validação. Comente-a após a depuração. **
            // dd($e->errors()); 
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // ** DEBUG: Esta linha mostra qualquer outra exceção. Comente-a após a depuração. **
            // dd($e->getMessage()); 
            return redirect()->back()->with('error', 'Não foi possível criar o produto: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $produto = Produto::findOrFail($id);
        return view('view_produto.show', compact('produto'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $produto = Produto::findOrFail($id);
        return view('view_produto.edit', compact('produto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $produto = Produto::findOrFail($id);

            $validatedData = $request->validate([
                'prod_cod' => 'sometimes|required|string|max:45|unique:produto,prod_cod,' . $id . ',id_produto',
                'prod_nome' => 'sometimes|required|string|max:85',
                'prod_familia' => 'sometimes|required|string|max:25',
                'prod_categoria' => 'sometimes|required|string|max:45',
                'prod_material' => 'sometimes|required|string|max:45',
                'prod_genero' => 'sometimes|required|string|max:20',
                'prod_modelo' => 'sometimes|required|string|max:70',
                'prod_caract' => 'sometimes|required|string|max:55',
                'prod_cor' => 'sometimes|required|string|max:20',
                'prod_preco' => 'sometimes|required|numeric|regex:/^\d+(\.\d{1,2})?$/',
                'prod_tamanho' => 'sometimes|required|array',
                'prod_tamanho.*' => 'string|max:10',
            ]);

            $produto->update($validatedData);

            return redirect()->route('produto.show', $produto->id_produto)->with('success', 'Produto atualizado com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Não foi possível atualizar o produto: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();

        return redirect()->route('produto.index')->with('success', 'Produto excluído com sucesso!');
    }
}