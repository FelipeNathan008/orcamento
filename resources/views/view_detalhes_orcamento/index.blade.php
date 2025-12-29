@extends('layouts.app')

@section('title', 'Lista de Detalhes de Orçamentos')

@section('content')
{{-- Div principal que centraliza o conteúdo e ajusta a largura máxima --}}
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho da Seção (Título e Botão) --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Detalhes de Orçamentos Cadastrados
        </h1>
        <a href="{{ route('detalhes_orcamento.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
            style="background-color: #EA792D;">
            Adicionar Detalhe
        </a>

    </div>
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Formulário de Busca com botão --}}
    <form action="{{ route('detalhes_orcamento.index') }}" method="GET" class="mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-4">
            <div class="relative w-full">
                <input type="text" name="search_orcamento_id" id="searchOrcamentoIdInput"
                    placeholder="Pesquisar por Orçamento Ref." value="{{ request('search_orcamento_id') }}"
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none
                                        hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="relative w-full">
                <input type="text" name="search_cliente_nome" id="searchClienteNomeInput"
                    placeholder="Pesquisar por Cliente" value="{{ request('search_cliente_nome') }}"
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none
                                        hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="relative w-full">
                <input type="text" name="search_detalhe_cod" id="searchDetalheCodInput"
                    placeholder="Pesquisar por Cód. Detalhe" value="{{ request('search_detalhe_cod') }}"
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none
                                        hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>
        </div>
        {{-- Botões de Ação --}}
        <div class="flex justify-end space-x-2">
            {{-- Botão "Buscar" --}}
            <button type="submit"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                Buscar
            </button>
            {{-- Botão "Limpar Filtros" --}}
            @if(request('search_orcamento_id') || request('search_cliente_nome') || request('search_detalhe_cod'))
            <a href="{{ route('detalhes_orcamento.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition duration-150 ease-in-out">
                Limpar Filtros
            </a>
            @endif
        </div>
    </form>

    @if ($detalhesOrcamento->isEmpty())
    <p class="text-gray-600 text-center py-8">Nenhum detalhe de orçamento encontrado.</p>
    @else
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Orçamento Ref.
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Cliente
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Produto
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Cód.
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Categoria
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Tam.
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-right text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Qtd.
                    </th>
                    <th scope="col"
                        class="px-4 py-3 text-right text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Valor Unit.
                    </th>
                    <th scope="col"
                        class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($detalhesOrcamento as $detalhe)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $detalhe->orcamento_id_orcamento }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $detalhe->orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $detalhe->produto->prod_nome ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $detalhe->det_cod }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $detalhe->det_categoria }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                        {{ $detalhe->det_tamanho ?? 'N/A' }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins text-right">
                        {{ $detalhe->det_quantidade }}
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins text-right">
                        R$ {{ number_format($detalhe->det_valor_unit, 2, ',', '.') }}
                    </td>
                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            {{-- Botão "Customizar" (NOVA COR) --}}
                            <a href="{{ route('customizacao.create', ['detalhe_id' => $detalhe->id_det]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-teal-500 hover:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 transition duration-150 ease-in-out">
                                Customizar
                            </a>

                            {{-- Botão "Customizações" com parâmetros adicionais --}}
                            <a href="{{ route('customizacao.index', [
                                'search_detalhes_id' => $detalhe->id_det,
                                'prod_nome_from_list' => $detalhe->produto?->prod_nome,
                                'prod_categoria_from_list' => $detalhe->det_categoria
                            ]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                Customizações
                            </a>

                            {{-- Botão "Ver" --}}
                            <a href="{{ route('detalhes_orcamento.show', $detalhe->id_det) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Ver
                            </a>

                            {{-- Botão "Editar" --}}
                            <a href="{{ route('detalhes_orcamento.edit', $detalhe->id_det) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>
                            {{-- Botão "Excluir" --}}
                            <form action="{{ route('detalhes_orcamento.destroy', $detalhe->id_det) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir este detalhe de orçamento?');">
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
    </div>
    @endif
