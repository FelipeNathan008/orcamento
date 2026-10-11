@extends('layouts.app_financeiro')

@section('title', 'Cobranças')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Cobranças">
            <x-header-action href="{{ route('dashboard') }}" color="gray">
                Home
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('cobranca.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="id_financeiro" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                ID Financeiro
                            </label>
                            <input type="number" id="id_financeiro" name="id_financeiro" value="{{ request('id_financeiro') }}"
                                placeholder="ID financeiro..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="id_orcamento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                ID Orçamento
                            </label>
                            <input type="number" id="id_orcamento" name="id_orcamento" value="{{ request('id_orcamento') }}"
                                placeholder="ID orçamento..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="cliente" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Cliente
                            </label>
                            <input type="text" id="cliente" name="cliente" value="{{ request('cliente') }}"
                                placeholder="Nome do cliente..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="tipo_pagamento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Forma Pagamento
                            </label>
                            <select id="tipo_pagamento" name="tipo_pagamento"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                @foreach($tiposPagamento as $tipo)
                                <option value="{{ $tipo->tipo_plano_fin }}" {{ request('tipo_pagamento') == $tipo->tipo_plano_fin ? 'selected' : '' }}>
                                    {{ $tipo->tipo_plano_fin }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <x-primary-button class="w-full h-11">
                                <x-icons.search />
                                Buscar
                            </x-primary-button>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('cobranca.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de cobranças</h2>

                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $formasPagamento->total() }} cobrança(s)
                    </span>
                </div>
            </div>

            @if($formasPagamento->isEmpty())
            @if(request('id_financeiro') || request('id_orcamento') || request('cliente') || request('tipo_pagamento'))
            <x-empty-state
                title="Nenhuma cobrança encontrada"
                message="Não existem cobranças correspondentes aos filtros informados."
                route="cobranca.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhuma cobrança cadastrada"
                message="Ainda não existem cobranças cadastradas no sistema."
                route="cobranca.index"
                button-text="Voltar às cobranças" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Financeiro</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Orçamento</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Cliente</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Forma Pagamento</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Parcelas</th>
                                <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100" id="cobrancaTableBody">
                            @foreach($formasPagamento as $forma)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                            <x-icons.document />
                                        </div>
                                        <span class="text-sm font-bold text-gray-800">
                                            #{{ $forma->financeiro_id_fin }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        #{{ $forma->financeiro?->orcamento_id_orcamento ?? 'N/A' }}
                                    </span>

                                    @if($forma->financeiro?->orcamento?->orc_cod_interno || $forma->financeiro?->orcamento?->orc_cod_fabrica)
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($forma->financeiro?->orcamento?->orc_cod_interno)
                                        Interno: {{ $forma->financeiro->orcamento->orc_cod_interno }}
                                        @endif

                                        @if($forma->financeiro?->orcamento?->orc_cod_interno && $forma->financeiro?->orcamento?->orc_cod_fabrica)
                                        <span class="mx-1">|</span>
                                        @endif

                                        @if($forma->financeiro?->orcamento?->orc_cod_fabrica)
                                        Fábrica: {{ $forma->financeiro->orcamento->orc_cod_fabrica }}
                                        @endif
                                    </div>
                                    @endif
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $forma->financeiro?->fin_nome_cliente ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $forma->tipoPagamento?->tipo_plano_fin ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap text-center">
                                    <x-badge
                                        type="orange"
                                        :text="$forma->detalhes->count() . ' ' . ($forma->detalhes->count() == 1 ? 'parcela' : 'parcelas')"
                                        :dot="false" />
                                </td>
                                <td class="px-2 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :budgets-route="'#detalhes-'.$forma->id_forma_pag"
                                        budgets-text="Detalhes"
                                        :budgets-count="null"
                                        :forma-pagamento-route="route('forma_pagamento.index', CryptHelper::encrypt($forma->financeiro_id_fin))"
                                        forma-pagamento-text="Forma Pagamento" />
                                </td>
                            </tr>

                            <tr id="detalhes-{{ $forma->id_forma_pag }}" class="hidden">
                                <td colspan="6" class="px-5 py-5 bg-gray-50 border-t border-gray-200">
                                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">

                                        <div class="px-5 py-4 border-b border-gray-200 bg-white">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                                                        <x-icons.document />
                                                    </div>

                                                    <div>
                                                        <h3 class="text-sm font-bold text-gray-800">
                                                            Detalhes da cobrança
                                                        </h3>

                                                        <p class="text-xs text-gray-500 mt-1">
                                                            Forma de pagamento:
                                                            <span class="font-semibold text-gray-700">
                                                                {{ $forma->tipoPagamento?->tipo_plano_fin ?? 'N/A' }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 self-start sm:self-auto">
                                                    <span class="text-xs text-gray-500">
                                                        Total de parcelas
                                                    </span>

                                                    <span class="inline-flex items-center justify-center min-w-8 h-8 px-2 rounded-lg bg-orange-50 border border-orange-200 text-xs font-bold text-orange-700">
                                                        {{ $forma->detalhes->count() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                        $detalhes = $forma->detalhes;
                                        @endphp

                                        @if($detalhes->isEmpty())
                                        <div class="px-5 py-8">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 px-4 py-4 rounded-lg border border-gray-200 bg-gray-50">
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-700">
                                                        Nenhuma parcela atrasada encontrada
                                                    </p>

                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Você pode cadastrar uma notificação para esta cobrança.
                                                    </p>
                                                </div>

                                                <x-table-actions
                                                    :notificacao-route="route('notificacao.create', [
                                                        'id_det_forma' => CryptHelper::encrypt($item->id_det_forma),
                                                        'origem' => 'cobranca',
                                                        'id_financeiro' => request('id_financeiro'),
                                                        'id_orcamento' => request('id_orcamento'),
                                                        'cliente' => request('cliente'),
                                                        'tipo_pagamento' => request('tipo_pagamento'),
                                                        'page' => request('page'),
                                                    ])"
                                                    notificacao-text="Cadastrar Notificação" />

                                            </div>
                                        </div>
                                        @else

                                        <div class="px-5 py-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <div>
                                                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide">
                                                        Parcelas
                                                    </h4>

                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Consulte valores, vencimentos e situação de cada parcela.
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                                <div class="overflow-x-auto">
                                                    <table class="min-w-full divide-y divide-gray-200">
                                                        <thead class="bg-gray-50">
                                                            <tr>
                                                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                                    Parcela
                                                                </th>

                                                                <th class="px-4 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                                    Valor Original
                                                                </th>

                                                                <th class="px-4 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                                    Valor Atual
                                                                </th>

                                                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                                    Vencimento
                                                                </th>

                                                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                                    Status
                                                                </th>

                                                                <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                                    Ações
                                                                </th>
                                                            </tr>
                                                        </thead>

                                                        <tbody class="divide-y divide-gray-100 bg-white">
                                                            @foreach($detalhes as $item)
                                                            <tr class="hover:bg-orange-50/30 transition">

                                                                <td class="px-4 py-4 whitespace-nowrap">
                                                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-gray-100 border border-gray-200 text-xs font-bold text-gray-700">
                                                                        #{{ $item->id_det_forma }}
                                                                    </span>
                                                                </td>

                                                                <td class="px-4 py-4 text-right whitespace-nowrap">
                                                                    <span class="text-sm text-gray-700">
                                                                        R$ {{ number_format($item->det_forma_valor_original, 2, ',', '.') }}
                                                                    </span>
                                                                </td>

                                                                <td class="px-4 py-4 text-right whitespace-nowrap">
                                                                    <span class="text-sm font-bold text-gray-800">
                                                                        R$ {{ number_format($item->det_forma_valor_parcela, 2, ',', '.') }}
                                                                    </span>
                                                                </td>

                                                                <td class="px-4 py-4 whitespace-nowrap">
                                                                    <span class="text-sm text-gray-700">
                                                                        {{ \Carbon\Carbon::parse($item->det_forma_data_venc)->format('d/m/Y') }}
                                                                    </span>
                                                                </td>

                                                                @php
                                                                $tipoStatus = match ($item->det_situacao) {
                                                                'Pago', 'Quitado' => 'green',
                                                                'Não Pago' => 'yellow',
                                                                'Acordo' => 'blue',
                                                                'Inadimplencia', 'Inadimplência', 'Atrasado' => 'red',
                                                                default => 'gray',
                                                                };
                                                                @endphp

                                                                <td class="px-4 py-4 whitespace-nowrap">
                                                                    <x-badge
                                                                        :type="$tipoStatus"
                                                                        :text="$item->det_situacao" />
                                                                </td>

                                                                <td class="px-4 py-4 text-center">
                                                                    <div class="flex justify-center items-center gap-2 flex-wrap">

                                                                        <x-table-actions
                                                                            :edit-route="route('cobranca.edit', [
                                                                                'cobranca' => CryptHelper::encrypt($item->id_det_forma),
                                                                                'id_financeiro' => request('id_financeiro'),
                                                                                'id_orcamento' => request('id_orcamento'),
                                                                                'cliente' => request('cliente'),
                                                                                'tipo_pagamento' => request('tipo_pagamento'),
                                                                                'page' => request('page'),
                                                                            ])"
                                                                            edit-text="Acordo" />

                                                                        <x-table-actions
                                                                            :notificacao-route="route('notificacao.create', [
                                                                            'id_det_forma' => CryptHelper::encrypt($item->id_det_forma),
                                                                            'id_financeiro' => request('id_financeiro'),
                                                                            'id_orcamento' => request('id_orcamento'),
                                                                            'cliente' => request('cliente'),
                                                                            'tipo_pagamento' => request('tipo_pagamento'),
                                                                            'page' => request('page'),
                                                                            ])"
                                                                            notificacao-text="Cadastrar Notificação" />

                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$formasPagamento" />
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const STORAGE_KEY = 'cobranca_estado';

        function salvarEstado(id) {
            sessionStorage.setItem(STORAGE_KEY, JSON.stringify({
                url: window.location.pathname + window.location.search,
                detalhes: id,
                scroll: window.scrollY
            }));
        }

        function limparEstado() {
            sessionStorage.removeItem(STORAGE_KEY);
        }

        document.querySelectorAll('.parcelas-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                const href = this.getAttribute('href');

                if (!href || !href.startsWith('#detalhes-')) {
                    return;
                }

                e.preventDefault();

                const id = href.replace('#detalhes-', '');

                document.querySelectorAll('[id^="detalhes-"]').forEach(function(row) {
                    if (row.id !== 'detalhes-' + id) {
                        row.classList.add('hidden');
                    }
                });

                const row = document.getElementById('detalhes-' + id);

                if (row) {
                    const estavaAberto = !row.classList.contains('hidden');

                    row.classList.toggle('hidden');

                    if (!estavaAberto) {
                        salvarEstado(id);
                    } else {
                        limparEstado();
                    }
                }
            });
        });

        const estadoSalvo = sessionStorage.getItem(STORAGE_KEY);

        if (!estadoSalvo) {
            return;
        }

        try {
            const estado = JSON.parse(estadoSalvo);
            const urlAtual = window.location.pathname + window.location.search;

            if (estado.url !== urlAtual) {
                limparEstado();
                return;
            }

            const row = document.getElementById('detalhes-' + estado.detalhes);

            if (!row) {
                limparEstado();
                return;
            }

            document.querySelectorAll('[id^="detalhes-"]').forEach(function(rowItem) {
                rowItem.classList.add('hidden');
            });

            row.classList.remove('hidden');

            requestAnimationFrame(function() {
                requestAnimationFrame(function() {
                    window.scrollTo({
                        top: estado.scroll,
                        behavior: 'instant'
                    });
                });
            });

        } catch (e) {
            limparEstado();
        }
    });
</script>
@endpush
@endsection