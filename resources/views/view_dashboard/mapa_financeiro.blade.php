@extends('layouts.app')

@section('title', 'Mapa Financeiro')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Mapa Financeiro" :back-url="route('dashboard')" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <x-filter-card>
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
                    <a href="{{ route('dashboard.mapa.financeiro', [
        'mes' => $dataMes->copy()->subMonth()->month,
        'ano' => $dataMes->copy()->subMonth()->year
    ]) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                        <span>←</span>
                        Mês anterior
                    </a>

                    <div class="text-center">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">
                            Mapa financeiro
                        </p>
                        <h2 class="text-2xl font-bold text-gray-800">
                            {{ $nomeMes }} de {{ $dataMes->year }}
                        </h2>
                        <a href="{{ route('dashboard.mapa.financeiro', ['mes' => now()->month,'ano' => now()->year]) }}"
                            class="inline-flex items-center justify-center gap-2 mt-2 px-4 py-2 text-sm font-semibold text-white rounded-lg shadow-sm hover:shadow-md hover:brightness-95 transition"
                            style="background-color:#EA792D;">
                            Hoje
                        </a>
                    </div>

                    <a href="{{ route('dashboard.mapa.financeiro', ['mes' => $dataMes->copy()->addMonth()->month,
                    'ano' => $dataMes->copy()->addMonth()->year]) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition">
                        Próximo mês
                        <span>→</span>
                    </a>
                </div>
            </x-filter-card>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
                    <p class="text-xs font-bold text-gray-600 uppercase tracking-wide">
                        Parcelas no mês
                    </p>
                    <p class="text-3xl font-bold text-orange-700 mt-1">
                        {{ $totalParcelas }}
                    </p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                    <p class="text-xs font-bold text-gray-600 uppercase tracking-wide">
                        Valor previsto no mês
                    </p>
                    <p class="text-3xl font-bold text-blue-700 mt-1">
                        R$ {{ number_format($valorTotal, 2, ',', '.') }}
                    </p>
                </div>
            </div>

            @if ($dias->isEmpty())
            <x-empty-state
                title="Nenhuma parcela prevista"
                message="Não existem parcelas a pagar neste mês."
                route="dashboard.mapa.financeiro"
                button-text="Atualizar mapa" />
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Dia</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Data</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Parcelas</th>
                                <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Valor</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($dias as $dia)
                            @php
                            $hoje = now()->startOfDay();
                            $dataDia = $dia['data']->copy()->startOfDay();
                            $ehHoje = $dataDia->equalTo($hoje);
                            @endphp

                            <tr class="group {{ $ehHoje ? 'bg-orange-50/50' : '' }} hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                                            <span class="text-sm font-bold">
                                                {{ str_pad($dia['dia'], 2, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </div>

                                        @if ($ehHoje)
                                        <span class="text-[10px] font-bold text-orange-600 uppercase">
                                            Hoje
                                        </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <x-icons.calendar class="w-4 h-4 text-gray-400" />
                                        {{ $dataDia->format('d/m/Y') }}
                                    </div>
                                </td>

                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <span class="inline-flex items-center justify-center min-w-8 px-2.5 py-1 rounded-full bg-orange-100 text-orange-700 text-xs font-bold">
                                        {{ $dia['quantidade'] }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-right whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">
                                        R$ {{ number_format($dia['valor'], 2, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <a href="{{ route('dashboard.mapa.financeiro.parcelas', [
                                        'data' => $dataDia->format('Y-m-d')
                                    ]) }}"
                                        class="inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-white shadow-sm hover:shadow-md hover:brightness-95 transition"
                                        style="background-color:#EA792D;">
                                        Ver parcelas
                                        <x-icons.arrow-right />
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection