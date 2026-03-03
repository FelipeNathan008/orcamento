@extends('layouts.app_financeiro')

@section('title', 'Financeiro')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Registros Financeiros
        </h1>
    </div>

    {{-- Sucesso --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Filtros --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

        {{-- Buscar por Cliente --}}
        <div class="relative w-full">
            <input type="text"
                id="searchFinanceiroInput"
                placeholder="Pesquisar por nome do cliente..."
                class="w-full h-9 pl-3 pr-3 text-sm bg-white border border-gray-300 rounded-md outline-none">
        </div>

        {{-- Filtro por Status --}}
        <select id="searchFinanceiroStatus"
            class="w-full h-9 pl-3 pr-3 text-sm bg-white border border-gray-300 rounded-md outline-none">
            <option value="">Filtrar por status...</option>
            <option value="Aguardando pagamento">Aguardando pagamento</option>
            <option value="Pagamento realizado">Pagamento realizado</option>
            <option value="Análise pedido">Análise pedido</option>
            <option value="Pedido fábrica">Pedido fábrica</option>
            <option value="Transportadora">Transportadora</option>
            <option value="Entregue">Entregue</option>
        </select>


        {{-- Botão Limpar --}}
        <div class="flex items-end">
            <button id="clearFiltersBtn"
                class="w-full h-9 text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 transition duration-150">
                Limpar Filtros
            </button>
        </div>

    </div>


    {{-- SE ESTIVER VAZIO --}}
    @if ($financeiro->isEmpty())
    <p class="text-gray-600 text-center py-8">Nenhum registro encontrado.</p>

    @else

    {{-- TABELA --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        ORÇAMENTO
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Cliente
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Valor Total
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="financeiroTableBody">

                @foreach ($financeiro as $fin)
                <tr class="hover:bg-gray-50 transition duration-150"
                    data-status="{{ strtolower($fin->fin_status) }}">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $fin->orcamento_id_orcamento }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $fin->fin_nome_cliente }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700">
                        R$ {{ number_format($fin->fin_valor_total, 2, ',', '.') }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700 font-poppins">
                        @php
                        $normalizedStatus = strtolower(str_replace(' ', '_', $fin->fin_status));

                        switch ($normalizedStatus) {
                        case 'aguardando_pagamento':
                        $statusClass = 'bg-yellow-400';
                        break;

                        case 'pagamento_realizado':
                        $statusClass = 'bg-green-400';
                        break;

                        case 'análise_pedido':
                        case 'analise_pedido':
                        $statusClass = 'bg-blue-400';
                        break;

                        case 'pedido_fábrica':
                        case 'pedido_fabrica':
                        $statusClass = 'bg-purple-400';
                        break;

                        case 'transportadora':
                        $statusClass = 'bg-indigo-400';
                        break;

                        case 'entregue':
                        $statusClass = 'bg-gray-400';
                        break;

                        default:
                        $statusClass = 'bg-indigo-400';
                        break;
                        }

                        // Quebra linha automática se tiver espaço
                        $formattedStatus = str_replace(' ', '<br>', ucfirst($fin->fin_status));
                        @endphp

                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight text-gray-900 text-center">
                            <span aria-hidden="true"
                                class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}"></span>
                            <span class="relative leading-tight">
                                {!! $formattedStatus !!}
                            </span>
                        </span>
                    </td>


                    <td class="px-6 py-4 text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">

                            {{-- Botão Status --}}
                            <button
                                type="button"
                                class="status-btn px-2 py-1 text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                data-id="{{ $fin->id_fin }}">
                                Status
                            </button>

                            {{-- Forma Pagamento --}}
                            @if(
                            $fin->fin_status !== 'Entregue'
                            )
                            <a href="{{ url('/forma_pagamento?' . $fin->id_fin) }}"
                                class="px-2 py-1 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                Forma Pagamento
                            </a>
                            @endif

                            @php
                            // Verifica se tem algum log com log_situacao = 0
                            $temStatusPendente = \App\Models\LogStatus::where('log_id_orcamento', $fin->orcamento_id_orcamento)
                            ->where('log_situacao', 0)
                            ->exists();
                            @endphp

                            {{-- Prosseguir Status --}}
                            @if(
                            $fin->fin_status !== 'Aguardando pagamento' &&
                            $fin->fin_status !== 'Entregue'
                            )
                            <form action="{{ route('financeiro.prosseguir', $fin->id_fin) }}"
                                method="POST"
                                onsubmit="return confirm('Tem certeza que deseja prosseguir o status?');">
                                @csrf

                                <button type="submit"
                                    class="px-2 py-1 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                                    id="prosseguirBtn-{{ $fin->id_fin }}"
                                    data-pendente="{{ $temStatusPendente ? '1' : '0' }}">
                                    Prosseguir Status
                                </button>
                            </form>
                            @endif

                        </div>
                    </td>
                </tr>

                {{-- LINHA OCULTA STATUS --}}
                <tr id="status-{{ $fin->id_fin }}" class="hidden">
                    <td colspan="5" class="px-6 py-4 text-sm text-gray-700">

                        @if($fin->logs->isEmpty())
                        <p class="text-center text-gray-500">Nenhum status encontrado.</p>
                        @else
                        <div class="grid grid-cols-6 gap-4">
                            @foreach ($fin->logs as $log)
                            @php
                            $status = $log->status_mercadoria_id_status;
                            $situacao = $log->log_situacao;
                            $img = $status . $situacao . '.png';
                            @endphp

                            <div class="flex flex-col items-center">
                                <img src="/imagens_status/{{ $img }}" class="w-16 h-16 object-contain">
                                <p class="text-sm text-gray-700 mt-2">
                                    {{$log->log_nome_status}}
                                </p>
                            </div>
                            @endforeach
                        </div>
                        @endif

                    </td>
                </tr>

                @endforeach

            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.status-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = btn.dataset.id;

                    document.querySelectorAll('[id^="status-"]').forEach(function(row) {
                        if (row.id !== 'status-' + id) {
                            row.classList.add('hidden');
                        }
                    });

                    const row = document.getElementById('status-' + id);
                    if (row) row.classList.toggle('hidden');
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {

            const searchInput = document.getElementById('searchFinanceiroInput');
            const searchStatusSelect = document.getElementById('searchFinanceiroStatus');
            const clearBtn = document.getElementById('clearFiltersBtn');
            const tableBody = document.getElementById('financeiroTableBody');

            const filterTable = () => {

                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedStatus = searchStatusSelect.value.toLowerCase().trim();
                const rows = tableBody.querySelectorAll('tr');

                rows.forEach(row => {

                    // Ignora linha oculta de status
                    if (row.id && row.id.startsWith('status-')) return;

                    const clienteNome = row.children[1]?.textContent.toLowerCase().trim() ?? '';
                    const statusText = row.dataset.status ?? '';

                    const matchClient = clienteNome.includes(searchTerm);
                    const matchStatus =
                        selectedStatus === '' ||
                        statusText === selectedStatus;

                    if (matchClient && matchStatus) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';

                        // Fecha linha de status aberta
                        const id = row.querySelector('.status-btn')?.dataset.id;
                        const statusRow = document.getElementById('status-' + id);
                        if (statusRow) statusRow.classList.add('hidden');
                    }
                });
            };

            // Eventos filtro
            searchInput.addEventListener('input', filterTable);
            searchStatusSelect.addEventListener('change', filterTable);

            // Botão limpar filtros
            clearBtn.addEventListener('click', function() {

                searchInput.value = '';
                searchStatusSelect.value = '';

                const rows = tableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    row.style.display = '';

                    // Fecha todas linhas de status
                    if (row.id && row.id.startsWith('status-')) {
                        row.classList.add('hidden');
                    }
                });
            });

        });
    </script>


    @endif

</div>

@endsection