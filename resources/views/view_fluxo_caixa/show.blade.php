@extends('layouts.app')

@section('title', 'Detalhes do Fluxo de Caixa')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Detalhes do Fluxo de Caixa" :back-url="route('fluxo_caixa.index')" />
        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>
        <div class="px-6 sm:px-8 pt-6 pb-8">
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Informações do lançamento</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Confira os dados cadastrados deste fluxo de caixa.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Data da despesa</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($fluxo->flu_data_despesa)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Tipo</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $fluxo->tipo->tipo_flu_nome ?? 'Não informado' }}</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Movimentação</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $fluxo->movimentacao->mov_nome ?? 'Não informado' }}</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Tipo Fiscal</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $fluxo->flu_tipo_fiscal ?: 'Não informado' }}</p>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-4 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Conta Bancária</p>
                        <p class="text-sm font-semibold text-gray-800">
                            @if($fluxo->conta)
                                {{ $fluxo->conta->conta_nome_banco }}
                                @if($fluxo->conta->numero_conta_corrente)
                                    - {{ $fluxo->conta->numero_conta_corrente }}
                                @endif
                            @else
                                Não informado
                            @endif
                        </p>
                    </div>
                    @php
                        $movNome = $fluxo->movimentacao->mov_nome ?? '';
                        $valorTipo = str_contains(mb_strtolower($movNome), 'entrada') ? 'text-green-600' : 'text-red-600';
                    @endphp
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Valor</p>
                        <p class="text-base font-bold {{ $valorTipo }}">
                            R$ {{ number_format($fluxo->flu_valor, 2, ',', '.') }}
                        </p>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-3 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Número do Documento</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $fluxo->flu_num_doc ?: 'Não informado' }}</p>
                    </div>
                    <div class="sm:col-span-2 lg:col-span-4 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Descrição</p>
                        <p class="text-sm font-semibold text-gray-800 whitespace-pre-line">{{ $fluxo->flu_desc ?: 'Não informado' }}</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="route('fluxo_caixa.index')">
                    Voltar para a lista
                </x-secondary-button>
            </div>
        </div>
    </div>
</div>
@endsection