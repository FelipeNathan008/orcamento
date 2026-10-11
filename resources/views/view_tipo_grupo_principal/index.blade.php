@extends('layouts.app')

@section('title', 'Grupos Principais')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Classificação Financeira">
            <x-header-action href="{{ route('tipo_grupo_principal.create', array_merge(request()->query(), ['page' => $gruposPrincipais->currentPage()])) }}">
                Novo Grupo Principal
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <div class="flex flex-wrap gap-2 mb-5">
                <a
                    href="{{ route('tipo_grupo_principal.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold bg-orange-50 text-orange-700 border border-orange-200">
                    <x-icons.document />
                    Grupos principais
                </a>

                <a href="{{ route('tipo_sub_grupo.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-100 transition">
                    Subgrupos
                </a>

                <a href="{{ route('tipo_categoria.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-100 transition">
                    Categorias
                </a>

                <a href="{{ route('tipo_sub_categoria.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium text-gray-600 border border-gray-200 hover:bg-gray-100 transition">
                    Subcategorias
                </a>
            </div>

            <form method="GET" action="{{ route('tipo_grupo_principal.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Nome do grupo
                            </label>

                            <input
                                type="text"
                                id="nome"
                                name="nome"
                                value="{{ request('nome') }}"
                                placeholder="Nome do grupo principal..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
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
                            href="{{ route('tipo_grupo_principal.index') }}"
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
                    <h2 class="text-lg font-bold text-gray-800">Grupos principais cadastrados</h2>
                    <p class="text-sm text-gray-500 mt-1">
                        Gerencie os grupos que organizam a classificação financeira.
                    </p>
                </div>

                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $gruposPrincipais->total() }} grupo(s)
                    </span>
                </div>
            </div>

            @if ($gruposPrincipais->isEmpty())
            @if (request('nome'))
            <x-empty-state
                title="Nenhum grupo encontrado"
                message="Não existem grupos principais correspondentes ao nome informado."
                route="tipo_grupo_principal.index"
                button-text="Limpar filtro" />
            @else
            <x-empty-state
                title="Nenhum grupo cadastrado"
                message="Cadastre o primeiro grupo principal para começar a organizar sua classificação financeira."
                route="tipo_grupo_principal.create"
                button-text="Cadastrar grupo" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Grupo principal
                                </th>

                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($gruposPrincipais as $grupo)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-orange-50 border border-orange-100 text-orange-600">
                                            <x-icons.document class="w-5 h-5" />
                                        </div>

                                        <div>
                                            <span class="block text-sm font-semibold text-gray-800">
                                                {{ $grupo->grupo_principal_nome }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :show-route="route('tipo_sub_grupo.index', ['grupo_principal_id' => CryptHelper::encrypt($grupo->id_grupo_principal)])"
                                        :show-text="'Detalhes'"
                                        :sub-grupo-route="route('tipo_sub_grupo.create', ['grupo_principal_id' => CryptHelper::encrypt($grupo->id_grupo_principal)])"
                                        :sub-grupo-text="'Cadastrar Subgrupo'" />
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$gruposPrincipais" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirGrupoPrincipal"
    titulo="Excluir grupo principal"
    mensagem="Deseja realmente excluir este grupo principal? Confira se existem subgrupos vinculados antes de confirmar."
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
</script>
@endpush