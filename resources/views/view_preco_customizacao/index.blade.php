@extends('layouts.app')

@section('title', 'Preços de Customização')

@section('content')

{{-- Div principal que centraliza o conteúdo e ajusta a largura máxima --}}
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho da Seção (Título e Botão) --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Preços de Customização Cadastrados
        </h1>
        <a href="{{ route('preco_customizacao.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
            style="background-color: #EA792D;">
            Novo Preço de Customização
        </a>

    </div>

    {{-- Mensagem de Sucesso (opcional) --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4"
        role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Textbox (Campo de busca) --}}
    <div class="relative w-full mb-6">
        <input type="text" id="searchPrecoCustomizacaoInput" placeholder="Pesquisar preços de customização..."
            class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none
                                         hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
        {{-- Ícone de pesquisa --}}
        <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20"
            fill="currentColor">
            <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd" />
        </svg>
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
                        ID Preço
                    </th>
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
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900 font-poppins">
                        {{ $preco->id_preco }}
                    </td>
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

{{-- Script JavaScript para a busca ao vivo --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchPrecoCustomizacaoInput');
        const precoTableBody = document.getElementById('precoCustomizacaoTableBody');
        const noPrecosMessage = document.getElementById('noPrecosCustomizacaoMessage');
        const noResultsMessage = document.getElementById('noResultsPrecoCustomizacaoMessage');

        // Verifica se a tabela de preços de customização está vazia desde o início
        if (precoTableBody.querySelectorAll('tr').length === 0) {
            if (noPrecosMessage) noPrecosMessage.classList.remove('hidden');
        } else {
            if (noPrecosMessage) noPrecosMessage.classList.add('hidden');
        }

        if (searchInput && precoTableBody) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = precoTableBody.querySelectorAll('tr');
                let foundResults = false;

                rows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();

                    if (rowText.includes(searchTerm)) {
                        row.style.display = '';
                        foundResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Lógica para exibir/ocultar mensagens de "nenhum resultado"
                if (searchTerm === '') {
                    // Se a busca está vazia, verifica se não há clientes na tabela original
                    if (precoTableBody.querySelectorAll('tr').length === 0) {
                        if (noPrecosMessage) noPrecosMessage.classList.remove('hidden');
                    } else {
                        if (noPrecosMessage) noPrecosMessage.classList.add('hidden');
                    }
                    if (noResultsMessage) noResultsMessage.classList.add('hidden');
                } else {
                    if (noPrecosMessage) noPrecosMessage.classList.add('hidden'); // Esconde a mensagem inicial
                    if (foundResults) {
                        if (noResultsMessage) noResultsMessage.classList.add('hidden');
                    } else {
                        if (noResultsMessage) noResultsMessage.classList.remove('hidden');
                    }
                }
            });
        }
    });
</script>
@endsection