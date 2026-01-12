{{-- resources/views/view_cobranca/index.blade.php --}}
@extends('layouts.app_financeiro')

@section('title', 'Cobranças')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Cobranças
        </h1>

    </div>

    {{-- Alerta de sucesso --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Campo de busca --}}
    <div class="relative w-full mb-6">
        <input type="text" id="searchCobrancaInput" placeholder="Pesquisar cobrança..."
            class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none
                   hover:border-custom-border-hover focus:border-custom-border-focus text-custom-dark-text">
        <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20">
            <path fill-rule="evenodd"
                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                clip-rule="evenodd" />
        </svg>
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

                    <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">
                        {{ $cobranca->cobr_status }}
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

                                    <td class="px-4 py-2 text-sm">
                                        {{ $item->det_cobr_status }}
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
                                            {{-- SOMENTE ACORDO --}}
                                            <a href="{{ url('/detalhes_cobranca/' . $item->id_det_cobranca . '/edit') }}"
                                                class="px-3 py-1 text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 transition block mx-auto text-center">
                                                Acordo
                                            </a>
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

        // FILTRO DE BUSCA
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = tableBody.querySelectorAll('tr');
            let found = false;

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();

                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }
            });

            if (!searchTerm) {
                noResultsMessage.classList.add('hidden');
                return;
            }

            found ? noResultsMessage.classList.add('hidden') : noResultsMessage.classList.remove('hidden');
        });
    });
</script>
@endpush


@endsection