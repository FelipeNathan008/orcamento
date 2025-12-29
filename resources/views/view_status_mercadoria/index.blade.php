@extends('layouts.app')

@section('title', 'Lista de Status de Mercadoria')

@section('content')

{{-- Div principal que centraliza o conteúdo e ajusta a largura máxima --}}
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho da Seção (Título e Botão) --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Status de Mercadoria Cadastrados
        </h1>
        <a href="{{ route('status_mercadoria.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
            style="background-color: #EA792D;">
            Novo Status
        </a>

    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Formulário de Busca --}}
    <form id="filtroStatusForm" action="{{ route('status_mercadoria.index') }}" method="GET" class="w-full mb-6">
        <div class="grid grid-cols-1 md:grid-cols-1 gap-4 relative">
            {{-- Campo para pesquisa por Nome --}}
            <div class="relative">
                <input type="text" name="search_nome" id="searchNameInput"
                    placeholder="Pesquisar por Nome..."
                    value="{{ request('search_nome') }}"
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                {{-- Ícone de pesquisa --}}
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Botão Limpar Filtro --}}
            @if(request('search_nome'))
            <a href="{{ route('status_mercadoria.index') }}"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 hover:text-gray-700 font-medium hidden md:block">
                Limpar
            </a>
            @endif
        </div>
    </form>

    @if ($statusMercadorias->isEmpty())
    <p class="text-gray-600 text-center py-8" id="noStatusMessage">Nenhum status de mercadoria cadastrado ainda.</p>
    @else
    {{-- TABELA DE STATUS DE MERCADORIA --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Nome do Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="statusTableBody">
                @foreach ($statusMercadorias as $status)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-normal break-words text-sm font-medium text-gray-900 font-poppins">
                        {{ $status->status_merc_nome }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            {{-- Botão "Ver" --}}
                            <a href="{{ route('status_mercadoria.show', $status->id_status_merc) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Ver
                            </a>

                            {{-- Botão "Editar" --}}
                            <a href="{{ route('status_mercadoria.edit', $status->id_status_merc) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>

                            {{-- Botão "Excluir" --}}
                            <form action="{{ route('status_mercadoria.destroy', $status->id_status_merc) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir este status de mercadoria?');">
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
        {{-- Mensagem para quando não houver resultados da busca --}}
        <p class="text-gray-600 text-center py-8 hidden" id="noResultsMessage">Nenhum status encontrado com esse termo de busca.</p>
    </div>
    @endif
</div>

{{-- Script JavaScript para a busca ao vivo --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchNameInput = document.getElementById('searchNameInput');
        const statusTableBody = document.getElementById('statusTableBody');
        const noStatusMessage = document.getElementById('noStatusMessage');
        const noResultsMessage = document.getElementById('noResultsMessage');

        // Verifica se a tabela de status está vazia desde o início
        if (statusTableBody && statusTableBody.querySelectorAll('tr').length === 0) {
            if (noStatusMessage) noStatusMessage.classList.remove('hidden');
        } else {
            if (noStatusMessage) noStatusMessage.classList.add('hidden');
        }

        if (searchNameInput && statusTableBody) {
            const filterTable = () => {
                const searchNameTerm = searchNameInput.value.toLowerCase();
                const rows = statusTableBody.querySelectorAll('tr');
                let foundResults = false;
                let hasSearchTerm = searchNameTerm !== '';

                rows.forEach(row => {
                    const nameCell = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const matchesName = searchNameTerm === '' || nameCell.includes(searchNameTerm);

                    if (matchesName) {
                        row.style.display = '';
                        foundResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (!hasSearchTerm) {
                    if (statusTableBody.querySelectorAll('tr').length === 0) {
                        if (noStatusMessage) noStatusMessage.classList.remove('hidden');
                    } else {
                        if (noStatusMessage) noStatusMessage.classList.add('hidden');
                    }
                    if (noResultsMessage) noResultsMessage.classList.add('hidden');
                } else {
                    if (noStatusMessage) noStatusMessage.classList.add('hidden');
                    if (foundResults) {
                        if (noResultsMessage) noResultsMessage.classList.add('hidden');
                    } else {
                        if (noResultsMessage) noResultsMessage.classList.remove('hidden');
                    }
                }
            };

            searchNameInput.addEventListener('input', filterTable);
        }
    });
</script>
@endsection