</div>

{{-- Script JavaScript para a busca ao vivo --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchOrcamentoIdInput = document.getElementById('searchOrcamentoIdInput');
        const searchClienteNomeInput = document.getElementById('searchClienteNomeInput');
        const searchDetalheCodInput = document.getElementById('searchDetalheCodInput');
        const detalhesOrcamentoTableBody = document.getElementById('detalhesOrcamentoTableBody');
        const noDetalhesOrcamentoMessage = document.getElementById('noDetalhesOrcamentoMessage');
        const noResultsMessage = document.getElementById('noResultsDetalhesOrcamentoMessage');

        const allSearchInputs = [searchOrcamentoIdInput, searchClienteNomeInput, searchDetalheCodInput];

        // Função para verificar e exibir a mensagem inicial se a tabela estiver vazia
        function checkInitialMessage() {
            if (!detalhesOrcamentoTableBody) return; // 🔒 protege se não existir
            if (detalhesOrcamentoTableBody.querySelectorAll('tr').length === 0) {
                if (noDetalhesOrcamentoMessage) noDetalhesOrcamentoMessage.classList.remove('hidden');
            } else {
                if (noDetalhesOrcamentoMessage) noDetalhesOrcamentoMessage.classList.add('hidden');
            }
        }

        // Chama a função ao carregar a página
        checkInitialMessage();

        // Adiciona listeners para cada campo de busca
        allSearchInputs.forEach(input => {
            if (input) {
                input.addEventListener('input', function() {
                    const searchTermOrcamento = (searchOrcamentoIdInput ? searchOrcamentoIdInput.value.toLowerCase() : '');
                    const searchTermCliente = (searchClienteNomeInput ? searchClienteNomeInput.value.toLowerCase() : '');
                    const searchTermDetalheCod = (searchDetalheCodInput ? searchDetalheCodInput.value.toLowerCase() : '');

                    if (!detalhesOrcamentoTableBody) return; // 🔒 protege se não existir

                    const rows = detalhesOrcamentoTableBody.querySelectorAll('tr');
                    let foundResults = false;

                    rows.forEach(row => {
                        const orcamentoRefText = row.children[0] ? row.children[0].textContent.toLowerCase() : '';
                        const clienteText = row.children[1] ? row.children[1].textContent.toLowerCase() : '';
                        const codigoText = row.children[3] ? row.children[3].textContent.toLowerCase() : ''; // Cód. Detalhe

                        const matchesOrcamento = searchTermOrcamento === '' || orcamentoRefText.includes(searchTermOrcamento);
                        const matchesCliente = searchTermCliente === '' || clienteText.includes(searchTermCliente);
                        const matchesDetalheCod = searchTermDetalheCod === '' || codigoText.includes(searchTermDetalheCod);

                        if (matchesOrcamento && matchesCliente && matchesDetalheCod) {
                            row.style.display = '';
                            foundResults = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Lógica para exibir/ocultar mensagens
                    const anySearchTermPresent = searchTermOrcamento !== '' || searchTermCliente !== '' || searchTermDetalheCod !== '';

                    if (!anySearchTermPresent) {
                        checkInitialMessage(); // Volta a verificar a mensagem inicial se todos os campos de busca estiverem vazios
                        if (noResultsMessage) noResultsMessage.classList.add('hidden');
                    } else {
                        if (noDetalhesOrcamentoMessage) noDetalhesOrcamentoMessage.classList.add('hidden'); // Esconde a mensagem inicial
                        if (foundResults) {
                            if (noResultsMessage) noResultsMessage.classList.add('hidden');
                        } else {
                            if (noResultsMessage) noResultsMessage.classList.remove('hidden');
                        }
                    }
                });
            }
        });
    });
</script>

@endsection