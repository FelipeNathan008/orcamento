@extends('layouts.app')

@section('title', 'Editar Conta Bancária')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Editar Conta Bancária" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="contaBancariaForm" action="{{ route('conta_bancaria.update', CryptHelper::encrypt($conta->id_conta)) }}" method="POST" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            @method('PUT')

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados da conta bancária</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Atualize as informações da conta bancária.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="conta_nome_banco" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Nome do Banco
                        </label>
                        <input type="text" name="conta_nome_banco" id="conta_nome_banco"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            value="{{ old('conta_nome_banco', $conta->conta_nome_banco) }}" maxlength="200" placeholder="Itaú" required>
                    </div>

                    <div>
                        <label for="conta_cod_banco" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Código do Banco
                        </label>
                        <input type="text" name="conta_cod_banco" id="conta_cod_banco"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            value="{{ old('conta_cod_banco', $conta->conta_cod_banco) }}" maxlength="10" placeholder="341" required>
                    </div>

                    <div>
                        <label for="conta_agencia" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Agência
                        </label>
                        <input type="text" name="conta_agencia" id="conta_agencia"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            value="{{ old('conta_agencia', $conta->conta_agencia) }}" maxlength="50" placeholder="1234" required>
                    </div>

                    <div>
                        <label for="numero_conta_corrente" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Conta Corrente
                        </label>
                        <input type="text" name="numero_conta_corrente" id="numero_conta_corrente"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            value="{{ old('numero_conta_corrente', $conta->numero_conta_corrente) }}" maxlength="100" placeholder="xxxxxxxx" required>
                    </div>

                    <div>
                        <label for="numero_digito_corrente" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Dígito
                        </label>
                        <input type="text" name="numero_digito_corrente" id="numero_digito_corrente"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            value="{{ old('numero_digito_corrente', $conta->numero_digito_corrente) }}" maxlength="90" placeholder="y" required>
                    </div>

                    <div class="md:col-span-2">
                        <label for="conta_desc" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Descrição
                        </label>
                        <textarea name="conta_desc" id="conta_desc" rows="4"
                            class="w-full px-3 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none"
                            maxlength="255" placeholder="Cartão exclusivo para Alphamega">{{ old('conta_desc', $conta->conta_desc) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>

                <x-primary-button type="submit" id="btnAtualizarContaBancaria" class="px-6">
                    <span id="textoAtualizar">Atualizar conta</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const form = document.getElementById('contaBancariaForm');
    const btnAtualizar = document.getElementById('btnAtualizarContaBancaria');
    const textoAtualizar = document.getElementById('textoAtualizar');

    form.addEventListener('submit', function() {
        if (btnAtualizar.disabled) {
            return false;
        }

        btnAtualizar.disabled = true;
        textoAtualizar.innerText = 'Atualizando...';
        btnAtualizar.classList.add('opacity-70', 'cursor-not-allowed');
    });
</script>
@endpush
@endsection