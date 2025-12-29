@extends('layouts.app') {{-- Assumindo que você tem um layout principal chamado 'app' --}}

@section('title', 'Orçamentos Vencidos') {{-- Título da Página --}}

@section('content')

    {{-- Div principal que centraliza o conteúdo e ajusta a largura máxima --}}
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

        {{-- Cabeçalho da Seção (Título) --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
                Orçamentos Vencidos
            </h1>
            {{-- Botão de retorno para a lista principal --}}
            <a href="{{ route('orcamento.index') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
               style="background-color: #374151;">
                Voltar para Orçamentos Ativos
            </a>
        </div>

        {{-- Formulários de Busca e Filtro --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            {{-- Busca por Cliente ou ID --}}
            <div class="relative w-full">
                <input type="text" name="search_query" id="searchOrcamentoInput"
                       placeholder="Pesquisar por ID do orçamento ou nome do cliente..." value="{{ request('search_query') }}"
                       class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
                {{-- Ícone de busca --}}
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
            </div>

            {{-- Filtro por Status (Select) --}}
            <div class="relative w-full">
                <select name="status_query" id="searchStatusSelect"
                        class="w-full h-9 pl-3 pr-10 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text appearance-none">
                    <option value="">Filtrar por status...</option>
                    <option value="pendente">Pendente</option>
                    <option value="para aprovacao">Para Aprovação</option>
                    <option value="aprovado">Aprovado</option>
                    <option value="rejeitado">Rejeitado</option>
                    <option value="finalizado">Finalizado</option>
                </select>
                {{-- Ícone de dropdown --}}
                <svg class="absolute top-1/2 right-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text pointer-events-none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>

        {{-- Mensagem "Nenhum orçamento" --}}
        @if ($orcamentos->isEmpty())
            <p class="text-gray-600 text-center py-8" id="noOrcamentosMessage">
                Nenhum orçamento vencido encontrado.
            </p>
        @else
            {{-- TABELA DE ORÇAMENTOS (visível apenas se houver orçamentos) --}}
            <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto" id="orcamentoTableContainer">
                <table class="min-w-full w-full divide-y divide-gray-200">
                    <thead class="bg-table-header-bg">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Cliente</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Data Início</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Data Fim</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Status</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="orcamentoTableBody">
                        @foreach ($orcamentos as $orcamento)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">{{ $orcamento->id_orcamento }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 font-poppins">{{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'Cliente Não Encontrado' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 font-poppins">{{ $orcamento->orc_data_inicio->format('d/m/Y') }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 font-poppins">{{ $orcamento->orc_data_fim->format('d/m/Y') }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700 font-poppins">
                                    @php
                                        $normalizedStatus = strtolower(str_replace(' ', '_', $orcamento->orc_status));
                                        switch ($normalizedStatus) {
                                            case 'pendente': $statusClass = 'bg-yellow-400'; break;
                                            case 'para_aprovacao': $statusClass = 'bg-teal-600'; break;
                                            case 'aprovado': $statusClass = 'bg-green-400'; break;
                                            case 'rejeitado': $statusClass = 'bg-orange-400'; break;
                                            case 'finalizado': $statusClass = 'bg-gray-400'; break;
                                            default: $statusClass = 'bg-indigo-400'; break;
                                        }
                                    @endphp
                                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight text-gray-900">
                                        <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}"></span>
                                        <span class="relative">{{ ucfirst($orcamento->orc_status) }}</span>
                                    </span>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                                        {{-- Botões de Ação --}}
                                        <a href="{{ route('detalhes_orcamento.index', ['search_orcamento_id' => $orcamento->id_orcamento]) }}" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">Visualizar Detalhes</a>
                                        
                                        {{-- Opções de edição e exclusão --}}
                                        <a href="{{ route('orcamento.edit', $orcamento->id_orcamento) }}" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">Editar</a>
                                        <form action="{{ route('orcamento.destroy', $orcamento->id_orcamento) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este orçamento?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-cancel-bg hover:bg-button-cancel-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-cancel-bg transition duration-150 ease-in-out">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="text-gray-600 text-center py-8 hidden" id="noResultsOrcamentoMessage">Nenhum orçamento encontrado com esse termo de busca.</p>
            </div>
        @endif
    </div>

    {{-- Script JavaScript para a busca em tempo real --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchOrcamentoInput');
            const searchStatusSelect = document.getElementById('searchStatusSelect');
            const orcamentoTableBody = document.getElementById('orcamentoTableBody');
            const noResultsMessage = document.getElementById('noResultsOrcamentoMessage');

            const filterTable = () => {
                if (!orcamentoTableBody) return;

                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedStatus = searchStatusSelect.value.toLowerCase().trim();
                const rows = orcamentoTableBody.querySelectorAll('tr');
                let foundResults = false;

                rows.forEach(row => {
                    const idOrcamentoText = row.children[0]?.textContent.toLowerCase().trim() ?? '';
                    const clienteNomeText = row.children[1]?.textContent.toLowerCase().trim() ?? '';
                    const statusText = row.children[4]?.querySelector('.relative')?.textContent.toLowerCase().trim() ?? '';

                    const normalizedStatusFromSelect = selectedStatus.replace(/ /g, '');
                    const normalizedStatusFromTable = statusText.replace(/ /g, '');

                    const matchClient = idOrcamentoText.includes(searchTerm) || clienteNomeText.includes(searchTerm);
                    const matchStatus = (selectedStatus === '' || normalizedStatusFromTable === normalizedStatusFromSelect);

                    if (matchClient && matchStatus) {
                        row.style.display = '';
                        foundResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (foundResults) {
                    noResultsMessage?.classList.add('hidden');
                } else {
                    noResultsMessage?.classList.remove('hidden');
                }
            };

            searchInput?.addEventListener('input', filterTable);
            searchStatusSelect?.addEventListener('change', filterTable);
        });
    </script>
@endsection