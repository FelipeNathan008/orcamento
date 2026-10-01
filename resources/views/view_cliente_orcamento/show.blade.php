@extends('layouts.app')

@section('title', 'Detalhes do Cliente de Orçamento')

@section('content')

@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header title="Detalhes do Cliente de Orçamento" :back-url="$urlVoltar">
            <x-header-action href="{{ route('cliente_orcamento.edit', CryptHelper::encrypt($clienteOrcamento->id_co)) }}">
                Editar cliente
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
                        <h2 class="text-lg font-bold text-gray-800">Informações do cliente</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Confira os dados cadastrais do cliente de orçamento.
                        </p>
                    </div>
                </div>

                @php
                $telefone = preg_replace('/\D/', '', $clienteOrcamento->clie_orc_telefone ?? '');

                if (strlen($telefone) === 10) {
                $telefoneFormatado = preg_replace(
                '/(\d{2})(\d{4})(\d{4})/',
                '($1) $2-$3',
                $telefone
                );
                } else {
                $telefoneFormatado = $clienteOrcamento->clie_orc_telefone;
                }

                $celular = preg_replace('/\D/', '', $clienteOrcamento->clie_orc_celular ?? '');

                if (strlen($celular) === 11) {
                $celularFormatado = preg_replace(
                '/(\d{2})(\d{5})(\d{4})/',
                '($1) $2-$3',
                $celular
                );
                } elseif (strlen($celular) === 10) {
                $celularFormatado = preg_replace(
                '/(\d{2})(\d{4})(\d{4})/',
                '($1) $2-$3',
                $celular
                );
                } else {
                $celularFormatado = $clienteOrcamento->clie_orc_celular;
                }

                $cep = preg_replace('/\D/', '', $clienteOrcamento->clie_orc_cep ?? '');

                $cepFormatado = preg_replace(
                '/(\d{5})(\d{3})/',
                '$1-$2',
                $cep
                );

                $cpf = preg_replace('/\D/', '', $clienteOrcamento->clie_orc_cpf ?? '');

                $cpfFormatado = preg_replace(
                '/(\d{3})(\d{3})(\d{3})(\d{2})/',
                '$1.$2.$3-$4',
                $cpf
                );

                $cnpj = preg_replace('/\D/', '', $clienteOrcamento->clie_orc_cnpj ?? '');

                $cnpjFormatado = preg_replace(
                '/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/',
                '$1.$2.$3/$4-$5',
                $cnpj
                );
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Nome
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $clienteOrcamento->clie_orc_nome }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            E-mail
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $clienteOrcamento->clie_orc_email }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Código interno
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $clienteOrcamento->clie_orc_cod_interno }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Telefone
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $telefoneFormatado ?: 'Não informado' }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Celular
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $celularFormatado ?: 'Não informado' }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Tipo de documento
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $clienteOrcamento->clie_orc_tipo_doc }}
                        </p>
                    </div>

                    @if ($clienteOrcamento->clie_orc_tipo_doc === 'CPF' && $clienteOrcamento->clie_orc_cpf)
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            CPF
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $cpfFormatado }}
                        </p>
                    </div>
                    @elseif ($clienteOrcamento->clie_orc_tipo_doc === 'CNPJ' && $clienteOrcamento->clie_orc_cnpj)
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            CNPJ
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $cnpjFormatado }}
                        </p>
                    </div>
                    @elseif ($clienteOrcamento->clie_orc_tipo_doc === 'RG' && $clienteOrcamento->clie_orc_rg)
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            RG
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $clienteOrcamento->clie_orc_rg }}
                        </p>
                    </div>
                    @endif

                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Logradouro
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $clienteOrcamento->clie_orc_logradouro }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Bairro
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $clienteOrcamento->clie_orc_bairro }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            CEP
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $cepFormatado }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Cidade
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $clienteOrcamento->clie_orc_cidade }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            UF
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $clienteOrcamento->clie_orc_uf }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Inscrição Estadual
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $clienteOrcamento->clie_orc_ie }}
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