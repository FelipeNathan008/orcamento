@extends('layouts.app')

@section('title', 'Tipos de Fluxo de Caixa')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Tipos de Fluxo de Caixa / Nota Fiscal">
            <x-header-action href="{{ route('tipo_fluxo_caixa.create') }}">
                Novo Tipo
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('tipo_fluxo_caixa.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Nome</label>
                            <input type="text" id="nome" name="nome" value="{{ request('nome') }}" placeholder="Nome do tipo..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <div>
                            <label for="tipo_despesa" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Despesa</label>
                            <select id="tipo_despesa" name="tipo_despesa"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                <option value="Fixa" {{ request('tipo_despesa') == 'Fixa' ? 'selected' : '' }}>Fixa</option>
                                <option value="Variavel" {{ request('tipo_despesa') == 'Variavel' ? 'selected' : '' }}>Variável</option>
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
                        <a href="{{ route('tipo_fluxo_caixa.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de tipos</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $tiposFluxo->total() }} tipo(s)</span>
                </div>
            </div>

            @if ($tiposFluxo->isEmpty())
            @if (request('nome') || request('tipo_despesa'))
            <x-empty-state
                title="Nenhum tipo encontrado"
                message="Não existem tipos correspondentes aos filtros informados."
                route="tipo_fluxo_caixa.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhum tipo cadastrado"
                message="Ainda não existem tipos de fluxo de caixa cadastrados no sistema."
                route="tipo_fluxo_caixa.create"
                button-text="Cadastrar tipo" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Nome</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Despesa</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($tiposFluxo as $tipo)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5">
                                    <span class="text-sm font-semibold text-gray-800">{{ $tipo->tipo_flu_nome }}</span>
                                </td>
                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">{{ $tipo->tipo_despesa == 'Variavel' ? 'Variável' : $tipo->tipo_despesa }}</span>
                                </td>
                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :show-route="route('tipo_fluxo_caixa.show', CryptHelper::encrypt($tipo->id_tipo_fluxo))"
                                        :edit-route="route('tipo_fluxo_caixa.edit', CryptHelper::encrypt($tipo->id_tipo_fluxo))"
                                        :delete-action="route('tipo_fluxo_caixa.destroy', CryptHelper::encrypt($tipo->id_tipo_fluxo))"
                                        delete-id="formExcluirTipo{{ $tipo->id_tipo_fluxo }}"
                                        delete-modal="modalExcluirTipo" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-5">
                <x-pagination-compact :paginator="$tiposFluxo" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirTipo"
    titulo="Excluir tipo de fluxo de caixa"
    mensagem="Deseja realmente excluir este tipo de fluxo de caixa?"
    textoConfirmar="Excluir" />
@endsection
@push('scripts')
<script>
    (function () {
        const key = 'scroll:' + location.pathname + location.search;

        // Restaura a posição salva (se existir)
        const saved = sessionStorage.getItem(key);
        if (saved !== null) {
            window.scrollTo(0, parseInt(saved, 10));
            sessionStorage.removeItem(key);
        }

        // Salva a posição ao clicar em ações da tabela ou em "Novo Tipo"
        document.addEventListener('click', function (e) {
            if (e.target.closest('tbody a, tbody button, a[href$="/create"]')) {
                sessionStorage.setItem(key, window.scrollY);
            }
        });
    })();
</script>
@endpush