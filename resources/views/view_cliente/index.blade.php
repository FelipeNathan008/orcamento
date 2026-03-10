@extends('layouts.app')

@section('title', 'Lista de Prospecções')

@section('content')

{{-- Div principal que centraliza o conteúdo e ajusta a largura máxima --}}
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho da Seção (Título e Botão) --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Prospecções Cadastrados
        </h1>
        <div class="flex items-center gap-3">

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                HOME
            </a>    

            <a href="{{ route('cliente.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Nova Prospecção
            </a>
        </div>

    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Formulário de Busca com campos separados --}}
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">

            {{-- Buscar por Nome --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pesquisar Nome
                </label>

                <div class="relative">
                    <input
                        type="text"
                        id="searchNameInput"
                        placeholder="Nome do cliente..."
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

            {{-- Buscar por Telefone --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pesquisar Telefone
                </label>

                <div class="relative">
                    <input
                        type="text"
                        id="searchPhoneInput"
                        placeholder="Telefone..."
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

            {{-- Buscar por Email --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pesquisar Email
                </label>

                <div class="relative">

                    <input
                        type="text"
                        id="searchEmailInput"
                        placeholder="Email..."
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
                    id="clearFiltersClients"
                    class="inline-flex items-center px-4 py-2 h-10 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300">
                    Limpar Busca
                </button>
            </div>
        </div>
    </div>

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
                        {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $cliente->clie_celular)) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                        {{ $cliente->clie_email }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                        {{-- CPF --}}
                        @if ($cliente->clie_tipo_doc === 'CPF' && $cliente->clie_cpf)
                        @php
                        $cpf = preg_replace('/\D/', '', $cliente->clie_cpf);
                        @endphp

                        {{ preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf) }}
                        @endif

                        {{-- CNPJ --}}
                        @if ($cliente->clie_tipo_doc === 'CNPJ' && $cliente->clie_cnpj)
                        @php
                        $cnpj = preg_replace('/\D/', '', $cliente->clie_cnpj);
                        @endphp

                        {{ preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj) }}
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
        const clearBtn = document.getElementById('clearFiltersClients');

        if (!clientTableBody) return;

        const rows = clientTableBody.querySelectorAll('tr');

        const filterTable = () => {

            const searchNameTerm = searchNameInput.value.toLowerCase();
            const searchPhoneTerm = searchPhoneInput.value.toLowerCase();
            const searchEmailTerm = searchEmailInput.value.toLowerCase();

            let foundResults = false;

            rows.forEach(row => {

                const nameCell = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const phoneCell = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const emailCell = row.querySelector('td:nth-child(3)').textContent.toLowerCase();

                const matchesName = !searchNameTerm || nameCell.includes(searchNameTerm);
                const matchesPhone = !searchPhoneTerm || phoneCell.includes(searchPhoneTerm);
                const matchesEmail = !searchEmailTerm || emailCell.includes(searchEmailTerm);

                if (matchesName && matchesPhone && matchesEmail) {
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
            searchNameInput.value = '';
            searchPhoneInput.value = '';
            searchEmailInput.value = '';
            filterTable();
        }

        searchNameInput.addEventListener('input', filterTable);
        searchPhoneInput.addEventListener('input', filterTable);
        searchEmailInput.addEventListener('input', filterTable);

        clearBtn.addEventListener('click', clearFilters);

        $('#searchPhoneInput').mask('(00) 00000-0000');

    });
</script>
@endsection