@extends('layouts.app')

@section('title', 'Lista de Clientes')

@section('content')

{{-- Div principal que centraliza o conteúdo e ajusta a largura máxima --}}
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho da Seção (Título e Botão) --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Prospecções Cadastradas
        </h1>
        <a href="{{ route('cliente.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
            style="background-color: #EA792D;">
            Nova Prospecção
        </a>

    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Formulário de Busca com campos separados --}}
    <form id="filtroClientesForm" action="{{ route('cliente.index') }}" method="GET" class="w-full mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 relative">
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

            {{-- Campo para pesquisa por Telefone --}}
            <div class="relative">
                <input type="text" name="search_telefone" id="searchPhoneInput"
                    placeholder="Pesquisar por Telefone..."
                    value="{{ request('search_telefone') }}"
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                {{-- Ícone de pesquisa --}}
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Campo para pesquisa por E-mail --}}
            <div class="relative">
                <input type="text" name="search_email" id="searchEmailInput"
                    placeholder="Pesquisar por E-mail..."
                    value="{{ request('search_email') }}"
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                {{-- Ícone de pesquisa --}}
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Botão Limpar Filtro - Visível apenas se houver termo de busca --}}
            @if(request('search_nome') || request('search_telefone') || request('search_email'))
            <a href="{{ route('cliente.index') }}"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 hover:text-gray-700 font-medium hidden md:block">
                Limpar
            </a>
            @endif
        </div>
    </form>

    @if ($clientes->isEmpty())
    <p class="text-gray-600 text-center py-8" id="noClientsMessage">Nenhum cliente cadastrado ainda.</p>
    @else
    {{-- TABELA DE CLIENTES --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Nome
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Celular
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        E-mail
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Documento
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="clientTableBody">
                @foreach ($clientes as $cliente)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-normal break-words text-sm font-medium text-gray-900 font-poppins">
                        {{ $cliente->clie_nome }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                        {{ $cliente->clie_celular }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                        {{ $cliente->clie_email }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                        @if ($cliente->clie_tipo_doc == 'CPF')
                        {{ $cliente->clie_cpf }}
                        @elseif ($cliente->clie_tipo_doc == 'CNPJ')
                        {{ $cliente->clie_cnpj }}
                        @else
                        {{ $cliente->clie_rg }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            {{-- Botão "Cliente Orçamento" - Direciona para a tela de clientes de orçamento --}}
                            <a href="{{ route('cliente_orcamento.create', ['cliente_id' => $cliente->id_cliente]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-budget-bg hover:bg-button-budget-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-budget-bg transition duration-150 ease-in-out">
                                Cliente Orçamento
                            </a>

                            {{-- Botão "Ver" --}}
                            <a href="{{ route('cliente.show', $cliente->id_cliente) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Ver
                            </a>

                            {{-- Botão "Editar" --}}
                            <a href="{{ route('cliente.edit', $cliente->id_cliente) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>

                            {{-- Botão "Excluir" --}}
                            <form action="{{ route('cliente.destroy', $cliente->id_cliente) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
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
        <p class="text-gray-600 text-center py-8 hidden" id="noResultsMessage">Nenhum cliente encontrado com esse termo de busca.</p>
    </div>
    @endif
</div>

{{-- Script JavaScript para a busca ao vivo --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchNameInput = document.getElementById('searchNameInput');
        const searchPhoneInput = document.getElementById('searchPhoneInput');
        const searchEmailInput = document.getElementById('searchEmailInput');
        const clientTableBody = document.getElementById('clientTableBody');
        const noClientsMessage = document.getElementById('noClientsMessage');
        const noResultsMessage = document.getElementById('noResultsMessage');

        // Verifica se a tabela de clientes está vazia desde o início
        if (clientTableBody && clientTableBody.querySelectorAll('tr').length === 0) {
            if (noClientsMessage) noClientsMessage.classList.remove('hidden');
        } else {
            if (noClientsMessage) noClientsMessage.classList.add('hidden');
        }

        if (searchNameInput && searchPhoneInput && searchEmailInput && clientTableBody) {
            // Função de filtragem genérica
            const filterTable = () => {
                const searchNameTerm = searchNameInput.value.toLowerCase();
                const searchPhoneTerm = searchPhoneInput.value.toLowerCase();
                const searchEmailTerm = searchEmailInput.value.toLowerCase();
                const rows = clientTableBody.querySelectorAll('tr');
                let foundResults = false;
                let hasSearchTerm = searchNameTerm !== '' || searchPhoneTerm !== '' || searchEmailTerm !== '';

                rows.forEach(row => {
                    // Obtém o texto de cada célula da linha para a busca
                    const nameCell = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                    const phoneCell = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    const emailCell = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                    // Condição de visibilidade: a linha deve corresponder a todos os campos preenchidos
                    const matchesName = searchNameTerm === '' || nameCell.includes(searchNameTerm);
                    const matchesPhone = searchPhoneTerm === '' || phoneCell.includes(searchPhoneTerm);
                    const matchesEmail = searchEmailTerm === '' || emailCell.includes(searchEmailTerm);

                    if (matchesName && matchesPhone && matchesEmail) {
                        row.style.display = '';
                        foundResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Lógica para exibir/ocultar mensagens
                if (!hasSearchTerm) {
                    if (clientTableBody.querySelectorAll('tr').length === 0) {
                        if (noClientsMessage) noClientsMessage.classList.remove('hidden');
                    } else {
                        if (noClientsMessage) noClientsMessage.classList.add('hidden');
                    }
                    if (noResultsMessage) noResultsMessage.classList.add('hidden');
                } else {
                    if (noClientsMessage) noClientsMessage.classList.add('hidden');
                    if (foundResults) {
                        if (noResultsMessage) noResultsMessage.classList.add('hidden');
                    } else {
                        if (noResultsMessage) noResultsMessage.classList.remove('hidden');
                    }
                }
            };

            // Adiciona o evento de input a todos os campos de busca
            searchNameInput.addEventListener('input', filterTable);
            searchPhoneInput.addEventListener('input', filterTable);
            searchEmailInput.addEventListener('input', filterTable);

            // Aplica as máscaras de input
            $(document).ready(function() {
                $('#searchPhoneInput').mask('(00) 00000-0000');
            });
        }
    });
</script>
@endsection