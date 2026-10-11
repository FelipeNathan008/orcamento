@extends('layouts.app')

@section('title', 'Categorias')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Classificação Financeira">
            <x-header-action href="{{ route('tipo_categoria.create', array_merge(request()->query(), ['page' => $categorias->currentPage()])) }}">
                Nova Categoria
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <div class="flex flex-wrap gap-2 mb-5">
                <a href="{{ route('tipo_grupo_principal.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-100 transition">
                    <x-icons.document />
                    Grupos principais
                </a>

                <a href="{{ route('tipo_sub_grupo.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-100 transition">
                    <x-icons.document />
                    Subgrupos
                </a>

                <a href="{{ route('tipo_categoria.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-50 text-orange-700 border border-orange-200">
                    <x-icons.document />
                    Categorias
                </a>

                <a href="{{ route('tipo_sub_categoria.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-100 transition">
                    Subcategorias
                </a>
            </div>

            <form method="GET" action="{{ route('tipo_categoria.index') }}">

                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Categoria
                            </label>

                            <input
                                type="text"
                                id="nome"
                                name="nome"
                                value="{{ request('nome') }}"
                                placeholder="Nome da categoria..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="grupo_principal_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Grupo principal
                            </label>

                            <select
                                id="grupo_principal_id"
                                name="grupo_principal_id"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos os grupos</option>
                                @foreach ($gruposPrincipais as $grupo)
                                <option
                                    value="{{ $grupo->id_grupo_principal }}"
                                    @selected((string) ($grupoPrincipalId ?? '' )===(string) $grupo->id_grupo_principal)>
                                    {{ $grupo->grupo_principal_nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="sub_grupo_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Subgrupo
                            </label>

                            <select
                                id="sub_grupo_id"
                                name="sub_grupo_id"
                                disabled
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                                <option value="">Selecione primeiro um grupo principal</option>
                                @foreach ($subGrupos as $subGrupo)
                                <option
                                    value="{{ $subGrupo->id_sub_grupo }}"
                                    data-grupo="{{ $subGrupo->grupo_principal_id }}"
                                    @selected((string) ($subGrupoId ?? '' )===(string) $subGrupo->id_sub_grupo)>
                                    {{ $subGrupo->sub_grupo_nome }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <x-primary-button class="w-full h-11">
                                <x-icons.search />
                                Buscar
                            </x-primary-button>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <a
                            href="{{ route('tipo_categoria.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>

            </form>

        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Categorias cadastradas</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Gerencie as categorias vinculadas aos subgrupos financeiros.
                    </p>
                </div>

                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $categorias->total() }} categoria(s)
                    </span>
                </div>
            </div>

            @if ($categorias->isEmpty())
            @if (request('nome') || request('sub_grupo_id'))
            <x-empty-state
                title="Nenhuma categoria encontrada"
                message="Não existem categorias correspondentes aos filtros informados."
                route="tipo_categoria.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhuma categoria cadastrada"
                message="Cadastre a primeira categoria para organizar sua classificação financeira."
                route="tipo_categoria.create"
                button-text="Cadastrar categoria" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Categoria
                                </th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Subgrupo
                                </th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Grupo principal
                                </th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($categorias as $categoria)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 text-orange-600">
                                            <x-icons.document class="w-5 h-5" />
                                        </div>

                                        <div>
                                            <span class="block text-sm font-semibold text-gray-800">
                                                {{ $categoria->categoria_nome }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">
                                        {{ $categoria->subGrupo->sub_grupo_nome ?? 'Subgrupo não encontrado' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-orange-100 border border-orange-200 text-orange-900 text-sm font-semibold">
                                        {{ $categoria->subGrupo->grupoPrincipal->grupo_principal_nome ?? 'Grupo não encontrado' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :show-route="route('tipo_sub_categoria.index', array_merge(request()->query(), [
                                            'categoria_id' => $categoria->id_categoria,
                                            'sub_grupo_id' => $categoria->sub_grupo_id,
                                            'grupo_principal_id' => $categoria->subGrupo->grupo_principal_id,
                                            'page' => 1
                                        ]))" :show-text="'Detalhes'" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$categorias" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirCategoria"
    titulo="Excluir categoria"
    mensagem="Deseja realmente excluir esta categoria? Confira se existem subcategorias vinculadas antes de confirmar."
    textoConfirmar="Excluir" />

@endsection

@push('scripts')
<script>
    (function() {
        const key = 'scroll:' + location.pathname + location.search;
        const saved = sessionStorage.getItem(key);

        if (saved !== null) {
            window.scrollTo(0, parseInt(saved, 10));
            sessionStorage.removeItem(key);
        }

        document.addEventListener('click', function(e) {
            if (e.target.closest('tbody a, tbody button, a[href*="/create"]')) {
                sessionStorage.setItem(key, window.scrollY);
            }
        });
    })();

    const grupoSelect = document.getElementById('grupo_principal_id');
    const subGrupoSelect = document.getElementById('sub_grupo_id');

    function atualizarSubGrupos() {
        const grupoSelecionado = grupoSelect.value;
        const subGrupoAnterior = subGrupoSelect.value;

        subGrupoSelect.disabled = !grupoSelecionado;

        Array.from(subGrupoSelect.options).forEach(function(option) {
            if (!option.value) {
                option.hidden = false;
                return;
            }

            const pertenceAoGrupo = option.dataset.grupo === grupoSelecionado;
            option.hidden = !pertenceAoGrupo;
        });

        if (!grupoSelecionado) {
            subGrupoSelect.value = '';
            subGrupoSelect.options[0].text = 'Selecione primeiro um grupo principal';
            return;
        }

        subGrupoSelect.options[0].text = 'Todos os subgrupos';

        const opcaoAnteriorValida = Array.from(subGrupoSelect.options).some(function(option) {
            return option.value === subGrupoAnterior &&
                option.dataset.grupo === grupoSelecionado;
        });

        if (opcaoAnteriorValida) {
            subGrupoSelect.value = subGrupoAnterior;
        } else {
            subGrupoSelect.value = '';
        }
    }

    grupoSelect.addEventListener('change', atualizarSubGrupos);
    atualizarSubGrupos();
</script>
@endpush