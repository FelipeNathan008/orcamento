@extends('layouts.app')

@section('title', 'Editar Grupo Principal')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Editar Grupo Principal" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form
            id="grupoPrincipalForm"
            action="{{ route('tipo_grupo_principal.update', ['tipo_grupo_principal' => CryptHelper::encrypt($grupoPrincipal->id_grupo_principal)]) }}"
            method="POST"
            class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            @method('PUT')

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do grupo principal</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Atualize o nome do grupo de classificação financeira.
                        </p>
                    </div>
                </div>

                <div>
                    <label for="grupo_principal_nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                        Nome do grupo principal
                    </label>

                    <input
                        type="text"
                        name="grupo_principal_nome"
                        id="grupo_principal_nome"
                        class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                        placeholder="Nome do grupo principal..."
                        maxlength="255"
                        value="{{ old('grupo_principal_nome', $grupoPrincipal->grupo_principal_nome) }}"
                        required>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>

                <x-primary-button type="submit" id="btnSalvarGrupo" class="px-6">
                    <span id="textoSalvar">Salvar alterações</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const form = document.getElementById('grupoPrincipalForm');
    const btnSalvar = document.getElementById('btnSalvarGrupo');
    const textoSalvar = document.getElementById('textoSalvar');

    form.addEventListener('submit', function(event) {
        if (btnSalvar.disabled) {
            event.preventDefault();
            return;
        }

        btnSalvar.disabled = true;
        textoSalvar.innerText = 'Salvando...';
        btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
    });
</script>
@endpush
@endsection