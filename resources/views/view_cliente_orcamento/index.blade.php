@extends('layouts.app') {{-- Assumindo que você tem um layout principal chamado 'app' --}}

@section('title', 'Lista de Clientes de Orçamento') {{-- Título da página --}}

@section('content')

{{-- Div principal que centraliza o conteúdo e ajusta a largura máxima --}}
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho da Seção (Título e Botão) - Estilo do Cliente --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Clientes Cadastrados
        </h1>
        <a href="{{ route('cliente_orcamento.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
            style="background-color: #EA792D;">
            Novo Cliente
        </a>


    </div>

    @if (session('success'))
    {{-- Estilo da mensagem de sucesso do Cliente --}}
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Campos de busca - ATUALIZADO para 3 campos --}}
    <form method="GET" action="{{ route('cliente_orcamento.index') }}">

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            {{-- Campo de busca por Nome --}}
            <div class="relative">
                <input type="text" id="searchClientOrcamentoName" placeholder="Pesquisar por nome..."
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none
                                               hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Campo de busca por E-mail --}}
            <div class="relative">
                <input type="text" id="searchClientOrcamentoEmail" placeholder="Pesquisar por e-mail..."
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none
                                               hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Campo de busca por Celular --}}
            <div class="relative">
                <input type="text" id="searchClientOrcamentoCode" placeholder="Pesquisar por código interno..."
                    class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none
                                               hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                        clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Campo de busca por ID do Orçamento --}}
            <div class="relative">
                <input type="number" name="id_orcamento" value="{{ request('id_orcamento') }}"
                    placeholder="Pesquisar por ID do orçamento..."
                    class="w-full h-9 pl-3 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none
        hover:border-custom-border-hover focus:border-custom-border-focus text-custom-dark-text">
            </div>

        </div>
        <button type="submit"
            class="mt-4 px-4 py-2 text-white rounded-md"
            style="background-color: #EA792D;">
            Filtrar
        </button>
    </form>

    @if ($clientesOrcamento->isEmpty())
    {{-- Estilo da mensagem de "nenhum cliente" do Cliente --}}
    <p class="text-gray-600 text-center py-8" id="noClientsOrcamentoMessage">Nenhum cliente de orçamento cadastrado
        ainda.</p>
    @else
    {{-- TABELA DE CLIENTES DE ORÇAMENTO - Estilo do Cliente --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Cód.
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Nome
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Celular
                    </th>
                    <th class="w-32 px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        E-mail
                    </th>
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="clientOrcamentoTableBody">
                @foreach ($clientesOrcamento as $cliente)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-normal break-words text-sm font-medium text-gray-900 font-poppins">
                        {{ $cliente->clie_orc_cod_interno }}
                    </td>
                    <td class="px-6 py-4 whitespace-normal break-words text-sm font-medium text-gray-900 font-poppins max-w-[220px]">
                        {{ $cliente->clie_orc_nome }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                        {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $cliente->clie_orc_celular)) }}
                    </td>
                    <td class="px-6 py-4 whitespace-normal break-words text-sm text-gray-700 font-poppins max-w-[150px]">
                        {{ $cliente->clie_orc_email }}
                    </td>
                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            {{-- Botão "Contatos" - AGORA LEVA PARA O INDEX DE CONTATOS COM FILTRO --}}
                            <a href="{{ route('contato_cliente.index', ['cliente_orcamento' => $cliente->id_co]) }}" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium
                                rounded-md shadow-sm text-white bg-button-contact-bg hover:bg-button-contact-hover
                                focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-contact-bg transition
                                duration-150 ease-in-out">
                                Contatos
                            </a>

                            {{-- Botão "Orçamentos" --}}
                            <a href="{{ route('orcamento.index', ['cliente_orcamento_id' => $cliente->id_co]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-budget-bg hover:bg-button-budget-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-budget-bg transition duration-150 ease-in-out">
                                Orçamentos
                            </a>

                            {{-- Botão "Ver" (CORRIGIDO: A rota agora usa $cliente->id_co) --}}
                            <a href="{{ route('cliente_orcamento.show', $cliente->id_co) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Ver
                            </a>

                            {{-- Botão "Editar" --}}
                            <a href="{{ route('cliente_orcamento.edit', $cliente->id_co) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>
                            {{-- Botão "Excluir" --}}
                            <form action="{{ route('cliente_orcamento.destroy', $cliente->id_co) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir este cliente de orçamento?');">
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
        {{-- Mensagem de "nenhum resultado" para busca - Estilo do Cliente --}}
        <p class="text-gray-600 text-center py-8 hidden" id="noResultsOrcamentoMessage">Nenhum cliente de orçamento
            encontrado com esse termo
            de busca.</p>
    </div>
    @endif
</div>

{{-- Script JavaScript para a busca ao vivo - ATUALIZADO para 3 campos --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const searchNameInput = document.getElementById('searchClientOrcamentoName');
        const searchEmailInput = document.getElementById('searchClientOrcamentoEmail');
        const searchCodeInput = document.getElementById('searchClientOrcamentoCode');

        const clientTableBody = document.getElementById('clientOrcamentoTableBody');
        const noClientsMessage = document.getElementById('noClientsOrcamentoMessage');
        const noResultsMessage = document.getElementById('noResultsOrcamentoMessage');

        const filterTable = () => {

            const searchName = (searchNameInput.value || '').toLowerCase();
            const searchEmail = (searchEmailInput.value || '').toLowerCase();
            const searchCode = (searchCodeInput.value || '').toLowerCase();

            const rows = clientTableBody.querySelectorAll('tr');
            let found = false;

            rows.forEach(row => {

                const code = row.cells[0].textContent.toLowerCase();
                const name = row.cells[1].textContent.toLowerCase();
                const email = row.cells[3].textContent.toLowerCase();

                const matchCode = code.includes(searchCode);
                const matchName = name.includes(searchName);
                const matchEmail = email.includes(searchEmail);

                if (matchCode && matchName && matchEmail) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }

            });

            const allEmpty = !searchName && !searchEmail && !searchCode;

            if (allEmpty) {
                noResultsMessage.classList.add('hidden');
            } else {
                if (found) {
                    noResultsMessage.classList.add('hidden');
                } else {
                    noResultsMessage.classList.remove('hidden');
                }
            }

        };

        searchNameInput.addEventListener('input', filterTable);
        searchEmailInput.addEventListener('input', filterTable);
        searchCodeInput.addEventListener('input', filterTable);

    });
</script>
@endsection