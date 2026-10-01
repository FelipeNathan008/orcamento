@extends('layouts.app')

@section('title', 'Parcelas Atrasadas / Inadimplentes')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Parcelas Atrasadas / Inadimplentes" :back-url="route('dashboard')" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('dashboard.parcelas.financeiro') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div>
                            <label for="id_det_forma" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                ID Parcela
                            </label>
                            <input type="number" id="id_det_forma" name="id_det_forma"
                                value="{{ request('id_det_forma') }}" placeholder="Ex.: 125"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="id_fin" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                ID Financeiro
                            </label>
                            <input type="number" id="id_fin" name="id_fin"
                                value="{{ request('id_fin') }}" placeholder="Ex.: 45"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="id_orcamento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                ID Orçamento
                            </label>
                            <input type="number" id="id_orcamento" name="id_orcamento"
                                value="{{ request('id_orcamento') }}" placeholder="Ex.: 80"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="situacao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Situação
                            </label>
                            <select id="situacao" name="situacao"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                <option value="Não Pago" {{ request('situacao') === 'Não Pago' ? 'selected' : '' }}>Não Pago</option>
                                <option value="Inadimplencia" {{ request('situacao') === 'Inadimplencia' ? 'selected' : '' }}>Inadimplência</option>
                                <option value="Atrasado" {{ request('situacao') === 'Atrasado' ? 'selected' : '' }}>Atrasado</option>
                            </select>
                        </div>

                        <div>
                            <label for="data_vencimento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Vencimento
                            </label>
                            <input type="date" id="data_vencimento" name="data_vencimento"
                                value="{{ request('data_vencimento') }}"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div class="flex items-end">
                            <x-primary-button class="w-full h-11">
                                <x-icons.search />
                                Buscar
                            </x-primary-button>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('dashboard.parcelas.financeiro') }}"
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
                <h2 class="text-lg font-bold text-gray-800">Lista de parcelas</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $parcelas->total() }} parcela(s)</span>
                </div>
            </div>

            @if ($parcelas->isEmpty())
            <x-empty-state
                title="Nenhuma parcela encontrada"
                message="Não existem parcelas correspondentes aos filtros informados."
                route="dashboard.parcelas.financeiro"
                button-text="Limpar filtros" />
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Parcela</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Referências</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Tipo</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Valor</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Vencimento</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Situação</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($parcelas as $parcela)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                            <x-icons.document />
                                        </div>
                                        <span class="text-sm font-bold text-gray-800">#{{ $parcela->id_det_forma }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="space-y-1.5">
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="w-8 text-[10px] font-bold text-gray-400">FIN.</span>
                                            <span class="font-semibold text-gray-700">
                                                {{ $parcela->formaPagamento?->financeiro_id_fin ?? 'N/D' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2 text-xs">
                                            <span class="w-8 text-[10px] font-bold text-gray-400">ORÇ.</span>
                                            <span class="font-semibold text-gray-700">
                                                {{ $parcela->formaPagamento?->financeiro?->orcamento_id_orcamento ?? 'N/D' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="inline-flex px-2.5 py-1 rounded-md bg-gray-100 text-gray-600 text-xs font-medium">
                                        {{ $parcela->formaPagamento?->tipoPagamento?->tipo_plano_fin ?? 'N/D' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">
                                        R$ {{ number_format($parcela->det_forma_valor_parcela, 2, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <x-icons.calendar class="w-4 h-4 text-gray-400" />
                                        {{ $parcela->det_forma_data_venc ? \Carbon\Carbon::parse($parcela->det_forma_data_venc)->format('d/m/Y') : 'N/D' }}
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <x-status-badge :status="$parcela->det_situacao" />
                                </td>

                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    @if ($parcela->formaPagamento?->financeiro?->id_fin)
                                    <a href="{{ route('forma_pagamento.index', ['id_fin' => $parcela->formaPagamento->financeiro->id_fin]) }}"
                                        class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-white shadow-sm hover:shadow-md hover:brightness-95 transition"
                                        style="background-color:#EA792D;">
                                        Detalhes
                                        <x-icons.arrow-right />
                                    </a>
                                    @else
                                    <span class="text-xs text-gray-400">Indisponível</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$parcelas" />
            </div>
            @endif
        </div>
    </div>
</div>
@endsection