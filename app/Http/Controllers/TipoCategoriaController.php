<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\TipoCategoria;
use App\Models\TipoSubGrupo;
use App\Models\TipoGrupoPrincipal;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipoCategoriaController extends Controller
{
    private const SESSION_KEY = 'tipo_categoria.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('tipo_categoria.index'));
    }


    public function index(Request $request)
    {
        $grupoPrincipalId = $request->filled('grupo_principal_id')
            ? CryptHelper::decrypt($request->query('grupo_principal_id'))
            : null;

        $subGrupoId = $request->filled('sub_grupo_id')
            ? CryptHelper::decrypt($request->query('sub_grupo_id'))
            : null;

        session([self::SESSION_KEY => route('tipo_categoria.index', array_filter([
            'nome' => $request->query('nome'),
            'grupo_principal_id' => $request->query('grupo_principal_id'),
            'sub_grupo_id' => $request->query('sub_grupo_id'),
            'page' => $request->query('page'),
        ], fn($value) => $value !== null && $value !== ''))]);

        $categorias = TipoCategoria::with('subGrupo.grupoPrincipal')
            ->when($request->nome, function ($query) use ($request) {
                $query->where(
                    'tipo_categoria.categoria_nome',
                    'like',
                    '%' . $request->nome . '%'
                );
            })
            ->when($subGrupoId, function ($query) use ($subGrupoId) {
                $query->where('tipo_categoria.sub_grupo_id', $subGrupoId);
            })
            ->when($grupoPrincipalId, function ($query) use ($grupoPrincipalId) {
                $query->whereHas('subGrupo', function ($subQuery) use ($grupoPrincipalId) {
                    $subQuery->where('grupo_principal_id', $grupoPrincipalId);
                });
            })
            ->join(
                'tipo_sub_grupo',
                'tipo_categoria.sub_grupo_id',
                '=',
                'tipo_sub_grupo.id_sub_grupo'
            )
            ->join(
                'tipo_grupo_principal',
                'tipo_sub_grupo.grupo_principal_id',
                '=',
                'tipo_grupo_principal.id_grupo_principal'
            )
            ->orderBy('tipo_grupo_principal.grupo_principal_nome', 'asc')
            ->orderBy('tipo_sub_grupo.sub_grupo_nome', 'asc')
            ->orderBy('tipo_categoria.categoria_nome', 'asc')
            ->select('tipo_categoria.*')
            ->paginate(10)
            ->withQueryString();

        $gruposPrincipais = TipoGrupoPrincipal::orderBy('grupo_principal_nome', 'asc')->get();

        $subGrupos = TipoSubGrupo::with('grupoPrincipal')
            ->orderBy('sub_grupo_nome', 'asc')
            ->get();

        return view('view_tipo_categoria.index', compact(
            'categorias',
            'subGrupos',
            'gruposPrincipais',
            'grupoPrincipalId',
            'subGrupoId'
        ));
    }


    public function create(Request $request)
    {
        $grupoPrincipalId = $request->filled('grupo_principal_id')
            ? CryptHelper::decrypt($request->query('grupo_principal_id'))
            : null;

        $subGrupoId = $request->filled('sub_grupo_id')
            ? CryptHelper::decrypt($request->query('sub_grupo_id'))
            : null;

        $gruposPrincipais = TipoGrupoPrincipal::orderBy('grupo_principal_nome')
            ->get();

        $subGrupos = TipoSubGrupo::with('grupoPrincipal')
            ->orderBy('sub_grupo_nome')
            ->get();

        return view('view_tipo_categoria.create', [
            'gruposPrincipais' => $gruposPrincipais,
            'subGrupos' => $subGrupos,
            'grupoPrincipalId' => $grupoPrincipalId,
            'subGrupoId' => $subGrupoId,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'sub_grupo_id' => 'required|integer|exists:tipo_sub_grupo,id_sub_grupo',
            'categoria_nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipo_categoria', 'categoria_nome')
                    ->where('sub_grupo_id', $request->input('sub_grupo_id')),
            ],
        ], [
            'categoria_nome.unique' => 'Já existe uma categoria com esse nome neste subgrupo.',
        ]);

        TipoCategoria::create($validated);

        return redirect($this->urlIndex())
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function show(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $categoria = TipoCategoria::with(['subGrupo.grupoPrincipal', 'subCategorias'])
            ->findOrFail($id);

        return view('view_tipo_categoria.show', [
            'categoria' => $categoria,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $categoria = TipoCategoria::findOrFail($id);

        $subGrupos = TipoSubGrupo::with('grupoPrincipal')
            ->orderBy('sub_grupo_nome')
            ->get();

        return view('view_tipo_categoria.edit', [
            'categoria' => $categoria,
            'subGrupos' => $subGrupos,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);

        $categoria = TipoCategoria::findOrFail($id);

        $validated = $request->validate([
            'sub_grupo_id' => 'required|integer|exists:tipo_sub_grupo,id_sub_grupo',
            'categoria_nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipo_categoria', 'categoria_nome')
                    ->where('sub_grupo_id', $request->input('sub_grupo_id'))
                    ->ignore($categoria->id_categoria, 'id_categoria'),
            ],
        ], [
            'categoria_nome.unique' => 'Já existe uma categoria com esse nome neste subgrupo.',
        ]);

        $categoria->update($validated);

        return redirect($this->urlIndex())
            ->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $categoria = TipoCategoria::findOrFail($id);

        $categoria->delete();

        return redirect()->route('tipo_categoria.index')
            ->with('success', 'Categoria excluída com sucesso!');
    }
}
