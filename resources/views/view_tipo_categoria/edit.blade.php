@extends('layouts.app')

@section('title', 'Editar Categoria')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Editar Categoria" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form
            id="categoriaForm"
            action="{{ route('tipo_categoria.update', ['tipo_categorium' => CryptHelper::encrypt($categoria->id_categoria)]) }}"
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
                        <h2 class="text-lg font-bold text-gray-800">Dados da categoria</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Atualize o subgrupo e o nome da categoria.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="sub_grupo_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Subgrupo
                        </label>

                        <select
                            name="sub_grupo_id"
                            id="sub_grupo_id"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            required>
                            <option value="">Selecione um subgrupo</option>
                            @foreach ($subGrupos as $subGrupo)
                            <option value="{{ $subGrupo->id_sub_grupo }}"
                                @selected(old('sub_grupo_id', $categoria->sub_grupo_id) == $subGrupo->id_sub_grupo)>
                                {{ $subGrupo->grupoPrincipal->grupo_principal_nome ?? 'Sem grupo' }}
                                — {{ $subGrupo->sub_grupo_nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="categoria_nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Nome da categoria
                        </label>

                        <input
                            type="text"
                            name="categoria_nome"
                            id="categoria_nome"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Nome da categoria..."
                            maxlength="255"
                            value="{{ old('categoria_nome', $categoria->categoria_nome) }}"
                            required>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>

                <x-primary-button type="submit" id="btnSalvarCategoria" class="px-6">
                    <span id="textoSalvar">Salvar alterações</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const formCategoria = document.getElementById('categoriaForm');
    const btnSalvarCategoria = document.getElementById('btnSalvarCategoria');
    const textoSalvarCategoria = document.getElementById('textoSalvar');

    formCategoria.addEventListener('submit', function(event) {
        if (btnSalvarCategoria.disabled) {
            event.preventDefault();
            return;
        }

        btnSalvarCategoria.disabled = true;
        textoSalvarCategoria.innerText = 'Salvando...';
        btnSalvarCategoria.classList.add('opacity-70', 'cursor-not-allowed');
    });
</script>
@endpush
@endsection