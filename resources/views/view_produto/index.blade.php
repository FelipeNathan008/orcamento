@extends('layouts.app')

@section('title', 'Lista de Produtos')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Produtos Cadastrados
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                HOME
            </a>

            <a href="{{ route('produto.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Novo Produto
            </a>

        </div>

    </div>


    {{-- Alerta de sucesso --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-6">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif


    {{-- Área de filtros --}}
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
            {{-- Campo busca --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pesquisar Produto
                </label>

                <div class="relative">

                    <input type="text"
                        id="searchProductInput"
                        placeholder="Nome ou código..."
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


            {{-- Filtro Família --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Filtrar por Família
                </label>

                <select id="familiaFilter"
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">

                    <option value="">Mostrar todas</option>

                    @foreach ($familias as $familia)
                    <option value="{{ $familia }}">
                        {{ $familia }}
                    </option>
                    @endforeach

                </select>
            </div>


            {{-- Filtro Categoria --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Filtrar por Categoria
                </label>

                <select id="categoriaFilter"
                    disabled
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">

                    <option value="">Mostrar todas</option>

                </select>
            </div>
            {{-- Botão limpar filtros --}}
            <div class="flex md:justify-end items-end">

                <button
                    type="button"
                    id="clearFilters"
                    class="inline-flex items-center px-4 py-2 h-10 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition duration-150 ease-in-out">

                    Limpar Filtros

                </button>

            </div>
        </div>

    </div>


    {{-- Mensagem nenhum produto --}}
    @if ($produtos->isEmpty())
    <p class="text-gray-600 text-center py-8" id="noProductsMessage">
        Nenhum produto cadastrado ainda.
    </p>
    @else

    {{-- Tabela --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">

        <table class="min-w-full w-full divide-y divide-gray-200">

            <thead class="bg-table-header-bg">
                <tr>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        ID
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Código
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Nome
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Família
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Categoria
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Cor
                    </th>

                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Preço
                    </th>

                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                        Ações
                    </th>

                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="productTableBody">

                @foreach ($produtos as $produto)

                <tr
                    data-prod-familia="{{ $produto->prod_familia }}"
                    data-prod-categoria="{{ $produto->prod_categoria }}"
                    data-prod-nome="{{ $produto->prod_nome }}"
                    data-prod-cod="{{ $produto->prod_cod }}">

                    <td class="px-4 py-4 text-sm font-medium text-gray-900">
                        {{ $produto->id_produto }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $produto->prod_cod }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $produto->prod_nome }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $produto->prod_familia }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $produto->prod_categoria }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        {{ $produto->prod_cor }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700">
                        R$ {{ number_format($produto->prod_preco, 2, ',', '.') }}
                    </td>

                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">

                        <div class="flex items-center justify-center space-x-2">

                            <a href="{{ route('produto.show', $produto->id_produto) }}"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Ver
                            </a>

                            <a href="{{ route('produto.edit', $produto->id_produto) }}"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover">
                                Editar
                            </a>

                            <form action="{{ route('produto.destroy', $produto->id_produto) }}"
                                method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">

                                @csrf
                                @method('DELETE')

                                <button
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-button-cancel-bg hover:bg-button-cancel-hover">
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
            Nenhum produto encontrado com esse termo de busca.
        </p>

    </div>

    @endif

</div>

{{-- Script de filtros --}}
@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const searchInput = document.getElementById('searchProductInput');
        const familiaFilter = document.getElementById('familiaFilter');
        const categoriaFilter = document.getElementById('categoriaFilter');
        const clearFiltersBtn = document.getElementById('clearFilters');

        const productTableBody = document.getElementById('productTableBody');
        const noResultsMessage = document.getElementById('noResultsMessage');

        const allRows = Array.from(productTableBody.querySelectorAll('tr'));

        const categoriesByFamily = {

            'Linha Social': [
                'Camisetes', 'Camisas', 'Camisetes com Detalhes', 'Blazer', 'Vestido Tubinho', 'Calça Social'
            ],

            'Linha Operacional': [
                'Camiseta Manga Curta', 'Camiseta Baby Look', 'Camiseta Manga Longa', 'Camiseta Polo',
                'Jaleco', 'Jaleco de Brim', 'Calças de Brim', 'Calças Jeans', 'Calça Bailarina', 'Calça Sarja'
            ],

            'Linha Gastronômica': [
                'Avental Peito', 'Meio Avental', 'Avental Gourmet', 'Touca Sushiman', 'Touca Telinha', 'Dolmã'
            ],

            'Linha Inverno': [
                'Jaqueta tactel com forro tactel',
                'Jaqueta tactel com forro matelassê',
                'Blusa Helanca Flanelada',
                'Blusa de Lã'
            ]

        };


        function filterProducts() {

            const searchTerm = searchInput.value.toLowerCase();
            const selectedFamilia = familiaFilter.value;
            const selectedCategoria = categoriaFilter.value;

            let foundResults = false;

            allRows.forEach(row => {

                const rowFamilia = row.dataset.prodFamilia;
                const rowCategoria = row.dataset.prodCategoria;
                const rowNome = row.dataset.prodNome.toLowerCase();
                const rowCod = row.dataset.prodCod.toLowerCase();

                const matchesSearch = rowNome.includes(searchTerm) || rowCod.includes(searchTerm);
                const matchesFamilia = !selectedFamilia || rowFamilia === selectedFamilia;
                const matchesCategoria = !selectedCategoria || rowCategoria === selectedCategoria;

                if (matchesSearch && matchesFamilia && matchesCategoria) {

                    row.style.display = '';
                    foundResults = true;
                } else {
                    row.style.display = 'none';
                }

            });

            noResultsMessage.classList.toggle('hidden', foundResults);

        }


        function updateCategoriaOptions() {
            const selectedFamilia = familiaFilter.value;
            let options = '<option value="">Mostrar todas</option>';
            if (categoriesByFamily[selectedFamilia]) {

                categoriesByFamily[selectedFamilia].forEach(cat => {
                    options += `<option value="${cat}">${cat}</option>`;
                });
                categoriaFilter.disabled = false;
            } else {
                categoriaFilter.disabled = true;
            }
            categoriaFilter.innerHTML = options;

            filterProducts();

        }

        function clearFilters() {

            searchInput.value = '';
            familiaFilter.value = '';
            categoriaFilter.value = '';
            categoriaFilter.disabled = true;

            categoriaFilter.innerHTML = '<option value="">Mostrar todas</option>';

            filterProducts();

        }
        searchInput.addEventListener('input', filterProducts);
        familiaFilter.addEventListener('change', updateCategoriaOptions);
        categoriaFilter.addEventListener('change', filterProducts);
        clearFiltersBtn.addEventListener('click', clearFilters);

    });
</script>
@endpush
@endsection