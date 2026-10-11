{{-- resources/views/view_notificacao/index.blade.php --}}
@extends('layouts.app_financeiro')

@section('title', 'Notificações')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Notificações">
            <x-header-action href="{{ route('dashboard') }}" color="gray">
                Home
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('notificacao.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="id_financeiro" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                ID Financeiro
                            </label>
                            <input
                                type="number"
                                id="id_financeiro"
                                name="id_financeiro"
                                value="{{ request('id_financeiro') }}"
                                placeholder="ID financeiro..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="id_orcamento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                ID Orçamento
                            </label>
                            <input
                                type="number"
                                id="id_orcamento"
                                name="id_orcamento"
                                value="{{ request('id_orcamento') }}"
                                placeholder="ID orçamento..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="cliente" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Cliente
                            </label>
                            <input
                                type="text"
                                id="cliente"
                                name="cliente"
                                value="{{ request('cliente') }}"
                                placeholder="Nome do cliente..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="forma_pagamento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Forma Pagamento
                            </label>
                            <select
                                id="forma_pagamento"
                                name="forma_pagamento"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                @foreach($tiposPagamento as $tipo)
                                <option value="{{ $tipo->tipo_plano_fin }}" {{ request('forma_pagamento') == $tipo->tipo_plano_fin ? 'selected' : '' }}>
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
                        <a
                            href="{{ route('notificacao.index') }}"
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
                <h2 class="text-lg font-bold text-gray-800">
                    Lista de notificações
                </h2>

                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $notificacoes->total() }} {{ $notificacoes->total() == 1 ? 'registro' : 'registros' }}
                    </span>
                </div>
            </div>

            @if($grupos->isEmpty())
            @if(request('id_financeiro') || request('id_orcamento') || request('cliente') || request('forma_pagamento'))
            <x-empty-state
                title="Nenhuma notificação encontrada"
                message="Não existem notificações correspondentes aos filtros informados."
                route="notificacao.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhuma notificação cadastrada"
                message="Ainda não existem notificações cadastradas no sistema."
                route="notificacao.index"
                button-text="Voltar às notificações" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Financeiro
                                </th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Orçamento
                                </th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Cliente
                                </th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Forma Pagamento
                                </th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">
                                    Parcelas
                                </th>
                                <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($grupos as $grupoId => $notificacoesForma)
                            @php
                            $primeiraNotificacao = $notificacoesForma->first();
                            $detalhe = $primeiraNotificacao->detalheFormaPag;
                            $formaPagamento = $detalhe?->formaPagamento;
                            $financeiro = $formaPagamento?->financeiro;
                            $orcamento = $financeiro?->orcamento;
                            $tipoPagamento = $formaPagamento?->tipoPagamento;
                            $linhaId = 'notificacoes-' . $grupoId;

                            $parcelas = $notificacoesForma->groupBy(function ($notificacao) {
                            return $notificacao->detalheFormaPag?->id_det_forma;
                            });
                            @endphp

                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                            <x-icons.document />
                                        </div>
                                        <span class="text-sm font-bold text-gray-800">
                                            #{{ $financeiro?->id_fin ?? 'N/A' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        #{{ $orcamento?->id_orcamento ?? 'N/A' }}
                                    </span>

                                    @if($orcamento?->orc_cod_interno || $orcamento?->orc_cod_fabrica)
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($orcamento?->orc_cod_interno)
                                        Interno: {{ $orcamento->orc_cod_interno }}
                                        @endif

                                        @if($orcamento?->orc_cod_interno && $orcamento?->orc_cod_fabrica)
                                        <span class="mx-1">|</span>
                                        @endif

                                        @if($orcamento?->orc_cod_fabrica)
                                        Fábrica: {{ $orcamento->orc_cod_fabrica }}
                                        @endif
                                    </div>
                                    @endif
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $financeiro?->fin_nome_cliente ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $tipoPagamento?->tipo_plano_fin ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap text-center">
                                    <x-badge
                                        type="orange"
                                        :text="$parcelas->count() . ' ' . ($parcelas->count() == 1 ? 'parcela' : 'parcelas')"
                                        :dot="false" />
                                </td>

                                <td class="px-2 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :budgets-route="'#' . $linhaId"
                                        budgets-text="Detalhes"
                                        :budgets-count="null" />
                                </td>
                            </tr>

                            <tr id="{{ $linhaId }}" class="hidden">
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
                                                            Detalhes das notificações
                                                        </h3>

                                                        <p class="text-xs text-gray-500 mt-1">
                                                            Forma de pagamento:
                                                            <span class="font-semibold text-gray-700">
                                                                {{ $tipoPagamento?->tipo_plano_fin ?? 'N/A' }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 self-start sm:self-auto">
                                                    <span class="text-xs text-gray-500">
                                                        Total de notificações
                                                    </span>

                                                    <span class="inline-flex items-center justify-center min-w-8 h-8 px-2 rounded-lg bg-orange-50 border border-orange-200 text-xs font-bold text-orange-700">
                                                        {{ $notificacoesForma->count() }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="px-5 py-4">
                                            <div class="flex items-center justify-between mb-3">
                                                <div>
                                                    <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wide">
                                                        Parcelas notificadas
                                                    </h4>

                                                    <p class="text-xs text-gray-500 mt-1">
                                                        Consulte os dados das parcelas e suas notificações.
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
                                                                    Notificações
                                                                </th>
                                                                <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                                                                    Ações
                                                                </th>
                                                            </tr>
                                                        </thead>

                                                        <tbody class="divide-y divide-gray-100 bg-white">
                                                            @foreach($parcelas as $idParcela => $notificacoesParcela)
                                                            @php
                                                            $detalheParcela = $notificacoesParcela->first()->detalheFormaPag;

                                                            $tipoStatus = match ($detalheParcela?->det_situacao) {
                                                            'Pago', 'Quitado' => 'green',
                                                            'Não Pago' => 'yellow',
                                                            'Acordo' => 'blue',
                                                            'Inadimplencia', 'Inadimplência', 'Atrasado' => 'red',
                                                            default => 'gray',
                                                            };
                                                            @endphp

                                                            <tr class="hover:bg-orange-50/30 transition">
                                                                <td class="px-4 py-4 whitespace-nowrap">
                                                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-md bg-gray-100 border border-gray-200 text-xs font-bold text-gray-700">
                                                                        #{{ $idParcela ?? 'N/A' }}
                                                                    </span>
                                                                </td>

                                                                <td class="px-4 py-4 text-right whitespace-nowrap">
                                                                    <span class="text-sm text-gray-700">
                                                                        R$ {{ number_format($detalheParcela?->det_forma_valor_original ?? 0, 2, ',', '.') }}
                                                                    </span>
                                                                </td>

                                                                <td class="px-4 py-4 text-right whitespace-nowrap">
                                                                    <span class="text-sm font-bold text-gray-800">
                                                                        R$ {{ number_format($detalheParcela?->det_forma_valor_parcela ?? 0, 2, ',', '.') }}
                                                                    </span>
                                                                </td>

                                                                <td class="px-4 py-4 whitespace-nowrap">
                                                                    <span class="text-sm text-gray-700">
                                                                        {{ $detalheParcela?->det_forma_data_venc
                                                                                ? \Carbon\Carbon::parse($detalheParcela->det_forma_data_venc)->format('d/m/Y')
                                                                                : 'N/D' }}
                                                                    </span>
                                                                </td>

                                                                <td class="px-4 py-4 whitespace-nowrap">
                                                                    <x-badge
                                                                        :type="$tipoStatus"
                                                                        :text="$detalheParcela?->det_situacao ?? 'N/D'" />
                                                                </td>

                                                                <td class="px-4 py-4 text-center whitespace-nowrap">
                                                                    <x-badge
                                                                        type="orange"
                                                                        :text="$notificacoesParcela->count() . ' ' . ($notificacoesParcela->count() == 1 ? 'notificação' : 'notificações')"
                                                                        :dot="false" />
                                                                </td>

                                                                <td class="px-4 py-4 text-center">
                                                                    <div class="flex justify-center items-center gap-2 flex-wrap">
                                                                        <x-table-actions
                                                                            :notificacao-route="route('notificacao.create', [
                                                                                'id_det_forma' => CryptHelper::encrypt($idParcela),
                                                                                'id_financeiro' => request('id_financeiro'),
                                                                                'id_orcamento' => request('id_orcamento'),
                                                                                'cliente' => request('cliente'),
                                                                                'forma_pagamento' => request('forma_pagamento'),
                                                                                'page' => request('page'),
                                                                            ])"
                                                                            notificacao-text="Cadastrar" />

                                                                        <x-table-actions
                                                                            :show-route="route('notificacao.show', [
                                                                                'id' => CryptHelper::encrypt($idParcela),
                                                                                'id_financeiro' => request('id_financeiro'),
                                                                                'id_orcamento' => request('id_orcamento'),
                                                                                'cliente' => request('cliente'),
                                                                                'forma_pagamento' => request('forma_pagamento'),
                                                                                'page' => request('page'),
                                                                            ])"
                                                                            show-text="Ver notificações" />
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$notificacoes" />
            </div>
            @endif
        </div>
    </div>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const STORAGE_KEY = 'notificacao_estado';

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

        document.querySelectorAll('a[href^="#notificacoes-"]').forEach(function(btn) {

            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const href = this.getAttribute('href');

                if (!href || !href.startsWith('#notificacoes-')) {
                    return;
                }

                const id = href.replace('#notificacoes-', '');

                document.querySelectorAll('[id^="notificacoes-"]').forEach(function(row) {
                    if (row.id !== 'notificacoes-' + id) {
                        row.classList.add('hidden');
                    }
                });

                const row = document.getElementById('notificacoes-' + id);

                if (!row) {
                    return;
                }

                const estavaAberto = !row.classList.contains('hidden');

                row.classList.toggle('hidden');

                if (!estavaAberto) {
                    salvarEstado(id);
                } else {
                    limparEstado();
                }
            });

        });

        /*
         * Mantém o estado salvo quando o usuário clicar
         * em uma ação dentro dos detalhes, como Cadastrar.
         */
        document.querySelectorAll('[id^="notificacoes-"] a').forEach(function(link) {

            link.addEventListener('click', function() {

                const row = this.closest('[id^="notificacoes-"]');

                if (!row || row.classList.contains('hidden')) {
                    return;
                }

                const id = row.id.replace('notificacoes-', '');

                salvarEstado(id);
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

            const row = document.getElementById(
                'notificacoes-' + estado.detalhes
            );

            if (!row) {
                limparEstado();
                return;
            }

            document.querySelectorAll('[id^="notificacoes-"]').forEach(function(rowItem) {
                rowItem.classList.add('hidden');
            });

            row.classList.remove('hidden');

            requestAnimationFrame(function() {
                requestAnimationFrame(function() {

                    window.scrollTo({
                        top: estado.scroll,
                        behavior: 'instant'
                    });

                    limparEstado();
                });
            });

        } catch (e) {
            limparEstado();
        }
    });
</script>
@endpush
@endsection