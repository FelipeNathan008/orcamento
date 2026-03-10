{{-- resources/views/view_cobranca/index.blade.php --}}
@extends('layouts.app_financeiro')

@section('title', 'Cobranças')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Cobranças
        </h1>
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                HOME
            </a>
        </div>

    </div>

    {{-- Alerta de sucesso --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Filtros --}}
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-6 gap-6 items-end">

            {{-- ID Fin --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    ID Fin
                </label>

                <input
                    type="text"
                    id="searchFinInput"
                    placeholder="ID financeiro..."
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- ID Orçamento --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    ID Orçamento
                </label>

                <input
                    type="text"
                    id="searchOrcInput"
                    placeholder="ID orçamento..."
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Cliente --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Cliente
                </label>

                <input
                    type="text"
                    id="searchClienteInput"
                    placeholder="Nome do cliente..."
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Tipo --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Tipo
                </label>

                <input
                    type="text"
                    id="searchTipoInput"
                    placeholder="Tipo pagamento..."
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Botão limpar --}}
            <div class="flex items-end">
                <button
                    type="button"
                    id="clearFiltersCobranca"
                    class="inline-flex items-center px-4 py-2 h-10 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300">
                    Limpar
                </button>
            </div>

        </div>

    </div>

    {{-- Nenhuma cobrança --}}
    @if ($cobrancas->isEmpty())
    <p class="text-gray-600 text-center py-8" id="noCobrancasMessage">Nenhuma cobrança cadastrada ainda.</p>
    @else

    {{-- Tabela --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">ID Fin</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">ID Orcamento</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Status</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">Ações</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="cobrancaTableBody">
                @foreach ($cobrancas as $cobranca)
                <tr>
                    <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">
                        {{ $cobranca->cobr_id_fin }}
                    </td>

                    <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">
                        {{ $cobranca->cobr_id_orc }}
                    </td>

                    <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">
                        {{ $cobranca->cobr_cliente }}
                    </td>

                    <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">
                        {{ $cobranca->tipoPagamento->tipo_plano_fin ?? 'Não informado' }}
                    </td>

                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">

                        @php
                        $normalizedStatus = strtolower(str_replace(' ', '_', $cobranca->cobr_status));

                        switch ($normalizedStatus) {
                        case 'inadimplencia':
                        $statusClass = 'bg-red-400';
                        break;

                        case 'débito':
                        case 'debito':
                        $statusClass = 'bg-yellow-400';
                        break;

                        case 'quitado':
                        $statusClass = 'bg-green-400';
                        break;

                        default:
                        $statusClass = 'bg-gray-400';
                        break;
                        }
                        @endphp

                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight text-gray-900">
                            <span aria-hidden="true"
                                class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}">
                            </span>

                            <span class="relative">
                                {{ ucfirst($cobranca->cobr_status) }}
                            </span>
                        </span>

                    </td>

                    <td class="px-2 py-4 text-center">
                        <button
                            class="detalhes-btn px-3 py-1 text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition"
                            data-id="{{ $cobranca->id_cobranca }}">
                            Ver Detalhes
                        </button>
                    </td>
                </tr>

                {{-- LINHA EXPANDIDA DOS DETALHES DE COBRANÇA --}}
                <tr id="detalhes-{{ $cobranca->id_cobranca }}" class="hidden">
                    <td colspan="6" class="bg-blue-50 p-4">

                        <h3 class="text-lg font-bold text-blue-800 mb-3">
                            Detalhes da Cobrança
                        </h3>

                        @php
                        $detalhes = $cobranca->detalhesCobranca; // Relationship
                        @endphp

                        @if($detalhes->isEmpty())
                        <p class="text-gray-600 text-sm">Nenhum detalhe encontrado para esta cobrança.</p>
                        @else
                        <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-md">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Valor</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Vencimento</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Status</th>
                                    <th class="px-4 py-2 text-left text-xs font-bold uppercase">Ações</th>
                                </tr>
                            </thead>
                            @php
                            $hoje = \Carbon\Carbon::today()->format('d/m/Y');
                            @endphp

                            <tbody class="divide-y divide-gray-200">
                                @foreach($detalhes as $item)
                                <tr>
                                    <td class="px-4 py-2 text-sm">
                                        R$ {{ number_format($item->det_cobr_valor_parcela, 2, ',', '.') }}
                                    </td>

                                    <td class="px-4 py-2 text-sm">
                                        {{ \Carbon\Carbon::parse($item->det_cobr_data_venc)->format('d/m/Y') }}
                                    </td>

                                    <td class="px-4 py-4 text-sm text-gray-700 font-poppins">

                                        @php
                                        $normalizedStatus = strtolower(str_replace(' ', '_', $item->det_cobr_status));

                                        switch ($normalizedStatus) {
                                        case 'inadimplencia':
                                        $statusClass = 'bg-red-400';
                                        break;

                                        case 'débito':
                                        case 'debito':
                                        $statusClass = 'bg-yellow-400';
                                        break;

                                        case 'quitado':
                                        $statusClass = 'bg-green-400';
                                        break;

                                        default:
                                        $statusClass = 'bg-gray-400';
                                        break;
                                        }
                                        @endphp

                                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight text-gray-900">
                                            <span aria-hidden="true"
                                                class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}">
                                            </span>

                                            <span class="relative">
                                                {{ ucfirst($item->det_cobr_status) }}
                                            </span>
                                        </span>

                                    </td>

                                    <td class="px-4 py-2 text-center">

                                        @if ($item->det_cobr_status === 'Inadimplencia')

                                        @php
                                        $totalNotificacoes = $cobranca->notificacoes->count();
                                        @endphp
                                        <div class="flex flex-col gap-2">

                                            {{-- CADASTRAR NOTIFICAÇÃO (somente se ainda não completou as 4) --}}
                                            @if ($item->det_cobr_status === 'Inadimplencia')

                                            @php
                                            $totalNotificacoes = $cobranca->notificacoes->count();
                                            @endphp
                                            <div class="flex flex-col gap-2">

                                                @if ($totalNotificacoes < 4)
                                                    <a href="{{ route('notificacao.create', ['cobranca_id' => $cobranca->id_cobranca]) }}"
                                                    class="px-3 py-1 text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 transition text-center">
                                                    Cadastrar Notificação
                                                    </a> @endif

                                                    <a href="{{ route('notificacao.index', ['cobranca_id' => $cobranca->id_cobranca]) }}"
                                                        class="px-3 py-1 text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition text-center">
                                                        Ver Notificações ({{ $totalNotificacoes }})
                                                    </a>
                                            </div>
                                            @endif

                                            @endif
                                            @if ($item->det_cobr_status != 'Quitado')
                                            <a href="{{ url('/detalhes_cobranca/' . $item->id_det_cobranca . '/edit') }}"
                                                class="px-3 py-1 text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 transition block mx-auto text-center">
                                                Acordo
                                            </a>
                                            @endif
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif

                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>

        <p class="text-gray-600 text-center py-8 hidden" id="noResultsMessage">
            Nenhuma cobrança encontrada com esse termo de busca.
        </p>
    </div>

    @endif
