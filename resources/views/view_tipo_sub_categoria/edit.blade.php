@extends('layouts.app')

@section('title', 'Editar Subcategoria')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Editar Subcategoria" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form
            id="subCategoriaForm"
            action="{{ route('tipo_sub_categoria.update', ['tipo_sub_categorium' => CryptHelper::encrypt($subCategoria->id_sub_categoria)]) }}"
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
                        <h2 class="text-lg font-bold text-gray-800">Dados da subcategoria</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Atualize a categoria vinculada e o nome da subcategoria.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="categoria_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Categoria
                        </label>

                        <select
                            name="categoria_id"
                            id="categoria_id"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            required>
                            <option value="">Selecione uma categoria</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id_categoria }}"
                                    @selected(old('categoria_id', $subCategoria->categoria_id) == $categoria->id_categoria)>
                                    {{ $categoria->subGrupo->grupoPrincipal->grupo_principal_nome ?? 'Sem grupo' }}
                                    — {{ $categoria->subGrupo->sub_grupo_nome ?? 'Sem subgrupo' }}
                                    — {{ $categoria->categoria_nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sub_categoria_nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Nome da subcategoria
                        </label>

                        <input
                            type="text"
                            name="sub_categoria_nome"
                            id="sub_categoria_nome"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Nome da subcategoria..."
                            maxlength="255"
                            value="{{ old('sub_categoria_nome', $subCategoria->sub_categoria_nome) }}"
                            required>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>

                <x-primary-button type="submit" id="btnSalvarSubCategoria" class="px-6">
                    <span id="textoSalvar">Salvar alterações</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const formSubCategoria = document.getElementById('subCategoriaForm');
    const btnSalvarSubCategoria = document.getElementById('btnSalvarSubCategoria');
    const textoSalvarSubCategoria = document.getElementById('textoSalvar');

    formSubCategoria.addEventListener('submit', function(event) {
        if (btnSalvarSubCategoria.disabled) {
            event.preventDefault();
            return;
        }

        btnSalvarSubCategoria.disabled = true;
        textoSalvarSubCategoria.innerText = 'Salvando...';
        btnSalvarSubCategoria.classList.add('opacity-70', 'cursor-not-allowed');
    });
</script>
@endpush
@endsection
