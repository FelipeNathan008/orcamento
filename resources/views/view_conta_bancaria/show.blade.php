@extends('layouts.app')

@section('title', 'Detalhes da Conta Bancária')

@section('content')

@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Detalhes da Conta Bancária" :back-url="$urlVoltar">
            <x-header-action href="{{ route('conta_bancaria.edit', CryptHelper::encrypt($conta->id_conta)) }}">
                Editar conta
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
                        <h2 class="text-lg font-bold text-gray-800">Informações da conta bancária</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Confira os dados cadastrais da conta bancária.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Banco
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $conta->conta_nome_banco }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Código do Banco
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $conta->conta_cod_banco }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Agência
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $conta->conta_agencia }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Conta Corrente
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $conta->numero_conta_corrente }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Dígito
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $conta->numero_digito_corrente }}
                        </p>
                    </div>

                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Descrição
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $conta->conta_desc ?? 'N/A' }}
                        </p>
                    </div>

                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Saldo
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            R$ {{ number_format($conta->saldoConta->saldo_conta_valor ?? 0, 2, ',', '.') }}
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