@extends('layouts.app')

@section('title', 'Detalhes do Contato')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header title="Detalhes do Contato" :back-url="$urlVoltar">
            <x-header-action href="{{ route('contato_cliente.edit', CryptHelper::encrypt($contatoCliente->id_contato)) }}">
                Editar contato
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        @php
        $celularCliente = preg_replace('/\D/', '', $contatoCliente->clienteOrcamento->clie_orc_celular ?? '');

        if (strlen($celularCliente) === 11) {
        $celularClienteFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celularCliente);
        } elseif (strlen($celularCliente) === 10) {
        $celularClienteFormatado = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $celularCliente);
        } else {
        $celularClienteFormatado = $contatoCliente->clienteOrcamento->clie_orc_celular ?: 'Não informado';
        }

        $telefone = preg_replace('/\D/', '', $contatoCliente->cont_telefone ?? '');
        $celular = preg_replace('/\D/', '', $contatoCliente->cont_celular ?? '');

        if (strlen($telefone) === 10) {
        $telefoneFormatado = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone);
        } elseif (strlen($telefone) === 11) {
        $telefoneFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone);
        } else {
        $telefoneFormatado = $contatoCliente->cont_telefone ?: 'Não informado';
        }

        if (strlen($celular) === 10) {
        $celularFormatado = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $celular);
        } elseif (strlen($celular) === 11) {
        $celularFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celular);
        } else {
        $celularFormatado = $contatoCliente->cont_celular ?: 'Não informado';
        }

        $tipo = [
        'administrativo' => 'purple',
        'comercial' => 'yellow',
        'financeiro' => 'blue',
        'rh' => 'pink',
        'compras' => 'green',
        'socio' => 'red',
        ][$contatoCliente->cont_tipo] ?? 'gray';
        @endphp

        <div class="px-6 sm:px-8 pt-6 pb-8">

            <x-info-card
                title="Cliente selecionado"
                :name="$contatoCliente->clienteOrcamento->clie_orc_nome"
                type="Cliente"
                :fields="[
                    [
                        'label' => 'Código interno',
                        'value' => $contatoCliente->clienteOrcamento->clie_orc_cod_interno ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'E-mail',
                        'value' => $contatoCliente->clienteOrcamento->clie_orc_email ?: 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Celular',
                        'value' => $celularClienteFormatado,
                    ],
                ]" />

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">

                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Informações do contato</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Confira os dados cadastrados do contato.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Nome do Contato
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $contatoCliente->cont_nome }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Celular
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $celularFormatado }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Telefone
                        </p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $telefoneFormatado }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            E-mail
                        </p>
                        <p class="text-sm font-semibold text-gray-800 break-all">
                            {{ $contatoCliente->cont_email ?: 'Não informado' }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-2">
                            Tipo de Contato
                        </p>
                        <x-badge :type="$tipo" :text="ucfirst($contatoCliente->cont_tipo)" />
                    </div>

                    @if ($contatoCliente->cont_descricao)
                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">
                            Descrição
                        </p>
                        <p class="text-sm font-semibold text-gray-800 whitespace-pre-line">
                            {{ $contatoCliente->cont_descricao }}
                        </p>
                    </div>
                    @endif

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