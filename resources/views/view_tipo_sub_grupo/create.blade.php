@extends('layouts.app')

@section('title', 'Cadastrar Subgrupo')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Cadastrar Subgrupo" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="subGrupoForm" action="{{ route('tipo_sub_grupo.store') }}" method="POST" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do subgrupo</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Selecione o grupo principal e informe o nome do subgrupo.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="grupo_principal_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Grupo principal
                        </label>

                        <select
                            name="grupo_principal_id"
                            id="grupo_principal_id"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            required>
                            <option value="">Selecione um grupo principal</option>
                            @foreach ($gruposPrincipais as $grupo)
                            <option value="{{ $grupo->id_grupo_principal }}"
                                @selected(old('grupo_principal_id', $grupoPrincipalId ?? '' )==$grupo->id_grupo_principal)>
                                {{ $grupo->grupo_principal_nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sub_grupo_nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Nome do subgrupo
                        </label>

                        <input
                            type="text"
                            name="sub_grupo_nome"
                            id="sub_grupo_nome"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Ex.: Despesas operacionais..."
                            maxlength="255"
                            value="{{ old('sub_grupo_nome') }}"
                            required>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>

                <x-primary-button type="submit" id="btnSalvarSubGrupo" class="px-6">
                    <span id="textoSalvar">Salvar subgrupo</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const formSubGrupo = document.getElementById('subGrupoForm');
    const btnSalvarSubGrupo = document.getElementById('btnSalvarSubGrupo');
    const textoSalvarSubGrupo = document.getElementById('textoSalvar');

    formSubGrupo.addEventListener('submit', function(event) {
        if (btnSalvarSubGrupo.disabled) {
            event.preventDefault();
            return;
        }

        btnSalvarSubGrupo.disabled = true;
        textoSalvarSubGrupo.innerText = 'Salvando...';
        btnSalvarSubGrupo.classList.add('opacity-70', 'cursor-not-allowed');
    });
</script>
@endpush
@endsection