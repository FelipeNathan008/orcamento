@extends('layouts.app')

@section('title', 'Detalhes da Empresa')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header title="Detalhes da Empresa" :back-url="$urlVoltar">
            <x-header-action href="{{ route('empresa.edit', CryptHelper::encrypt($empresa->id_emp)) }}">
                Editar empresa
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
                        <h2 class="text-lg font-bold text-gray-800">Informações da empresa</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Confira os dados cadastrais da empresa.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Nome da Empresa
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $empresa->emp_nome }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            CNPJ
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $empresa->emp_cnpj }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            CEP
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            @php
                            $cep = preg_replace('/\D/', '', $empresa->emp_cep ?? '');
                            @endphp

                            {{ preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep) }}
                        </p>
                    </div>

                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Logradouro
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $empresa->emp_logradouro }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Bairro
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $empresa->emp_bairro }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Cidade
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $empresa->emp_cidade }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            UF
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $empresa->emp_uf }}
                        </p>
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