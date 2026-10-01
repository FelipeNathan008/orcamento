@extends('layouts.app')

@section('title', 'Editar Tipo de Pagamento')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Editar Tipo de Pagamento" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="tipoPagamentoForm" action="{{ route('tipo_pagamento.update', CryptHelper::encrypt($tipo->id_tipo_pagamento)) }}" method="POST" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            @method('PUT')

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do tipo de pagamento</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Atualize as informações cadastrais do tipo de pagamento.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="tipo_plano_fin" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Tipo de Pagamento
                        </label>
                        <input
                            type="text"
                            name="tipo_plano_fin"
                            id="tipo_plano_fin"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Ex: Boleto, Pix..."
                            maxlength="45"
                            value="{{ old('tipo_plano_fin', $tipo->tipo_plano_fin) }}"
                            required>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="btnSalvarTipoPagamento" class="px-6">
                    <span id="textoSalvar">Atualizar tipo</span>
                </x-primary-button>
            </div>
        </form>
    </div>

</div>

@push('scripts')

<script>
    const form = document.getElementById('tipoPagamentoForm');
    const btnSalvar = document.getElementById('btnSalvarTipoPagamento');
    const textoSalvar = document.getElementById('textoSalvar');

    form.addEventListener('submit', function(event) {
        if (btnSalvar.disabled) {
            event.preventDefault();
            return;
        }
        btnSalvar.disabled = true;
        textoSalvar.innerText = 'Atualizando...';
        btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
    });
</script>

@endpush
@endsection