@extends('layouts.app_financeiro')

@section('title', 'Cobranças')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Cobranças
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                HOME
            </a>

        </div>

    </div>

    <x-alert-flash />

    {{-- FILTROS --}}
    <form method="GET" action="{{ route('cobranca.index') }}" class="mb-6">

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- ID Financeiro --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        ID Financeiro
                    </label>

                    <input
                        type="number"
                        name="id_financeiro"
                        value="{{ request('id_financeiro') }}"
                        placeholder="Digite o ID..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">

                </div>

                {{-- ID Orçamento --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        ID Orçamento
                    </label>

                    <input
                        type="number"
                        name="id_orcamento"
                        value="{{ request('id_orcamento') }}"
                        placeholder="Digite o ID..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">

                </div>

                {{-- Cliente --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Cliente
                    </label>

                    <input
                        type="text"
                        name="cliente"
                        value="{{ request('cliente') }}"
                        placeholder="Digite o nome..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">

                </div>

                {{-- Forma de Pagamento --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Forma Pagamento
                    </label>

                    <select
                        name="tipo_pagamento"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">

                        <option value="">Todas</option>

                        @foreach ($tiposPagamento as $tipo)
                        <option
                            value="{{ $tipo->tipo_plano_fin }}"
                            {{ request('tipo_pagamento') == $tipo->tipo_plano_fin ? 'selected' : '' }}>
                            {{ $tipo->tipo_plano_fin }}
                        </option>
                        @endforeach

                    </select>

                </div>

                {{-- Buscar --}}
                <div class="flex items-end">

                    <button
                        type="submit"
                        class="w-full h-10 text-white rounded-md hover:opacity-90 transition"
                        style="background-color:#EA792D;">
                        Buscar
                    </button>

                </div>

                {{-- Limpar --}}
                <div class="flex items-end">

                    <a
                        href="{{ route('cobranca.index') }}"
                        class="w-full h-10 bg-gray-300 rounded-md text-gray-800 flex items-center justify-center hover:bg-gray-400 transition">
                        Limpar
                    </a>

                </div>

            </div>

        </div>

    </form>

    @if ($formasPagamento->isEmpty())

    @if(request('id_financeiro') || request('id_orcamento') || request('cliente') || request('tipo_pagamento'))

    <div class="text-center py-8">

        <p class="text-gray-600 text-lg">
            Nenhuma cobrança encontrada com os filtros informados.
        </p>

        <a
            href="{{ route('cobranca.index') }}"
            class="inline-block mt-3 text-orange-600 hover:text-orange-700 font-medium">
            Limpar filtros
        </a>

    </div>

    @else
    <p class="text-gray-600 text-center py-8">
        Nenhuma cobrança cadastrada ainda.
    </p>
    @endif
    @else
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">

        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-table-header-bg">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        ID Financeiro
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        ID Orçamento
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Cliente
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Forma Pagamento
                    </th>

                    <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                        Parcelas
                    </th>

                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                        Ações
                    </th>

                </tr>

            </thead>

            <tbody class="bg-white divide-y divide-gray-200" id="cobrancaTableBody">

                @foreach ($formasPagamento as $forma)

                <tr class="hover:bg-gray-50 transition duration-150">

                    {{-- ID Financeiro --}}
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $forma->financeiro_id_fin }}
                    </td>

                    {{-- ID Orçamento --}}
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">

                        <div>
                            {{ $forma->financeiro?->orcamento_id_orcamento ?? 'N/A' }}
                        </div>

                        @if($forma->financeiro?->orcamento?->orc_cod_interno || $forma->financeiro?->orcamento?->orc_cod_fabrica)

                        <div class="text-xs text-gray-500 mt-1">

                            @if($forma->financeiro?->orcamento?->orc_cod_interno)
                            Interno: {{ $forma->financeiro->orcamento->orc_cod_interno }}
                            @endif

                            @if(
                            $forma->financeiro?->orcamento?->orc_cod_interno &&
                            $forma->financeiro?->orcamento?->orc_cod_fabrica
                            )
                            <span class="mx-1">|</span>
                            @endif

                            @if($forma->financeiro?->orcamento?->orc_cod_fabrica)
                            Fábrica: {{ $forma->financeiro->orcamento->orc_cod_fabrica }}
                            @endif

                        </div>

                        @endif

                    </td>

                    {{-- Cliente --}}
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $forma->financeiro?->fin_nome_cliente ?? 'N/A' }}
                    </td>

                    {{-- Forma de Pagamento --}}
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $forma->tipoPagamento?->tipo_plano_fin ?? 'N/A' }}
                    </td>

                    {{-- Quantidade de parcelas --}}
                    <td class="px-6 py-4 text-sm text-center">

                        <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                            {{ $forma->detalhes->count() }}
                            {{ $forma->detalhes->count() == 1 ? 'parcela' : 'parcelas' }}
                        </span>

                    </td>

                    {{-- Ações --}}
                    <td class="px-2 py-4 text-center">

                        <button
                            type="button"
                            class="detalhes-btn inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600 transition"
                            data-id="{{ $forma->id_forma_pag }}">
                            Detalhes
                        </button>

                    </td>

                </tr>

                {{-- DETALHES DA COBRANÇA --}}
                <tr id="detalhes-{{ $forma->id_forma_pag }}" class="hidden">

                    <td colspan="6" class="bg-orange-50 p-4">

                        <div class="bg-white border border-orange-200 rounded-lg shadow-sm overflow-x-auto">

                            <div class="px-4 py-3 bg-orange-100 border-b border-orange-200">

                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">

                                    <div>

                                        <h3 class="text-base font-bold text-orange-800">
                                            Detalhes da Cobrança
                                        </h3>

                                        <p class="text-xs text-orange-700 mt-1">
                                            Forma de pagamento:
                                            {{ $forma->tipoPagamento?->tipo_plano_fin ?? 'N/A' }}
                                        </p>

                                    </div>

                                    <span class="text-sm text-orange-700">
                                        {{ $forma->detalhes->count() }}
                                        {{ $forma->detalhes->count() == 1 ? 'parcela' : 'parcelas' }}
                                    </span>

                                </div>

                            </div>

                            @php
                            $detalhes = $forma->detalhes;
                            @endphp

                            @if($detalhes->isEmpty())

                            <div class="p-4 flex items-center justify-between">

                                <p class="text-gray-600 text-sm">
                                    Nenhuma parcela atrasada encontrada.
                                </p>

                                <a
                                    href="{{ route('notificacao.create', ['id_financeiro' => $forma->financeiro_id_fin]) }}"
                                    class="px-3 py-1 text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 transition">
                                    Cadastrar Notificação
                                </a>

                            </div>

                            @else

                            <table class="min-w-full divide-y divide-gray-200">

                                <thead class="bg-gray-200">

                                    <tr>

                                        <th class="px-4 py-2 text-left text-xs font-bold uppercase">
                                            Parcela
                                        </th>

                                        <th class="px-4 py-2 text-left text-xs font-bold uppercase">
                                            Valor Original
                                        </th>

                                        <th class="px-4 py-2 text-left text-xs font-bold uppercase">
                                            Valor Atual
                                        </th>

                                        <th class="px-4 py-2 text-left text-xs font-bold uppercase">
                                            Vencimento
                                        </th>

                                        <th class="px-4 py-2 text-left text-xs font-bold uppercase">
                                            Status
                                        </th>

                                        <th class="px-4 py-2 text-center text-xs font-bold uppercase">
                                            Ações
                                        </th>

                                    </tr>

                                </thead>

                                <tbody class="divide-y divide-gray-200">

                                    @foreach($detalhes as $item)

                                    <tr class="hover:bg-gray-50 transition duration-150">

                                        {{-- Parcela --}}
                                        <td class="px-4 py-3 text-sm font-medium text-gray-900">
                                            #{{ $item->id_det_forma }}
                                        </td>

                                        {{-- Valor Original --}}
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            R$ {{ number_format($item->det_forma_valor_original, 2, ',', '.') }}
                                        </td>

                                        {{-- Valor Atual --}}
                                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                            R$ {{ number_format($item->det_forma_valor_parcela, 2, ',', '.') }}
                                        </td>

                                        {{-- Vencimento --}}
                                        <td class="px-4 py-3 text-sm text-gray-900">
                                            {{ \Carbon\Carbon::parse($item->det_forma_data_venc)->format('d/m/Y') }}
                                        </td>

                                        {{-- Status --}}
                                        <td class="px-4 py-3 text-sm">

                                            <span class="px-2 py-1 rounded-md text-xs font-semibold {{ $item->cor_status }}">
                                                {{ $item->det_situacao }}
                                            </span>

                                        </td>

                                        {{-- Ações --}}
                                        <td class="px-4 py-3 text-center">

                                            <div class="flex justify-center items-center gap-2 flex-wrap">

                                                <a
                                                    href="{{ route('cobranca.edit', ['cobranca' => $item->id_det_forma, 'id_financeiro' => request('id_financeiro'),
                                                        'id_orcamento' => request('id_orcamento'), 'cliente' => request('cliente'),
                                                        'tipo_pagamento' => request('tipo_pagamento'),'page' => request('page'),
                                                    ]) }}"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                    Acordo
                                                </a>

                                                <a
                                                    href="{{ route('notificacao.create', ['id_det_forma' => $item->id_det_forma]) }}"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                                    Cadastrar Notificação
                                                </a>

                                                @if($item->notificacoes->isNotEmpty())

                                                <a
                                                    href="{{ route('notificacao.index', ['id_det_forma' => $item->id_det_forma]) }}"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600">
                                                    Ver Notificações
                                                </a>

                                                @endif

                                            </div>

                                        </td>

                                    </tr>

                                    @endforeach

                                </tbody>

                            </table>

                            @endif

                        </div>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>
    </div>

    <x-pagination-compact :paginator="$formasPagamento" />

    @endif

</div>

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.detalhes-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const id = btn.dataset.id;
                document.querySelectorAll('[id^="detalhes-"]').forEach(function(row) {
                    if (row.id !== 'detalhes-' + id) {
                        row.classList.add('hidden');
                    }
                });
                const row = document.getElementById('detalhes-' + id);
                if (row) {
                    row.classList.toggle('hidden');
                }
            });
        });
    });
</script>

@endpush

@endsection