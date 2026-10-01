@extends('layouts.app')

@section('title', 'Editar Tipo de Fluxo de Caixa')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Editar Tipo de Fluxo de Caixa" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="tipoFluxoCaixaForm" action="{{ route('tipo_fluxo_caixa.update', CryptHelper::encrypt($tipoFluxo->id_tipo_fluxo)) }}" method="POST" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            @method('PUT')

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do tipo de fluxo</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Atualize as informações cadastrais do tipo de fluxo de caixa.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="tipo_flu_nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Nome
                        </label>
                        <input
                            type="text"
                            name="tipo_flu_nome"
                            id="tipo_flu_nome"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Ex: Papelaria"
                            maxlength="120"
                            value="{{ old('tipo_flu_nome', $tipoFluxo->tipo_flu_nome) }}"
                            required>
                    </div>

                    <div>
                        <label for="tipo_despesa" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Despesa
                        </label>
                        <select
                            name="tipo_despesa"
                            id="tipo_despesa"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            required>
                            <option value="">Selecione a despesa</option>
                            <option value="Fixa" {{ old('tipo_despesa', $tipoFluxo->tipo_despesa) == 'Fixa' ? 'selected' : '' }}>Fixa</option>
                            <option value="Variavel" {{ old('tipo_despesa', $tipoFluxo->tipo_despesa) == 'Variavel' ? 'selected' : '' }}>Variável</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label for="tipo_desc" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Descrição
                        </label>
                        <textarea
                            name="tipo_desc"
                            id="tipo_desc"
                            rows="4"
                            maxlength="180"
                            class="w-full px-3 py-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition resize-none focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Informe uma descrição para o tipo de fluxo de caixa"
                            required>{{ old('tipo_desc', $tipoFluxo->tipo_desc) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="btnSalvarTipoFluxoCaixa" class="px-6">
                    <span id="textoSalvar">Atualizar tipo</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const form = document.getElementById('tipoFluxoCaixaForm');
    const btnSalvar = document.getElementById('btnSalvarTipoFluxoCaixa');
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