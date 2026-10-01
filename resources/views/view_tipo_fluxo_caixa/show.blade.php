@extends('layouts.app')

@section('title', 'Detalhes do Tipo de Fluxo de Caixa')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Detalhes do Tipo de Fluxo de Caixa" :back-url="$urlVoltar">
            <x-header-action href="{{ route('tipo_fluxo_caixa.edit', CryptHelper::encrypt($tipoFluxo->id_tipo_fluxo)) }}">
                Editar tipo
            </x-header-action>
        </x-page-header>

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
                        <h2 class="text-lg font-bold text-gray-800">Informações do tipo de fluxo</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Confira os dados cadastrados do tipo de fluxo de caixa.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Nome</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $tipoFluxo->tipo_flu_nome }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Despesa</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $tipoFluxo->tipo_despesa === 'Variavel' ? 'Variável' : $tipoFluxo->tipo_despesa }}
                        </p>
                    </div>

                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Descrição</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $tipoFluxo->tipo_desc ?? '—' }}</p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
            </div>
        </div>
    </div>
</div>
@endsection