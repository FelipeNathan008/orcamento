<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\TipoGrupoPrincipal;
use Illuminate\Http\Request;

class TipoGrupoPrincipalController extends Controller
{
    private const SESSION_KEY = 'tipo_grupo_principal.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('tipo_grupo_principal.index'));
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $gruposPrincipais = TipoGrupoPrincipal::query()
            ->when($request->nome, function ($query) use ($request) {
                $query->where('grupo_principal_nome', 'like', '%' . $request->nome . '%');
            })
            ->orderBy('grupo_principal_nome', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('view_tipo_grupo_principal.index', compact('gruposPrincipais'));
    }

    public function create()
    {
        return view('view_tipo_grupo_principal.create', [
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grupo_principal_nome' => 'required|string|max:255|unique:tipo_grupo_principal,grupo_principal_nome',
        ], [
            'grupo_principal_nome.unique' => 'Já existe um grupo principal com esse nome.',
        ]);

        TipoGrupoPrincipal::create($validated);

        return redirect($this->urlIndex())
            ->with('success', 'Grupo principal criado com sucesso!');
    }

    public function show(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $grupoPrincipal = TipoGrupoPrincipal::with('subGrupos')->findOrFail($id);

        return view('view_tipo_grupo_principal.show', [
            'grupoPrincipal' => $grupoPrincipal,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $grupoPrincipal = TipoGrupoPrincipal::findOrFail($id);

        return view('view_tipo_grupo_principal.edit', [
            'grupoPrincipal' => $grupoPrincipal,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);

        $grupoPrincipal = TipoGrupoPrincipal::findOrFail($id);

        $validated = $request->validate([
            'grupo_principal_nome' => 'required|string|max:255|unique:tipo_grupo_principal,grupo_principal_nome,' . $grupoPrincipal->id_grupo_principal . ',id_grupo_principal',
        ], [
            'grupo_principal_nome.unique' => 'Já existe um grupo principal com esse nome.',
        ]);

        $grupoPrincipal->update($validated);

        return redirect($this->urlIndex())
            ->with('success', 'Grupo principal atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $grupoPrincipal = TipoGrupoPrincipal::findOrFail($id);

        $grupoPrincipal->delete();

        return redirect()->route('tipo_grupo_principal.index')
            ->with('success', 'Grupo principal excluído com sucesso!');
    }
}
