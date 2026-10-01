<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PrecoCustomizacao;

class PrecoCustomizacaoController extends Controller
{
    private const SESSION_KEY = 'preco_customizacao.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('preco_customizacao.index'));
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $precosCustomizacao = PrecoCustomizacao::query()
            ->when($request->tipo, function ($query) use ($request) {
                $query->where('preco_tipo', 'like', '%' . $request->tipo . '%');
            })
            ->when($request->tamanho, function ($query) use ($request) {
                $query->where('preco_tamanho', 'like', '%' . $request->tamanho . '%');
            })
            ->paginate(10)
            ->withQueryString();

        return view('view_preco_customizacao.index', compact('precosCustomizacao'));
    }

    public function create()
    {
        return view('view_preco_customizacao.create', [
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function store(Request $request)
    {
        $valor = str_replace(['R$', '.', ','], ['', '', '.'], $request->preco_valor);

        $request->merge([
            'preco_valor' => $valor
        ]);

        $validatedData = $request->validate([
            'preco_tipo' => 'required|string|max:45',
            'preco_tamanho' => 'required|string|max:30',
            'preco_valor' => 'required|numeric|max:99999.99',
        ]);

        PrecoCustomizacao::create($validatedData);

        return redirect($this->urlIndex())
            ->with('success', 'Preço de customização criado com sucesso!');
    }

    public function show(string $id)
    {
        $precoCustomizacao = PrecoCustomizacao::findOrFail($id);

        return view('preco_customizacao.show', [
            'precoCustomizacao' => $precoCustomizacao,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit(string $id)
    {
        $precoCustomizacao = PrecoCustomizacao::findOrFail($id);

        return view('view_preco_customizacao.edit', [
            'precoCustomizacao' => $precoCustomizacao,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $valor = str_replace(['R$', '.', ','], ['', '', '.'], $request->preco_valor);

        $request->merge([
            'preco_valor' => $valor
        ]);

        $validatedData = $request->validate([
            'preco_tipo' => 'required|string|max:45',
            'preco_tamanho' => 'required|string|max:30',
            'preco_valor' => 'required|numeric|max:99999.99',
        ]);

        $precoCustomizacao = PrecoCustomizacao::findOrFail($id);
        $precoCustomizacao->update($validatedData);

        return redirect($this->urlIndex())
            ->with('success', 'Preço de customização atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $precoCustomizacao = PrecoCustomizacao::findOrFail($id);
        $precoCustomizacao->delete();

        return redirect($this->urlIndex())
            ->with('success', 'Preço de customização excluído com sucesso!');
    }
}
