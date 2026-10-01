@extends('layouts.app')

@section('title', 'Editar Contato')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header title="Editar Contato" :back-url="$urlVoltar" />

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
        @endphp

        <form
            id="contatoForm"
            action="{{ route('contato_cliente.update', CryptHelper::encrypt($contatoCliente->id_contato)) }}"
            method="POST"
            class="px-6 sm:px-8 pt-6 pb-8">

            @csrf
            @method('PUT')

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

            <input
                type="hidden"
                name="cliente_orcamento_id_co"
                value="{{ $contatoCliente->cliente_orcamento_id_co }}">

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do contato</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Atualize as informações do contato do cliente.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="cont_nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Nome do Contato
                        </label>

                        <input
                            type="text"
                            name="cont_nome"
                            id="cont_nome"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Nome completo do contato"
                            maxlength="45"
                            value="{{ old('cont_nome', $contatoCliente->cont_nome) }}"
                            required>
                    </div>

                    <div>
                        <label for="cont_celular" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Celular
                        </label>

                        <input
                            type="text"
                            name="cont_celular"
                            id="cont_celular"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="(XX) XXXXX-XXXX"
                            maxlength="15"
                            value="{{ old('cont_celular', $contatoCliente->cont_celular) }}"
                            required>
                    </div>

                    <div>
                        <label for="cont_telefone" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Telefone
                        </label>

                        <input
                            type="text"
                            name="cont_telefone"
                            id="cont_telefone"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="(XX) XXXX-XXXX"
                            maxlength="14"
                            value="{{ old('cont_telefone', $contatoCliente->cont_telefone) }}">
                    </div>

                    <div>
                        <label for="cont_email" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            E-mail
                        </label>

                        <input
                            type="email"
                            name="cont_email"
                            id="cont_email"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="contato@exemplo.com"
                            maxlength="45"
                            value="{{ old('cont_email', $contatoCliente->cont_email) }}"
                            required>
                    </div>

                    <div>
                        <label for="cont_tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Tipo de Contato
                        </label>

                        <select
                            name="cont_tipo"
                            id="cont_tipo"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            required>

                            <option value="">Selecione</option>

                            <option value="administrativo" {{ old('cont_tipo', $contatoCliente->cont_tipo) === 'administrativo' ? 'selected' : '' }}>
                                Administrativo
                            </option>

                            <option value="comercial" {{ old('cont_tipo', $contatoCliente->cont_tipo) === 'comercial' ? 'selected' : '' }}>
                                Comercial
                            </option>

                            <option value="financeiro" {{ old('cont_tipo', $contatoCliente->cont_tipo) === 'financeiro' ? 'selected' : '' }}>
                                Financeiro
                            </option>

                            <option value="rh" {{ old('cont_tipo', $contatoCliente->cont_tipo) === 'rh' ? 'selected' : '' }}>
                                RH
                            </option>

                            <option value="compras" {{ old('cont_tipo', $contatoCliente->cont_tipo) === 'compras' ? 'selected' : '' }}>
                                Compras
                            </option>

                            <option value="socio" {{ old('cont_tipo', $contatoCliente->cont_tipo) === 'socio' ? 'selected' : '' }}>
                                Sócio
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="cont_descricao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Descrição
                        </label>

                        <textarea
                            name="cont_descricao"
                            id="cont_descricao"
                            rows="4"
                            maxlength="500"
                            class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none"
                            placeholder="Informações adicionais sobre o contato, como cargo ou setor">{{ old('cont_descricao', $contatoCliente->cont_descricao) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>

                <x-primary-button type="submit" id="btnSalvarContato" class="px-6">
                    <span id="textoSalvar">Atualizar contato</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    const form = document.getElementById('contatoForm');
    const btnSalvar = document.getElementById('btnSalvarContato');
    const textoSalvar = document.getElementById('textoSalvar');

    form.addEventListener('submit', function() {
        if (btnSalvar.disabled) return false;

        btnSalvar.disabled = true;
        textoSalvar.innerText = 'Atualizando...';
        btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
    });

    $(document).ready(function() {
        $('#contatoForm input[name="cont_telefone"]').mask('(00) 0000-0000');
        $('#contatoForm input[name="cont_celular"]').mask('(00) 00000-0000');
    });
</script>
@endpush

@endsection