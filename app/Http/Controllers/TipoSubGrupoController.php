<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\TipoGrupoPrincipal;
use App\Models\TipoSubGrupo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipoSubGrupoController extends Controller
{
    private const SESSION_KEY = 'tipo_sub_grupo.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('tipo_sub_grupo.index'));
    }

    public function index(Request $request)
    {
        $grupoPrincipalId = $request->filled('grupo_principal_id')
            ? CryptHelper::decrypt($request->query('grupo_principal_id'))
            : null;

        session([self::SESSION_KEY => $request->fullUrl()]);

        $subGrupos = TipoSubGrupo::with('grupoPrincipal')
            ->when($request->nome, function ($query) use ($request) {
                $query->where('sub_grupo_nome', 'like', '%' . $request->nome . '%');
            })
            ->when($grupoPrincipalId, function ($query) use ($grupoPrincipalId) {
                $query->where('grupo_principal_id', $grupoPrincipalId);
            })
            ->join(
                'tipo_grupo_principal',
                'tipo_sub_grupo.grupo_principal_id',
                '=',
                'tipo_grupo_principal.id_grupo_principal'
            )
            ->orderBy('tipo_grupo_principal.grupo_principal_nome', 'asc')
            ->orderBy('tipo_sub_grupo.sub_grupo_nome', 'asc')
            ->select('tipo_sub_grupo.*')
            ->paginate(10)
            ->withQueryString();

        $gruposPrincipais = TipoGrupoPrincipal::orderBy('grupo_principal_nome', 'asc')->get();

        return view('view_tipo_sub_grupo.index', compact(
            'subGrupos',
            'gruposPrincipais',
            'grupoPrincipalId'
        ));
    }

    public function create(Request $request)
    {
        $gruposPrincipais = TipoGrupoPrincipal::orderBy('grupo_principal_nome')->get();

        $grupoPrincipalId = $request->filled('grupo_principal_id')
            ? CryptHelper::decrypt($request->query('grupo_principal_id'))
            : null;

        return view('view_tipo_sub_grupo.create', [
            'gruposPrincipais' => $gruposPrincipais,
            'urlVoltar' => $this->urlIndex(),
            'grupoPrincipalId' => $grupoPrincipalId,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grupo_principal_id' => 'required|integer|exists:tipo_grupo_principal,id_grupo_principal',
            'sub_grupo_nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipo_sub_grupo', 'sub_grupo_nome')
                    ->where('grupo_principal_id', $request->input('grupo_principal_id')),
            ],
        ], [
            'sub_grupo_nome.unique' => 'Já existe um subgrupo com esse nome neste grupo principal.',
        ]);

        TipoSubGrupo::create($validated);

        return redirect($this->urlIndex())
            ->with('success', 'Subgrupo criado com sucesso!');
    }

    public function show(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $subGrupo = TipoSubGrupo::with(['grupoPrincipal', 'categorias'])->findOrFail($id);

        return view('view_tipo_sub_grupo.show', [
            'subGrupo' => $subGrupo,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $subGrupo = TipoSubGrupo::findOrFail($id);
        $gruposPrincipais = TipoGrupoPrincipal::orderBy('grupo_principal_nome')->get();

        return view('view_tipo_sub_grupo.edit', [
            'subGrupo' => $subGrupo,
            'gruposPrincipais' => $gruposPrincipais,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);

        $subGrupo = TipoSubGrupo::findOrFail($id);

        $validated = $request->validate([
            'grupo_principal_id' => 'required|integer|exists:tipo_grupo_principal,id_grupo_principal',
            'sub_grupo_nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipo_sub_grupo', 'sub_grupo_nome')
                    ->where('grupo_principal_id', $request->input('grupo_principal_id'))
                    ->ignore($subGrupo->id_sub_grupo, 'id_sub_grupo'),
            ],
        ], [
            'sub_grupo_nome.unique' => 'Já existe um subgrupo com esse nome neste grupo principal.',
        ]);

        $subGrupo->update($validated);

        return redirect($this->urlIndex())
            ->with('success', 'Subgrupo atualizado com sucesso!');
    }

    public function destroy(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $subGrupo = TipoSubGrupo::findOrFail($id);

        $subGrupo->delete();

        return redirect()->route('tipo_sub_grupo.index')
            ->with('success', 'Subgrupo excluído com sucesso!');
    }
}
