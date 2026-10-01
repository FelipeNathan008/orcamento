@extends('layouts.app')

@section('title', 'Detalhes da Nota Fiscal')

@section('content')

@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Detalhes da Nota Fiscal" :back-url="route('nota_fiscal.index')">
            <x-header-action href="{{ route('nota_fiscal.edit', CryptHelper::encrypt($nota->id_nota_fiscal)) }}">
                Editar nota
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
                        <h2 class="text-lg font-bold text-gray-800">Informações da nota fiscal</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Confira os dados cadastrados desta nota fiscal.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Data</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ \Carbon\Carbon::parse($nota->nota_data)->format('d/m/Y') }}
                        </p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Número da Nota</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $nota->nota_numero }}</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Orçamento</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $nota->orcamento_id_orcamento }}</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Tipo</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $nota->tipo->tipo_flu_nome ?? 'Não informado' }}</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Movimentação</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $nota->movimentacao->mov_nome ?? 'Não informado' }}</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Valor</p>
                        <p class="text-sm font-semibold text-gray-800">R$ {{ number_format($nota->nota_valor, 2, ',', '.') }}</p>
                    </div>
                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Descrição</p>
                        <p class="text-sm font-semibold text-gray-800 whitespace-pre-line">{{ $nota->nota_desc ?: 'Não informado' }}</p>
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="route('nota_fiscal.index')">
                    Voltar para a lista
                </x-secondary-button>
            </div>
        </div>
    </div>
</div>
@endsection