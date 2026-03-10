{{-- resources/views/view_tipo_pagamento/index.blade.php --}}
@extends('layouts.app_financeiro')

@section('title', 'Tipos de Pagamentos')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Tipos de Pagamentos Cadastrados
        </h1>
        <div class="flex items-center gap-3">

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                HOME
            </a>

            <a href="{{ route('tipo_pagamento.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Novo Pagamento
            </a>
        </div>

    </div>


    {{-- Alerta de sucesso --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Formulário de Busca --}}
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">

            {{-- Buscar por Tipo de Pagamento --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pesquisar Tipo de Pagamento
                </label>

                <div class="relative">
                    <input
                        type="text"
                        id="searchTipoPagInput"
                        placeholder="Tipo de pagamento..."
                        class="w-full h-10 pl-10 pr-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">

                    <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 text-gray-500"
                        fill="currentColor"
                        viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            {{-- Botão limpar --}}
            <div class="flex md:justify-end items-end">
                <button
                    type="button"
                    id="clearFiltersTipoPag"
                    class="inline-flex items-center px-4 py-2 h-10 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300">
                    Limpar Busca
                </button>
            </div>

        </div>
    </div>

    {{-- Sem tipos cadastrados --}}
    @if ($tiposPagamento->isEmpty())
    <p class="text-gray-600 text-center py-8" id="noTiposMessage">Nenhum tipo de pagamento cadastrado ainda.</p>
    @else
    {{-- Tabela --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Tipo de Plano</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="tipoPagTableBody">
                @foreach ($tiposPagamento as $tipo)
                <tr>
                    <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">
                        {{ $tipo->id_tipo_pagamento }}
                    </td>
                    <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">
                        {{ $tipo->tipo_plano_fin }}
                    </td>

                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            <a href="{{ route('tipo_pagamento.edit', $tipo->id_tipo_pagamento) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>
                            <form action="{{ route('tipo_pagamento.destroy', $tipo->id_tipo_pagamento) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir este tipo de pagamento?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-cancel-bg hover:bg-button-cancel-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-cancel-bg transition duration-150 ease-in-out">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="text-gray-600 text-center py-8 hidden" id="noResultsMessage">
            Nenhum tipo de pagamento encontrado com esse termo de busca.
        </p>
    </div>
    @endif
</div>

{{-- Script de busca dinâmica --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const searchInput = document.getElementById('searchTipoPagInput');
        const tableBody = document.getElementById('tipoPagTableBody');
        const noTiposMessage = document.getElementById('noTiposMessage');
        const noResultsMessage = document.getElementById('noResultsMessage');
        const clearBtn = document.getElementById('clearFiltersTipoPag');

        if (!tableBody) return;

        const rows = tableBody.querySelectorAll('tr');

        const filterTable = () => {

            const searchTerm = searchInput.value.toLowerCase();
            let foundResults = false;

            rows.forEach(row => {

                const nameCell = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                if (!searchTerm || nameCell.includes(searchTerm)) {
                    row.style.display = '';
                    foundResults = true;
                } else {
                    row.style.display = 'none';
                }

            });

            if (noResultsMessage) {
                noResultsMessage.classList.toggle('hidden', foundResults);
            }
        };

        function clearFilters() {
            searchInput.value = '';
            filterTable();
        }

        searchInput.addEventListener('input', filterTable);
        clearBtn.addEventListener('click', clearFilters);

    });
</script>
@endpush
@endsection