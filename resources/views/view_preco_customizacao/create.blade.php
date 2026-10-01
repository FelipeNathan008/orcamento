@extends('layouts.app')

@section('title', 'Cadastrar Novo Preço de Customização')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header title="Cadastrar Novo Preço de Customização" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="precoCustomizacaoForm" action="{{ route('preco_customizacao.store') }}" method="POST" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">

                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do preço</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Preencha as informações do preço de customização.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label for="preco_tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Tipo
                        </label>

                        <input type="text"
                            name="preco_tipo"
                            id="preco_tipo"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Ex: Estampa DTF"
                            maxlength="45"
                            value="{{ old('preco_tipo') }}"
                            required>

                    </div>

                    <div>
                        <label for="preco_tamanho" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Tamanho
                        </label>

                        <input type="text"
                            name="preco_tamanho"
                            id="preco_tamanho"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Ex: Pequeno (até 9cm)"
                            maxlength="30"
                            value="{{ old('preco_tamanho') }}"
                            required>

                    </div>

                    <div>
                        <label for="preco_valor" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Valor
                        </label>

                        <input type="text"
                            name="preco_valor"
                            id="preco_valor"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            value="{{ old('preco_valor') }}"
                            placeholder="Ex: R$ 50,00"
                            required>

                    </div>

                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">

                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>

                <x-primary-button type="submit" id="btnSalvarPrecoCustomizacao" class="px-6">
                    <span id="textoSalvar">Salvar preço</span>
                </x-primary-button>

            </div>
        </form>
    </div>

</div>

@push('scripts')

<script>
    const form = document.getElementById('precoCustomizacaoForm');
    const btnSalvar = document.getElementById('btnSalvarPrecoCustomizacao');
    const textoSalvar = document.getElementById('textoSalvar');

    form.addEventListener('submit', function() {
        if (btnSalvar.disabled) {
            return false;
        }

        btnSalvar.disabled = true;
        textoSalvar.innerText = 'Salvando...';
        btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
    });

    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('preco_valor');

        input.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');

            if (value === '') {
                this.value = '';
                return;
            }

            value = (parseFloat(value) / 100).toFixed(2);
            value = value.replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            this.value = 'R$ ' + value;
        });
    });
</script>

@endpush
@endsection