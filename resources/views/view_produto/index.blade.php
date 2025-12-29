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
            <a href="{{ route('produto.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Adicionar Novo Produto
            </a>
        </div>

        {{-- Alerta de sucesso --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
                <strong class="font-bold">Sucesso!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Campos de busca e filtro --}}
        <div class="flex flex-col sm:flex-row gap-4 mb-6">
            {{-- Campo de busca por nome/código --}}
            <div class="relative w-full sm:w-1/3">
                <input type="text" id="searchProductInput" placeholder="Pesquisar por nome ou código..."
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Filtro de Família --}}
            <div class="w-full sm:w-1/3">
                <select id="familiaFilter"
                    class="w-full h-9 px-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                    <option value="">Filtrar por Família</option>
                    @foreach ($familias as $familia)
                        <option value="{{ $familia }}">{{ $familia }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filtro de Categoria (depende da Família) --}}
            <div class="w-full sm:w-1/3">
                <select id="categoriaFilter"
                    class="w-full h-9 px-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text"
                    disabled>
                    <option value="">Filtrar por Categoria</option>
                </select>
            </div>
        </div>

        {{-- Sem produtos --}}
        @if ($produtos->isEmpty())
            <p class="text-gray-600 text-center py-8" id="noProductsMessage">Nenhum produto cadastrado ainda.</p>
        @else
            {{-- Tabela --}}
            <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
                <table class="min-w-full w-full divide-y divide-gray-200">
                    <thead class="bg-table-header-bg">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                                ID</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                                Código</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                                Nome</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                                Família</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                                Categoria</th>
                            {{-- ADICIONADO: Novo cabeçalho para 'Cor' --}}
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                                Cor</th>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                                Preço</th>
                            <th
                                class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                                Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="productTableBody">
                        @foreach ($produtos as $produto)
                            {{-- ADICIONEI OS ATRIBUTOS 'data-' PARA O JAVASCRIPT FILTRAR --}}
                            <tr data-prod-familia="{{ $produto->prod_familia }}"
                                data-prod-categoria="{{ $produto->prod_categoria }}" data-prod-nome="{{ $produto->prod_nome }}"
                                data-prod-cod="{{ $produto->prod_cod }}">
                                <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">
                                    {{ $produto->id_produto }}
                                </td>
                                <td class="px-4 py-4 break-words whitespace-normal text-sm text-gray-700 font-poppins">
                                    {{ $produto->prod_cod }}
                                </td>
                                <td class="px-4 py-4 break-words whitespace-normal text-sm text-gray-700 font-poppins">
                                    {{ $produto->prod_nome }}
                                </td>
                                <td class="px-4 py-4 break-words whitespace-normal text-sm text-gray-700 font-poppins">
                                    {{ $produto->prod_familia }}
                                </td>
                                <td class="px-4 py-4 break-words whitespace-normal text-sm text-gray-700 font-poppins">
                                    {{ $produto->prod_categoria }}
                                </td>
                                {{-- ADICIONADO: Nova célula para a cor --}}
                                <td class="px-4 py-4 break-words whitespace-normal text-sm text-gray-700 font-poppins">
                                    {{ $produto->prod_cor }}
                                </td>
                                <td class="px-4 py-4 break-words whitespace-normal text-sm text-gray-700 font-poppins">
                                    R$ {{ number_format($produto->prod_preco, 2, ',', '.') }}
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                                        {{-- Botão Ver --}}
                                        <a href="{{ route('produto.show', $produto->id_produto) }}"
                                            class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                            Ver
                                        </a>
                                        <a href="{{ route('produto.edit', $produto->id_produto) }}"
                                            class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                            Editar
                                        </a>
                                        <form action="{{ route('produto.destroy', $produto->id_produto) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Tem certeza que deseja excluir este produto?');">
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
                    Nenhum produto encontrado com esse termo de busca.
                </p>
            </div>
        @endif
    </div>

    {{-- Script de busca dinâmica --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('searchProductInput');
                const familiaFilter = document.getElementById('familiaFilter');
                const categoriaFilter = document.getElementById('categoriaFilter');
                const productTableBody = document.getElementById('productTableBody');
                const noProductsMessage = document.getElementById('noProductsMessage');
                const noResultsMessage = document.getElementById('noResultsMessage');

                const allRows = Array.from(productTableBody.querySelectorAll('tr'));

                // Mapeia as categorias por família
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
                        'Jaqueta tactel com forro tactel', 'Jaqueta tactel com forro matelassê', 'Blusa Helanca Flanelada', 'Blusa de Lã'
                    ],
                    // Adicione outras famílias e categorias aqui, se necessário
                };

                // Função que filtra a tabela com base em todos os filtros
                function filterProducts() {
                    const searchTerm = searchInput.value.toLowerCase();
                    const selectedFamilia = familiaFilter.value;
                    const selectedCategoria = categoriaFilter.value;

                    let foundResults = false;

                    allRows.forEach(row => {
                        const rowFamilia = row.dataset.prodFamilia;
                        const rowCategoria = row.dataset.prodCategoria;
                        const rowName = row.dataset.prodNome.toLowerCase();
                        const rowCod = row.dataset.prodCod.toLowerCase();

                        const matchesSearch = rowName.includes(searchTerm) || rowCod.includes(searchTerm);
                        const matchesFamilia = !selectedFamilia || rowFamilia === selectedFamilia;
                        const matchesCategoria = !selectedCategoria || rowCategoria === selectedCategoria;

                        if (matchesSearch && matchesFamilia && matchesCategoria) {
                            row.style.display = '';
                            foundResults = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Atualiza as mensagens de "nenhum resultado"
                    if (allRows.length === 0) {
                        noProductsMessage?.classList.remove('hidden');
                        noResultsMessage?.classList.add('hidden');
                    } else if (foundResults) {
                        noProductsMessage?.classList.add('hidden');
                        noResultsMessage?.classList.add('hidden');
                    } else {
                        noProductsMessage?.classList.add('hidden');
                        noResultsMessage?.classList.remove('hidden');
                    }
                }

                // Função para atualizar as opções da categoria com base na família
                function updateCategoriaOptions() {
                    const selectedFamilia = familiaFilter.value;
                    let optionsHtml = '<option value="">Filtrar por Categoria</option>';

                    if (selectedFamilia in categoriesByFamily) {
                        categoriesByFamily[selectedFamilia].forEach(cat => {
                            optionsHtml += `<option value="${cat}">${cat}</option>`;
                        });
                        categoriaFilter.disabled = false;
                    } else {
                        categoriaFilter.disabled = true;
                    }

                    categoriaFilter.innerHTML = optionsHtml;
                    filterProducts(); // Chama o filtro após atualizar as opções
                }

                // Adiciona os event listeners para os filtros
                searchInput.addEventListener('input', filterProducts);
                familiaFilter.addEventListener('change', updateCategoriaOptions);
                categoriaFilter.addEventListener('change', filterProducts);
            });
        </script>
    @endpush
@endsection