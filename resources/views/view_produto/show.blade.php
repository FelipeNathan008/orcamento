@extends('layouts.app')

@section('title', 'Detalhes do Produto')

@section('content')

@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Detalhes do Produto" :back-url="$urlVoltar">
            <x-header-action href="{{ route('produto.edit', CryptHelper::encrypt($produto->id_produto)) }}">
                Editar produto
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
                        <h2 class="text-lg font-bold text-gray-800">Informações do produto</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Confira os dados cadastrados do produto.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Código do Produto</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $produto->prod_cod }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">ID do Produto</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $produto->id_produto }}</p>
                    </div>

                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Nome do Produto</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $produto->prod_nome }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Família</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $produto->prod_familia }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Categoria</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $produto->prod_categoria }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Material</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $produto->prod_material }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Gênero</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $produto->prod_genero }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Modelo</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $produto->prod_modelo }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Características</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $produto->prod_caract }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Cor</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $produto->prod_cor }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Tamanhos</p>
                        <p class="text-sm font-semibold text-gray-800">
                            @if(is_array($produto->prod_tamanho))
                            {{ implode(', ', $produto->prod_tamanho) }}
                            @else
                            {{ $produto->prod_tamanho }}
                            @endif
                        </p>
                    </div>

                    <div class="bg-white border border-orange-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-[#EA792D] uppercase tracking-wide mb-1">Preço</p>
                        <p class="text-base font-bold text-[#EA792D]">R$ {{ number_format($produto->prod_preco , 2, ',', '.') }}</p>
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