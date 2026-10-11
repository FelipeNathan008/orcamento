<?php

namespace App\Http\Controllers;

use App\Helpers\CryptHelper;
use App\Models\TipoCategoria;
use App\Models\TipoSubCategoria;
use Illuminate\Http\Request;
use App\Models\TipoGrupoPrincipal;
use App\Models\TipoSubGrupo;
use Illuminate\Validation\Rule;

class TipoSubCategoriaController extends Controller
{
    private const SESSION_KEY = 'tipo_sub_categoria.index_url';

    private function urlIndex(): string
    {
        return session(self::SESSION_KEY, route('tipo_sub_categoria.index'));
    }

    public function index(Request $request)
    {
        session([self::SESSION_KEY => $request->fullUrl()]);

        $subCategorias = TipoSubCategoria::query()
            ->with('categoria.subGrupo.grupoPrincipal')
            ->join('tipo_categoria as categorias', 'tipo_sub_categoria.categoria_id', '=', 'categorias.id_categoria')
            ->join('tipo_sub_grupo as subgrupos', 'categorias.sub_grupo_id', '=', 'subgrupos.id_sub_grupo')
            ->join('tipo_grupo_principal as grupos', 'subgrupos.grupo_principal_id', '=', 'grupos.id_grupo_principal')
            ->when($request->filled('nome'), function ($query) use ($request) {
                $query->where('tipo_sub_categoria.sub_categoria_nome', 'like', '%' . $request->nome . '%');
            })
            ->when($request->filled('grupo_principal_id'), function ($query) use ($request) {
                $query->where('grupos.id_grupo_principal', $request->grupo_principal_id);
            })
            ->when($request->filled('sub_grupo_id'), function ($query) use ($request) {
                $query->where('subgrupos.id_sub_grupo', $request->sub_grupo_id);
            })
            ->when($request->filled('categoria_id'), function ($query) use ($request) {
                $query->where('categorias.id_categoria', $request->categoria_id);
            })
            ->select('tipo_sub_categoria.*')
            ->orderBy('grupos.grupo_principal_nome')
            ->orderBy('subgrupos.sub_grupo_nome')
            ->orderBy('categorias.categoria_nome')
            ->orderBy('tipo_sub_categoria.sub_categoria_nome')
            ->paginate(10)
            ->withQueryString();

        $gruposPrincipais = TipoGrupoPrincipal::orderBy('grupo_principal_nome')->get();

        $subGrupos = TipoSubGrupo::with('grupoPrincipal')
            ->orderBy('sub_grupo_nome')
            ->get();

        $categorias = TipoCategoria::with('subGrupo.grupoPrincipal')
            ->orderBy('categoria_nome')
            ->get();

        return view('view_tipo_sub_categoria.index', compact(
            'subCategorias',
            'gruposPrincipais',
            'subGrupos',
            'categorias'
        ));
    }


    public function create()
    {
        $gruposPrincipais = TipoGrupoPrincipal::orderBy('grupo_principal_nome')
            ->get();

        $subGrupos = TipoSubGrupo::with('grupoPrincipal')
            ->orderBy('sub_grupo_nome')
            ->get();

        $categorias = TipoCategoria::with('subGrupo.grupoPrincipal')
            ->orderBy('categoria_nome')
            ->get();

        return view('view_tipo_sub_categoria.create', [
            'gruposPrincipais' => $gruposPrincipais,
            'subGrupos' => $subGrupos,
            'categorias' => $categorias,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'categoria_id' => 'required|integer|exists:tipo_categoria,id_categoria',
            'sub_categoria_nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipo_sub_categoria', 'sub_categoria_nome')
                    ->where('categoria_id', $request->input('categoria_id')),
            ],
        ], [
            'sub_categoria_nome.unique' => 'Já existe uma subcategoria com esse nome nesta categoria.',
        ]);

        TipoSubCategoria::create($validated);

        return redirect($this->urlIndex())
            ->with('success', 'Subcategoria criada com sucesso!');
    }

    public function show(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $subCategoria = TipoSubCategoria::with('categoria.subGrupo.grupoPrincipal')
            ->findOrFail($id);

        return view('view_tipo_sub_categoria.show', [
            'subCategoria' => $subCategoria,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function edit(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $subCategoria = TipoSubCategoria::findOrFail($id);

        $categorias = TipoCategoria::with('subGrupo.grupoPrincipal')
            ->orderBy('categoria_nome')
            ->get();

        return view('view_tipo_sub_categoria.edit', [
            'subCategoria' => $subCategoria,
            'categorias' => $categorias,
            'urlVoltar' => $this->urlIndex(),
        ]);
    }

    public function update(Request $request, string $id)
    {
        $id = CryptHelper::decrypt($id);

        $subCategoria = TipoSubCategoria::findOrFail($id);

        $validated = $request->validate([
            'categoria_id' => 'required|integer|exists:tipo_categoria,id_categoria',
            'sub_categoria_nome' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tipo_sub_categoria', 'sub_categoria_nome')
                    ->where('categoria_id', $request->input('categoria_id'))
                    ->ignore($subCategoria->id_sub_categoria, 'id_sub_categoria'),
            ],
        ], [
            'sub_categoria_nome.unique' => 'Já existe uma subcategoria com esse nome nesta categoria.',
        ]);

        $subCategoria->update($validated);

        return redirect($this->urlIndex())
            ->with('success', 'Subcategoria atualizada com sucesso!');
    }

    public function destroy(string $id)
    {
        $id = CryptHelper::decrypt($id);

        $subCategoria = TipoSubCategoria::findOrFail($id);

        $subCategoria->delete();

        return redirect()->route('tipo_sub_categoria.index')
            ->with('success', 'Subcategoria excluída com sucesso!');
    }
}
