@extends('layouts.app')

@section('title', 'Preços de Customização')

@section('content')

{{-- Div principal que centraliza o conteúdo e ajusta a largura máxima --}}
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho da Seção (Título e Botão) --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Preços Customizações Cadastrados
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                HOME
            </a>

            <a href="{{ route('preco_customizacao.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Novo Preço
            </a>

        </div>

    </div>

    {{-- Mensagem de Sucesso (opcional) --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4"
        role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pesquisar Customização
                </label>

                <div class="relative">

                    <input
                        type="text"
                        id="searchPrecoInput"
                        placeholder="Tipo ou tamanho..."
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

            <div class="flex md:justify-end items-end">

                <button
                    type="button"
                    id="clearFiltersPreco"
                    class="inline-flex items-center px-4 py-2 h-10 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300">

                    Limpar Busca

                </button>

            </div>

        </div>

    </div>

    @if ($precosCustomizacao->isEmpty())
    <p class="text-gray-600 text-center py-8" id="noPrecosCustomizacaoMessage">Nenhum preço de customização
        cadastrado ainda.</p>
    @else
    {{-- TABELA DE PREÇOS DE CUSTOMIZAÇÃO --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>

                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Tipo
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Tamanho
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Valor
                    </th>
                    <th scope="col"
                        class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="precoCustomizacaoTableBody">
                @foreach ($precosCustomizacao as $preco)
                <tr
                    class="hover:bg-gray-50 transition duration-150"
                    data-preco-tipo="{{ $preco->preco_tipo }}"
                    data-preco-tamanho="{{ $preco->preco_tamanho }}">
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $preco->preco_tipo }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                        {{ $preco->preco_tamanho }}
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                        R$ {{ number_format($preco->preco_valor, 2, ',', '.') }}
                    </td>
                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            {{-- Botão "Editar" --}}
                            <a href="{{ route('preco_customizacao.edit', $preco->id_preco) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>
                            {{-- Botão "Excluir" --}}
                            <form action="{{ route('preco_customizacao.destroy', $preco->id_preco) }}"
                                method="POST" class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir este preço de customização?');">
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
        <p class="text-gray-600 text-center py-8 hidden" id="noResultsPrecoCustomizacaoMessage">Nenhum preço de
            customização encontrado com esse termo
            de busca.</p>
    </div>
    @endif
</div>

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const searchInput = document.getElementById('searchPrecoInput');
        const clearBtn = document.getElementById('clearFiltersPreco');

        const tableBody = document.getElementById('precoCustomizacaoTableBody');
        const noResultsMessage = document.getElementById('noResultsPrecoCustomizacaoMessage');

        const allRows = tableBody ? Array.from(tableBody.querySelectorAll('tr')) : [];

        function filterPrecos() {

            const searchTerm = searchInput.value.toLowerCase();
            let foundResults = false;

            allRows.forEach(row => {
                const tipo = row.dataset.precoTipo.toLowerCase();
                const tamanho = row.dataset.precoTamanho.toLowerCase();
                const matchesSearch =
                    tipo.includes(searchTerm) ||
                    tamanho.includes(searchTerm);
                if (matchesSearch) {
                    row.style.display = '';
                    foundResults = true;
                } else {
                    row.style.display = 'none';
                }
            });
            noResultsMessage.classList.toggle('hidden', foundResults);
        }

        function clearFilters() {
            searchInput.value = '';
            filterPrecos();
        }
        searchInput.addEventListener('input', filterPrecos);
        clearBtn.addEventListener('click', clearFilters);

    });
</script>

@endpush
@endsection