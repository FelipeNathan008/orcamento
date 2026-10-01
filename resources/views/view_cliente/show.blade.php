@extends('layouts.app')

@section('title', 'Detalhes da Prospecção')

@section('content')

@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header title="Detalhes da Prospecção" :back-url="$urlVoltar">
            <x-header-action href="{{ route('cliente.edit', CryptHelper::encrypt($cliente->id_cliente)) }}">
                Editar prospecção
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
                        <h2 class="text-lg font-bold text-gray-800">
                            Informações da prospecção
                        </h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Confira os dados cadastrais da prospecção.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Nome
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $cliente->clie_nome }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            E-mail
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $cliente->clie_email }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Tipo de Documento
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $cliente->clie_tipo_doc }}
                        </p>
                    </div>

                    @if ($cliente->clie_tipo_doc === 'CPF' && $cliente->clie_cpf)
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            CPF
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            @php
                            $cpf = preg_replace('/\D/', '', $cliente->clie_cpf);
                            @endphp

                            @if (strlen($cpf) === 11)
                            {{ preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf) }}
                            @else
                            {{ $cliente->clie_cpf }}
                            @endif
                        </p>
                    </div>
                    @endif

                    @if ($cliente->clie_tipo_doc === 'CNPJ' && $cliente->clie_cnpj)
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            CNPJ
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            @php
                            $cnpj = preg_replace('/\D/', '', $cliente->clie_cnpj);
                            @endphp

                            @if (strlen($cnpj) === 14)
                            {{ preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj) }}
                            @else
                            {{ $cliente->clie_cnpj }}
                            @endif
                        </p>
                    </div>
                    @endif

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Telefone
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            @php
                            $telefone = preg_replace('/\D/', '', $cliente->clie_telefone ?? '');
                            @endphp

                            @if (strlen($telefone) === 10)
                            {{ preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone) }}
                            @elseif (strlen($telefone) === 11)
                            {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone) }}
                            @else
                            {{ $cliente->clie_telefone ?: 'Não informado' }}
                            @endif
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Celular
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            @php
                            $celular = preg_replace('/\D/', '', $cliente->clie_celular ?? '');
                            @endphp

                            @if (strlen($celular) === 11)
                            {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celular) }}
                            @elseif (strlen($celular) === 10)
                            {{ preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $celular) }}
                            @else
                            {{ $cliente->clie_celular ?: 'Não informado' }}
                            @endif
                        </p>
                    </div>

                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Logradouro
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $cliente->clie_logradouro }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Bairro
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $cliente->clie_bairro }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            CEP
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            @php
                            $cep = preg_replace('/\D/', '', $cliente->clie_cep ?? '');
                            @endphp

                            @if (strlen($cep) === 8)
                            {{ preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep) }}
                            @else
                            {{ $cliente->clie_cep ?: 'Não informado' }}
                            @endif
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Cidade
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $cliente->clie_cidade }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            UF
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $cliente->clie_uf }}
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