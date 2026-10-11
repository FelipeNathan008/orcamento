@extends('layouts.app')

@section('title', 'Cadastrar Subcategoria')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Cadastrar Subcategoria" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form
            id="subCategoriaForm"
            action="{{ route('tipo_sub_categoria.store') }}"
            method="POST"
            data-sub-grupo-inicial="{{ old('sub_grupo_id', '') }}"
            data-categoria-inicial="{{ old('categoria_id', '') }}"
            class="px-6 sm:px-8 pt-6 pb-8">
            @csrf

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados da subcategoria</h2>
                        <p class="text-xs text-gray-500 mt-0.5">
                            Selecione o grupo principal, o subgrupo, a categoria e informe o nome.
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
                            <option
                                value="{{ $grupo->id_grupo_principal }}"
                                @selected((string) old('grupo_principal_id')===(string) $grupo->id_grupo_principal)>
                                {{ $grupo->grupo_principal_nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="sub_grupo_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Subgrupo
                        </label>
                        <select
                            name="sub_grupo_id"
                            id="sub_grupo_id"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 disabled:bg-gray-100 disabled:text-gray-400"
                            disabled
                            required>
                            <option value="">Selecione primeiro um grupo principal</option>
                            @foreach ($subGrupos as $subGrupo)
                            <option
                                value="{{ $subGrupo->id_sub_grupo }}"
                                data-grupo="{{ $subGrupo->grupo_principal_id }}"
                                @selected((string) old('sub_grupo_id')===(string) $subGrupo->id_sub_grupo)>
                                {{ $subGrupo->sub_grupo_nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="categoria_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Categoria
                        </label>
                        <select
                            name="categoria_id"
                            id="categoria_id"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 disabled:bg-gray-100 disabled:text-gray-400"
                            disabled
                            required>
                            <option value="">Selecione primeiro um subgrupo</option>
                            @foreach ($categorias as $categoria)
                            <option
                                value="{{ $categoria->id_categoria }}"
                                data-subgrupo="{{ $categoria->sub_grupo_id }}"
                                @selected((string) old('categoria_id')===(string) $categoria->id_categoria)>
                                {{ $categoria->categoria_nome }}
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
                            placeholder="Ex.: Internet, Material de escritório..."
                            maxlength="255"
                            value="{{ old('sub_categoria_nome') }}"
                            required>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="btnSalvarSubCategoria" class="px-6">
                    <span id="textoSalvar">Salvar subcategoria</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('subCategoriaForm');
        const grupoSelect = document.getElementById('grupo_principal_id');
        const subGrupoSelect = document.getElementById('sub_grupo_id');
        const categoriaSelect = document.getElementById('categoria_id');
        const btnSalvar = document.getElementById('btnSalvarSubCategoria');
        const textoSalvar = document.getElementById('textoSalvar');

        const subGrupoInicial = form.dataset.subGrupoInicial || '';
        const categoriaInicial = form.dataset.categoriaInicial || '';

        function atualizarSubGrupos(valorInicial = '') {
            const grupoSelecionado = grupoSelect.value;
            const subGrupoAnterior = valorInicial || subGrupoSelect.value;

            subGrupoSelect.disabled = !grupoSelecionado;

            Array.from(subGrupoSelect.options).forEach(function(option) {
                if (!option.value) {
                    option.hidden = false;
                    return;
                }

                option.hidden = option.dataset.grupo !== grupoSelecionado;
            });

            subGrupoSelect.options[0].text = grupoSelecionado ?
                'Selecione um subgrupo' :
                'Selecione primeiro um grupo principal';

            const opcaoValida = Array.from(subGrupoSelect.options).some(function(option) {
                return option.value === String(subGrupoAnterior) &&
                    option.dataset.grupo === grupoSelecionado;
            });

            subGrupoSelect.value = opcaoValida ? String(subGrupoAnterior) : '';
            atualizarCategorias(categoriaInicial);
        }

        function atualizarCategorias(valorInicial = '') {
            const subGrupoSelecionado = subGrupoSelect.value;
            const categoriaAnterior = valorInicial || categoriaSelect.value;

            categoriaSelect.disabled = !subGrupoSelecionado;

            Array.from(categoriaSelect.options).forEach(function(option) {
                if (!option.value) {
                    option.hidden = false;
                    return;
                }

                option.hidden = option.dataset.subgrupo !== subGrupoSelecionado;
            });

            categoriaSelect.options[0].text = subGrupoSelecionado ?
                'Selecione uma categoria' :
                'Selecione primeiro um subgrupo';

            const opcaoValida = Array.from(categoriaSelect.options).some(function(option) {
                return option.value === String(categoriaAnterior) &&
                    option.dataset.subgrupo === subGrupoSelecionado;
            });

            categoriaSelect.value = opcaoValida ? String(categoriaAnterior) : '';
        }

        grupoSelect.addEventListener('change', function() {
            atualizarSubGrupos();
        });

        subGrupoSelect.addEventListener('change', function() {
            atualizarCategorias();
        });

        atualizarSubGrupos(subGrupoInicial);

        form.addEventListener('submit', function(event) {
            if (btnSalvar.disabled) {
                event.preventDefault();
                return;
            }

            btnSalvar.disabled = true;
            textoSalvar.innerText = 'Salvando...';
            btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
        });
    });
</script>
@endpush