</div>

{{-- Script de busca dinâmica --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchCobrancaInput');
        const tableBody = document.getElementById('cobrancaTableBody');
        const noInitialMessage = document.getElementById('noCobrancasMessage');
        const noResultsMessage = document.getElementById('noResultsMessage');

        // LIGA OS BOTÕES "Ver Detalhes"
        document.querySelectorAll('.detalhes-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = btn.dataset.id;

                // Fecha todas as outras linhas de detalhes
                document.querySelectorAll('[id^="detalhes-"]').forEach(function(row) {
                    if (row.id !== 'detalhes-' + id) {
                        row.classList.add('hidden');
                    }
                });

                // Alterna a linha clicada
                const row = document.getElementById('detalhes-' + id);
                if (row) row.classList.toggle('hidden');
            });
        });

        const searchFinInput = document.getElementById('searchFinInput');
        const searchOrcInput = document.getElementById('searchOrcInput');
        const searchClienteInput = document.getElementById('searchClienteInput');
        const searchTipoInput = document.getElementById('searchTipoInput');
        const searchStatusInput = document.getElementById('searchStatusInput');

        const clearBtn = document.getElementById('clearFiltersCobranca');

        if (!tableBody) return;

        const rows = tableBody.querySelectorAll('tr');

        const filterTable = () => {

            const fin = searchFinInput.value.toLowerCase();
            const orc = searchOrcInput.value.toLowerCase();
            const cliente = searchClienteInput.value.toLowerCase();
            const tipo = searchTipoInput.value.toLowerCase();
            const status = searchStatusInput.value.toLowerCase();

            let found = false;

            rows.forEach(row => {

                const cells = row.querySelectorAll('td');

                if (cells.length < 5) return;

                const finCell = cells[0].textContent.toLowerCase();
                const orcCell = cells[1].textContent.toLowerCase();
                const clienteCell = cells[2].textContent.toLowerCase();
                const tipoCell = cells[3].textContent.toLowerCase();
                const statusCell = cells[4].textContent.toLowerCase();

                const matchFin = !fin || finCell.includes(fin);
                const matchOrc = !orc || orcCell.includes(orc);
                const matchCliente = !cliente || clienteCell.includes(cliente);
                const matchTipo = !tipo || tipoCell.includes(tipo);
                const matchStatus = !status || statusCell.includes(status);

                if (matchFin && matchOrc && matchCliente && matchTipo && matchStatus) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }

            });

            if (noResultsMessage) {
                noResultsMessage.classList.toggle('hidden', found);
            }

        };

        function clearFilters() {
            searchFinInput.value = '';
            searchOrcInput.value = '';
            searchClienteInput.value = '';
            searchTipoInput.value = '';
            searchStatusInput.value = '';
            filterTable();
        }

        searchFinInput.addEventListener('input', filterTable);
        searchOrcInput.addEventListener('input', filterTable);
        searchClienteInput.addEventListener('input', filterTable);
        searchTipoInput.addEventListener('input', filterTable);
        searchStatusInput.addEventListener('change', filterTable);

        clearBtn.addEventListener('click', clearFilters);

    });
</script>
@endpush
@endsection