<!-- resources/views/produto/create.blade.php -->
@extends('layouts.app')

@section('title', 'Cadastrar Novo Produto')

@section('content')
    {{-- Contêiner principal para centralizar o formulário e definir largura máxima --}}
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">
        {{-- Cabeçalho --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h1
                class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
                Cadastro de Novo Produto
            </h1>
            <a href="{{ route('produto.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-800 bg-gray-300 hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                Voltar para a Lista
            </a>
        </div>

        {{-- Alerta de erro --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert">
                <strong class="font-bold">Opa!</strong>
                <span class="block sm:inline">Existem alguns problemas com seus dados.</span>
                <ul class="mt-3 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Formulário de cadastro --}}
        <form action="{{ route('produto.store') }}" method="POST" class="space-y-6" id="produto-form">
            @csrf

            {{-- Seção de campos do formulário organizada em layout de duas colunas (responsivo) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Campo Nome do Produto - Ocupa duas colunas --}}
                <div class="md:col-span-2">
                    <div>
                        <label for="prod_nome" class="block text-sm font-medium text-custom-dark-text mb-1">Nome do
                            Produto</label>
                        <input type="text" name="prod_nome" id="prod_nome"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Nome completo do produto" maxlength="85" value="{{ old('prod_nome') }}" required>
                        @error('prod_nome')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- PRIMEIRA COLUNA (ESQUERDA) - Campos Família, Categoria, Código, Material, Tamanho --}}
                <div>
                    <!-- Campo Família (Select) -->
                    <div class="mt-0">
                        <label for="prod_familia"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Família</label>
                        <select name="prod_familia" id="prod_familia"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="" class="text-gray-400">Selecione a Família</option>
                            @php
                                $familias = [
                                    'Linha Social' => 'Linha Social',
                                    'Linha Operacional' => 'Linha Operacional',
                                    'Linha Gastronômica' => 'Linha Gastronômica',
                                    'Linha Inverno' => 'Linha Inverno',
                                ];
                                $selectedFamilia = old('prod_familia');
                            @endphp
                            @foreach ($familias as $familiaValue => $familiaName)
                                <option value="{{ $familiaValue }}" {{ $selectedFamilia == $familiaValue ? 'selected' : '' }}>
                                    {{ $familiaName }}
                                </option>
                            @endforeach
                        </select>
                        @error('prod_familia')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Categoria -->
                    <div class="mt-6">
                        <label for="prod_categoria"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Categoria</label>
                        <select name="prod_categoria" id="prod_categoria"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required disabled> {{-- Desabilitado por padrão --}}
                            <option value="" class="text-gray-400">Selecione a Categoria</option>
                        </select>
                        @error('prod_categoria')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Código -->
                    <div class="mt-6">
                        <label for="prod_cod" class="block text-sm font-medium text-custom-dark-text mb-1">Código</label>
                        <input type="text" name="prod_cod" id="prod_cod"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Código do produto" maxlength="45" value="{{ old('prod_cod') }}" required disabled>
                        {{-- Desabilitado por padrão --}}
                        @error('prod_cod')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Material -->
                    <div class="mt-6">
                        <label for="prod_material"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Material</label>
                        <input type="text" name="prod_material" id="prod_material"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Ex: 100% Algodão, Poliéster" maxlength="45" value="{{ old('prod_material') }}"
                            required disabled> {{-- Disabled by default --}}
                        @error('prod_material')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Tamanho (Checkboxes dentro de um <details>) -->
                    <div class="mt-6">
                        <details id="prod_tamanho_details" class="border border-gray-300 rounded-md shadow-sm">
                            <summary
                                class="block text-sm font-medium text-custom-dark-text px-4 py-2.5 cursor-pointer bg-white rounded-t-md hover:bg-gray-50 transition duration-150 ease-in-out">
                                Tamanhos Disponíveis
                            </summary>
                            <div id="prod_tamanho_checkboxes" class="flex flex-col gap-2 p-2 bg-white rounded-b-md">
                                @php
                                    $sizes = ['PP', 'P', 'M', 'G', 'GG', 'EG', 'G1', 'G2', 'G3', 'G4', 'G5', 'Único'];
                                    $oldSizes = old('prod_tamanho', []);
                                    if (!is_array($oldSizes)) {
                                        $oldSizes = explode(',', (string) $oldSizes); // Garante que seja um array
                                    }
                                    $half = ceil(count($sizes) / 2);
                                @endphp
                                <div class="flex flex-wrap gap-2">
                                    @foreach (array_slice($sizes, 0, $half) as $size)
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="prod_tamanho[]" value="{{ $size }}"
                                                class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300"
                                                id="prod_tamanho_{{ $size }}" {{ in_array($size, $oldSizes) ? 'checked' : '' }}
                                                disabled> {{-- Disabled by default --}}
                                            <span class="ml-2 text-base text-gray-700">{{ $size }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    @foreach (array_slice($sizes, $half) as $size)
                                        <label class="inline-flex items-center cursor-pointer">
                                            <input type="checkbox" name="prod_tamanho[]" value="{{ $size }}"
                                                class="form-checkbox h-5 w-5 text-blue-600 rounded focus:ring-blue-500 border-gray-300"
                                                id="prod_tamanho_{{ $size }}" {{ in_array($size, $oldSizes) ? 'checked' : '' }}
                                                disabled> {{-- Disabled by default --}}
                                            <span class="ml-2 text-base text-gray-700">{{ $size }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </details>
                        @error('prod_tamanho')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim da primeira coluna --}}

                {{-- SEGUNDA COLUNA (DIREITA) - Campos Preço, Gênero, Modelo, Características, Cor --}}
                <div>
                    <!-- Campo Preço -->
                    <div class="mt-0">
                        <label for="prod_preco" class="block text-sm font-medium text-custom-dark-text mb-1">Preço</label>
                        <input type="text" name="prod_preco" id="prod_preco"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="0,00" value="{{ old('prod_preco') }}" required disabled>
                        @error('prod_preco')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Gênero -->
                    <div class="mt-6">
                        <label for="prod_genero" class="block text-sm font-medium text-custom-dark-text mb-1">Gênero</label>
                        <select name="prod_genero" id="prod_genero"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required disabled>
                            <option value="" class="text-gray-400">Selecione o Gênero</option>
                            @php
                                $generos = [
                                    'Masculino' => 'Masculino',
                                    'Feminino' => 'Feminino',
                                    'Unissex' => 'Unissex',
                                ];
                                $selectedGenero = old('prod_genero');
                            @endphp
                            @foreach ($generos as $generosValue => $generosName)
                                <option value="{{ $generosValue }}" {{ $selectedGenero == $generosValue ? 'selected' : '' }}>
                                    {{ $generosName }}
                                </option>
                            @endforeach
                        </select>
                        @error('prod_genero')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Modelo -->
                    <div class="mt-6">
                        <label for="prod_modelo" class="block text-sm font-medium text-custom-dark-text mb-1">Modelo</label>
                        <input type="text" name="prod_modelo" id="prod_modelo"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Ex: Piquet, Listrado com Botões" maxlength="70" {{-- Ajustado para 70 caracteres
                            conforme a migration --}} value="{{ old('prod_modelo') }}" required disabled>
                        @error('prod_modelo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Características (Campo de texto visível, preenchimento automático) -->
                    <div class="mt-6">
                        <label for="prod_caract_display"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Características</label>
                        <input type="text" name="prod_caract_display" id="prod_caract_display"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            value="{{ old('prod_caract') }}" maxlength="55" {{-- Ajustado para 55 caracteres conforme a
                            migration --}} disabled> {{-- Desabilitado para edição manual --}}
                        <input type="hidden" name="prod_caract" id="prod_caract_hidden" value="{{ old('prod_caract') }}">
                        @error('prod_caract')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Cor -->
                    <div class="mt-6">
                        <label for="prod_cor" class="block text-sm font-medium text-custom-dark-text mb-1">Cor</label>
                        <input type="text" name="prod_cor" id="prod_cor"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Ex: Azul, Preto, Branco" maxlength="20" value="{{ old('prod_cor') }}" required
                            disabled>
                        @error('prod_cor')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim da segunda coluna --}}

            </div> {{-- Fim do grid principal --}}

            <!-- Botões de Ação -->
            <div class="flex justify-center mt-8 space-x-4">
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out">
                    CADASTRAR PRODUTO
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        {{-- Inclua o jQuery e o jQuery Mask Plugin --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script>
            $(document).ready(function () {
                // Máscara para Preço (moeda brasileira)
                $('#prod_preco').mask('000.000.000.000.000,00', {
                    reverse: true
                });

                const prodFamiliaSelect = $('#prod_familia');
                const prodCategoriaSelect = $('#prod_categoria');
                const prodCodInput = $('#prod_cod');
                const prodMaterialInput = $('#prod_material');
                const prodPrecoInput = $('#prod_preco');
                const prodGeneroSelect = $('#prod_genero');
                const prodModeloInput = $('#prod_modelo');
                const prodCaractDisplayInput = $('#prod_caract_display');
                const prodCaractHiddenInput = $('#prod_caract_hidden');
                const prodTamanhoDetails = $('#prod_tamanho_details');
                const prodTamanhoCheckboxesContainer = $('#prod_tamanho_checkboxes');
                const prodCorInput = $('#prod_cor');

                function getCaractOptions(selectedFamilia, selectedCategoria) {
                    let characteristics = [];

                    // Lógica para 'Jaleco'
                    if (selectedFamilia === 'Linha Operacional' && selectedCategoria === 'Jaleco') {
                        characteristics = ['Com Bolso', 'Sem Bolso'];
                    }
                    // Lógica para 'Jaleco de Brim' e as calças da Linha Operacional
                    else if (selectedFamilia === 'Linha Operacional' && [
                        'Jaleco de Brim', 'Calças de Brim', 'Calças Jeans', 'Calça Bailarina', 'Calça Sarja'
                    ].includes(selectedCategoria)) {
                        characteristics = ['-'];
                    }
                    // Lógica para camisetas da 'Linha Operacional'
                    else if (selectedFamilia === 'Linha Operacional' && [
                        'Camiseta Manga Curta', 'Camiseta Baby Look', 'Camiseta Manga Longa', 'Camiseta Polo'
                    ].includes(selectedCategoria)) {
                        characteristics = ['Com Bolso', 'Sem Bolso'];
                    }
                    // Lógica para a 'Linha Social'
                    else if (selectedFamilia === 'Linha Social' && [
                        'Camisas', 'Camisetes', 'Camisetes com Detalhes'
                    ].includes(selectedCategoria)) {
                        characteristics = ['Manga Curta', 'Manga Longa', 'Com Bolso', 'Sem Bolso'];
                    }
                    // Lógica para todas as outras categorias
                    else {
                        characteristics = ['-'];
                    }

                    return characteristics;
                }

                function updateCategoriaOptions() {
                    const selectedFamilia = prodFamiliaSelect.val();
                    let optionsHtml = '<option value="" class="text-gray-400">Selecione a Categoria</option>';
                    let categories = [];

                    if (selectedFamilia === 'Linha Social') {
                        categories = [
                            'Camisetes', 'Camisas', 'Camisetes com Detalhes', 'Blazer', 'Vestido Tubinho', 'Calça Social'
                        ];
                    } else if (selectedFamilia === 'Linha Operacional') {
                        categories = [
                            'Camiseta Manga Curta', 'Camiseta Baby Look', 'Camiseta Manga Longa', 'Camiseta Polo',
                            'Jaleco', 'Jaleco de Brim', 'Calças de Brim', 'Calças Jeans', 'Calça Bailarina', 'Calça Sarja'
                        ];
                    } else if (selectedFamilia === 'Linha Gastronômica') {
                        categories = [
                            'Avental Peito', 'Meio Avental', 'Avental Gourmet', 'Touca Sushiman', 'Touca Telinha', 'Dolmã'
                        ];
                    } else if (selectedFamilia === 'Linha Inverno') {
                        categories = [
                            'Jaqueta tactel com forro tactel', 'Jaqueta tactel com forro matelassê', 'Blusa Helanca Flanelada', 'Blusa de Lã'
                        ];
                    } else {
                        categories = ['-'];
                    }

                    categories.forEach(cat => {
                        optionsHtml += `<option value="${cat}">${cat}</option>`;
                    });
                    prodCategoriaSelect.html(optionsHtml);
                }

                function setAndClearField(field, enable, initialValue = '') {
                    if (field.attr('id') === 'prod_tamanho_checkboxes') {
                        field.find('input[type="checkbox"]').each(function () {
                            const checkbox = $(this);
                            checkbox.prop('disabled', !enable);
                            if (!enable) {
                                checkbox.prop('checked', false);
                            } else {
                                if (Array.isArray(initialValue)) {
                                    checkbox.prop('checked', initialValue.includes(checkbox.val()));
                                }
                            }
                        });
                        if (enable && field.find('#prod_tamanho_Único').is(':checked')) {
                            field.find('input[type="checkbox"]').not('#prod_tamanho_Único').prop('disabled', true);
                        }
                        if (enable) {
                            prodTamanhoDetails.prop('open', true);
                        } else {
                            prodTamanhoDetails.prop('open', false);
                        }
                    } else {
                        field.prop('disabled', !enable);
                        if (!enable) {
                            field.val('');
                            if (field.is('select')) {
                                field.find('option:first').prop('selected', true);
                            }
                        } else {
                            if (initialValue && field.val() === '') {
                                field.val(initialValue);
                            }
                        }
                    }
                }

                function updateCascadingFields() {
                    const isFamiliaSelected = prodFamiliaSelect.val() !== '';
                    const isCategoriaSelected = prodCategoriaSelect.val() !== '';

                    setAndClearField(prodCategoriaSelect, isFamiliaSelected);
                    if (!isFamiliaSelected) {
                        updateCategoriaOptions();
                    }

                    const allowedCharacteristics = getCaractOptions(prodFamiliaSelect.val(), prodCategoriaSelect.val());
                    if (isCategoriaSelected && allowedCharacteristics.length > 0) {
                        const characteristicsString = allowedCharacteristics.join(', ');
                        prodCaractHiddenInput.val(characteristicsString);
                        prodCaractDisplayInput.val(characteristicsString);
                    } else {
                        prodCaractHiddenInput.val('');
                        prodCaractDisplayInput.val('');
                    }

                    setAndClearField(prodTamanhoCheckboxesContainer, isCategoriaSelected);
                    if (isCategoriaSelected) {
                        prodTamanhoDetails.prop('open', true);
                    } else {
                        prodTamanhoDetails.prop('open', false);
                    }

                    setAndClearField(prodCodInput, isFamiliaSelected);
                    setAndClearField(prodMaterialInput, isFamiliaSelected);
                    setAndClearField(prodPrecoInput, isFamiliaSelected);
                    setAndClearField(prodGeneroSelect, isFamiliaSelected);
                    setAndClearField(prodModeloInput, isFamiliaSelected);
                    setAndClearField(prodCorInput, isFamiliaSelected);
                }

                updateCategoriaOptions();
                updateCascadingFields();

                prodFamiliaSelect.on('change', function () {
                    updateCategoriaOptions();
                    updateCascadingFields();
                });

                prodCategoriaSelect.on('change', function () {
                    updateCascadingFields();
                });

                prodTamanhoCheckboxesContainer.on('change', 'input[type="checkbox"]', function () {
                    const unicoCheckbox = $('#prod_tamanho_Único');
                    const allOtherCheckboxes = prodTamanhoCheckboxesContainer.find('input[type="checkbox"]').not(unicoCheckbox);

                    if ($(this).attr('id') === 'prod_tamanho_Único') {
                        if (unicoCheckbox.is(':checked')) {
                            allOtherCheckboxes.prop('checked', false).prop('disabled', true);
                        } else {
                            allOtherCheckboxes.prop('disabled', false);
                        }
                    } else {
                        if ($(this).is(':checked')) {
                            unicoCheckbox.prop('checked', false).prop('disabled', true);
                        } else {
                            const anyOtherChecked = allOtherCheckboxes.is(':checked');
                            if (!anyOtherChecked) {
                                unicoCheckbox.prop('disabled', false);
                            }
                        }
                    }
                });

                // Validação de formulário personalizada
                $('#produto-form').on('submit', function (event) {
                    // Antes de enviar, remove a máscara do campo de preço
                    const precoValue = $('#prod_preco').val();
                    const cleanPreco = precoValue.replace(/\./g, '').replace(',', '.');
                    $('#prod_preco').val(cleanPreco);

                    // Limpa as mensagens de erro anteriores
                    $('.error-message').remove();
                    let isValid = true;

                    // Itera sobre todos os campos habilitados do formulário
                    $(this).find(':input:enabled').each(function () {
                        const field = $(this);
                        const value = field.val().trim();
                        const isRequired = field.prop('required');

                        if (isRequired && (value === '' || value === null)) {
                            isValid = false;
                            const label = $(`label[for="${field.attr('id')}"]`).text() || field.attr('name');
                            const errorMessage = `
                                                    <p class="mt-2 text-sm text-red-600 error-message">
                                                        <strong>⚠️ Preencha este campo!</strong>
                                                    </p>
                                                `;
                            field.closest('div').append(errorMessage);
                            field.addClass('border-red-500'); // Adiciona uma borda vermelha ao campo
                        } else {
                            field.removeClass('border-red-500'); // Remove a borda vermelha se o campo for válido
                        }
                    });

                    // Validação especial para os checkboxes de tamanho
                    const tamanhoCheckboxes = prodTamanhoCheckboxesContainer.find('input[type="checkbox"]:enabled');
                    if (tamanhoCheckboxes.length > 0 && tamanhoCheckboxes.filter(':checked').length === 0) {
                        isValid = false;
                        const errorMessage = `
                                                <p class="mt-2 text-sm text-red-600 error-message">
                                                    <strong>⚠️ Selecione pelo menos um tamanho!</strong>
                                                </p>
                                            `;
                        prodTamanhoDetails.after(errorMessage);
                        prodTamanhoDetails.addClass('border-red-500');
                    } else {
                        prodTamanhoDetails.removeClass('border-red-500');
                    }

                    // Se houver algum erro, impede o envio do formulário
                    if (!isValid) {
                        event.preventDefault();
                        // Rola a página para o primeiro campo com erro
                        $('html, body').animate({
                            scrollTop: $('.error-message').first().offset().top - 100
                        }, 500);
                    }
                });
            });
        </script>
    @endpush
@endsection