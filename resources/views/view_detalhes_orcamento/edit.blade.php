{{-- resources/views/view_detalhes_orcamento/edit.blade.php --}}
@extends('layouts.app') {{-- Assumindo que você tem um layout principal chamado 'app' --}}

@section('title', 'Editar Detalhe de Orçamento')

@push('styles')
    {{-- Select2 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Estilos opcionais para alinhar o Select2 com o TailwindCSS */
        .select2-container .select2-selection--single {
            height: 42px !important; /* Ajusta a altura para combinar com os campos de input */
            border: 1px solid #d1d5db !important; /* Cor da borda (Tailwind gray-300) */
            border-radius: 0.375rem !important; /* Raio da borda (Tailwind rounded-md) */
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important; /* Ajusta a altura da seta */
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px !important; /* Centraliza o texto verticalmente */
            padding-left: 1rem !important; /* Adiciona padding para alinhar com outros inputs */
            padding-right: 1rem !important; /* Adiciona padding para alinhar com outros inputs */
        }
        .select2-dropdown {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            padding: 0.5rem 1rem !important;
        }

        /* Estilos para o Select2 de múltiplas seleções */
        .select2-container--default .select2-selection--multiple {
            border: 1px solid #d1d5db !important;
            border-radius: 0.375rem !important;
            min-height: 42px !important; /* Altura mínima para múltiplas seleções */
            padding: 0.25rem 0.5rem !important;
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #e0e7ff !important; /* Tailwind indigo-100 */
            border: 1px solid #a5b4fc !important; /* Tailwind indigo-300 */
            border-radius: 0.25rem !important;
            padding: 0.25rem 0.5rem !important;
            margin: 0.25rem !important;
            color: #312e81 !important; /* Tailwind indigo-900 */
        }
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #312e81 !important;
            margin-right: 0.25rem !important;
        }
    </style>
@endpush

@section('content')
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins"> {{-- Contêiner principal para centralizar --}}
        {{-- Título centralizado --}}
        <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Editar Detalhe de Orçamento</h1>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
                <strong class="font-bold">Sucesso!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

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

        <div>
            <form action="{{ route('detalhes_orcamento.update', $detalheOrcamento->id_det) }}" method="POST" class="space-y-6" id="detalhesOrcamentoForm">
                @csrf
                @method('PUT') {{-- Método HTTP para atualização --}}

                {{-- Seção de seleção de Orçamento e Produto --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Campo Orçamento (Select) -->
                    <div>
                        <label for="orcamento_id_orcamento" class="block text-sm font-medium text-custom-dark-text mb-1">Orçamento</label>
                        <select name="orcamento_id_orcamento" id="orcamento_id_orcamento"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="">Selecione um Orçamento</option>
                            @foreach ($orcamentos as $orcamento)
                                <option value="{{ $orcamento->id_orcamento }}"
                                    {{ (old('orcamento_id_orcamento', $detalheOrcamento->orcamento_id_orcamento) == $orcamento->id_orcamento) ? 'selected' : '' }}>
                                    {{ $orcamento->id_orcamento }} - {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                        @error('orcamento_id_orcamento')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Produto (Select) -->
                    <div>
                        <label for="produto_id_produto" class="block text-sm font-medium text-custom-dark-text mb-1">Produto</label>
                        <select name="produto_id_produto" id="produto_id_produto"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required> {{-- Removido 'disabled' aqui para permitir seleção inicial --}}
                            <option value="">Selecione um Produto</option>
                            @foreach ($produtos as $produto)
                                <option value="{{ $produto->id_produto }}"
                                    data-prod-cod="{{ $produto->prod_cod }}"
                                    data-prod-categoria="{{ $produto->prod_categoria }}"
                                    data-prod-modelo="{{ $produto->prod_modelo }}"
                                    data-prod-cor="{{ $produto->prod_cor }}"
                                    data-prod-genero="{{ $produto->prod_genero }}"
                                    data-prod-caract="{{ $produto->prod_caract }}"
                                    {{-- Garante que prod_tamanho seja sempre um JSON array válido --}}
                                    data-prod-tamanho="{{ json_encode(is_array($produto->prod_tamanho) ? $produto->prod_tamanho : explode(',', $produto->prod_tamanho)) }}"
                                    data-prod-preco="{{ number_format($produto->prod_preco, 2, ',', '') }}"
                                    {{ (old('produto_id_produto', $detalheOrcamento->produto_id_produto) == $produto->id_produto) ? 'selected' : '' }}>
                                    {{ $produto->prod_cod }} - {{ $produto->prod_nome }} - {{ $produto->prod_categoria }} -
                                    {{ $produto->prod_cor }}
                                </option>
                            @endforeach
                        </select>
                        @error('produto_id_produto')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim da seção de seleção --}}

                {{-- Este campo é preenchido automaticamente com o valor do 'id_co' selecionado do cliente do orçamento. --}}
                {{-- Não visível no formulário, mas necessário para o backend. --}}
                <input type="hidden" name="orcamento_cliente_id_cliente" id="orcamento_cliente_id_cliente" value="{{ old('orcamento_cliente_id_cliente', $detalheOrcamento->orcamento_cliente_id_cliente) }}">

                {{-- Seção de campos do produto (preenchimento automático) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    {{-- PRIMEIRA COLUNA (ESQUERDA) dos campos do produto --}}
                    <div class="space-y-6">
                        <!-- Campo Código -->
                        <div>
                            <label for="det_cod" class="block text-sm font-medium text-custom-dark-text mb-1">Código</label>
                            <input type="text" name="det_cod" id="det_cod"
                                class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md outline-none focus:ring-0 focus:border-gray-300 cursor-not-allowed border border-gray-300"
                                placeholder="Código do detalhe" maxlength="45" value="{{ old('det_cod', $detalheOrcamento->det_cod) }}" readonly>
                            @error('det_cod')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo Modelo -->
                        <div>
                            <label for="det_modelo" class="block text-sm font-medium text-custom-dark-text mb-1">Modelo</label>
                            <input type="text" name="det_modelo" id="det_modelo"
                                class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md outline-none focus:ring-0 focus:border-gray-300 cursor-not-allowed border border-gray-300"
                                placeholder="Modelo do produto" maxlength="45" value="{{ old('det_modelo', $detalheOrcamento->det_modelo) }}" readonly>
                            @error('det_modelo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo Cor -->
                        <div>
                            <label for="det_cor" class="block text-sm font-medium text-custom-dark-text mb-1">Cor</label>
                            <input type="text" name="det_cor" id="det_cor"
                                class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md outline-none focus:ring-0 focus:border-gray-300 cursor-not-allowed border border-gray-300"
                                placeholder="Cor do produto" maxlength="15" value="{{ old('det_cor', $detalheOrcamento->det_cor) }}" readonly>
                            @error('det_cor')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div> {{-- Fim da PRIMEIRA COLUNA (campos do produto) --}}

                    {{-- SEGUNDA COLUNA (DIREITA) dos campos do produto (sem Característica) --}}
                    <div class="space-y-6">
                        <!-- Campo Categoria -->
                        <div>
                            <label for="det_categoria" class="block text-sm font-medium text-custom-dark-text mb-1">Categoria</label>
                            <input type="text" name="det_categoria" id="det_categoria"
                                class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md outline-none focus:ring-0 focus:border-gray-300 cursor-not-allowed border border-gray-300"
                                placeholder="Categoria do detalhe" maxlength="45" value="{{ old('det_categoria', $detalheOrcamento->det_categoria) }}" readonly>
                            @error('det_categoria')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo Valor Unitário -->
                        <div>
                            <label for="det_valor_unit" class="block text-sm font-medium text-custom-dark-text mb-1">Valor Unitário</label>
                            <input type="text" name="det_valor_unit" id="det_valor_unit"
                                class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md outline-none focus:ring-0 focus:border-gray-300 cursor-not-allowed border border-gray-300"
                                placeholder="Ex: 123,45" value="{{ old('det_valor_unit', number_format($detalheOrcamento->det_valor_unit, 2, ',', '')) }}" readonly>
                            @error('det_valor_unit')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Campo Gênero -->
                        <div>
                            <label for="det_genero" class="block text-sm font-medium text-custom-dark-text mb-1">Gênero</label>
                            <input type="text" name="det_genero" id="det_genero"
                                class="block w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-md outline-none focus:ring-0 focus:border-gray-300 cursor-not-allowed border border-gray-300"
                                placeholder="Gênero do produto" maxlength="20" value="{{ old('det_genero', $detalheOrcamento->det_genero) }}" readonly>
                            @error('det_genero')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div> {{-- Fim da SEGUNDA COLUNA (campos do produto) --}}

                    <!-- Campo Característica (Agora é um Select Múltiplo) -->
                    <div class="md:col-span-2">
                        <label for="det_caract" class="block text-sm font-medium text-custom-dark-text mb-1">Característica</label>
                        <select name="det_caract[]" id="det_caract" multiple="multiple"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            {{-- As opções serão preenchidas via JavaScript --}}
                        </select>
                        @error('det_caract')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim da seção de campos do produto --}}

                {{-- Campos Quantidade e Tamanho (Nova Seção, abaixo dos outros) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                    <!-- Campo Quantidade -->
                    <div>
                        <label for="det_quantidade" class="block text-sm font-medium text-custom-dark-text mb-1">Quantidade</label>
                        <input type="number" name="det_quantidade" id="det_quantidade"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Quantidade do item" min="1" value="{{ old('det_quantidade', $detalheOrcamento->det_quantidade) }}" required>
                        @error('det_quantidade')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Tamanho -->
                    <div>
                        <label for="det_tamanho" class="block text-sm font-medium text-custom-dark-text mb-1">Tamanho</label>
                        <select name="det_tamanho" id="det_tamanho"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="" class="text-gray-400">Selecione um Tamanho</option>
                            {{-- As opções serão preenchidas via JavaScript --}}
                        </select>
                        @error('det_tamanho')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim da seção Quantidade e Tamanho --}}


                <!-- Campo Observação (Ocupando a largura total) -->
                <div>
                    <label for="det_observacao" class="block text-sm font-medium text-custom-dark-text mb-1">Observação (Opcional)</label>
                    <textarea name="det_observacao" id="det_observacao" rows="3"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Informações adicionais sobre o detalhe do orçamento">{{ old('det_observacao', $detalheOrcamento->det_observacao) }}</textarea>
                    @error('det_observacao')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campo Anotação (Ocupando a largura total) -->
                <div>
                    <label for="det_anotacao" class="block text-sm font-medium text-custom-dark-text mb-1">Anotação (Opcional)</label>
                    <textarea name="det_anotacao" id="det_anotacao" rows="3"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Anotações internas sobre o detalhe">{{ old('det_anotacao', $detalheOrcamento->det_anotacao) }}</textarea>
                    @error('det_anotacao')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botões de Ação -->
                <div class="flex justify-center pt-8 space-x-4">
                    <button type="submit"
                        class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                        SALVAR ALTERAÇÕES
                    </button>
                    <a href="{{ route('detalhes_orcamento.index') }}"
                        class="inline-flex items-center justify-center py-3 px-8 border border-gray-300 shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        VOLTAR
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Message Modal -->
    <div id="customMessageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full">
            <h3 class="text-lg font-bold mb-4" id="modalTitle">Atenção!</h3>
            <p id="modalMessage" class="mb-6"></p>
            <div class="flex justify-end">
                <button id="closeModal" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">OK</button>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Inclua o jQuery e o jQuery Mask Plugin --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        {{-- Inclua o Select2 JavaScript AQUI --}}
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function () {
                // Máscara para Valor Unitário
                $('#det_valor_unit').mask('000.000.000.000.000,00', {reverse: true});

                const orcamentoSelect = $('#orcamento_id_orcamento');
                const produtoSelect = $('#produto_id_produto');
                const detTamanhoSelect = $('#det_tamanho');
                const detCaractSelect = $('#det_caract'); // Referência para o select de característica
                const form = $('#detalhesOrcamentoForm'); // Referência ao formulário

                // Campos que SÃO sempre preenchidos e devem ser imutáveis (read-only)
                const immutableFields = [
                    $('#det_cod'),
                    $('#det_categoria'),
                    $('#det_modelo'),
                    $('#det_cor'),
                    $('#det_valor_unit'),
                    $('#det_genero')
                ];

                // Campos que são habilitados/desabilitados e podem ser editados (após seleção de produto)
                const editableDependentFields = [
                    $('#det_quantidade'),
                    detTamanhoSelect,
                    detCaractSelect,
                    $('#det_observacao'),
                    $('#det_anotacao')
                ];

                // Store the initial product ID from the detalheOrcamento for comparison
                const initialProductId = "{{ $detalheOrcamento->produto_id_produto }}";

                // Variáveis para armazenar os valores 'old' ou os valores do detalheOrcamento para persistência
                // Estes são os valores que devem ser restaurados APENAS se o produto original for re-selecionado
                let oldDetTamanho = {!! json_encode(old('det_tamanho', $detalheOrcamento->det_tamanho)) !!};
                if (Array.isArray(oldDetTamanho) && oldDetTamanho.length > 0) {
                    oldDetTamanho = oldDetTamanho[0]; // Se for array, pega o primeiro elemento
                } else if (oldDetTamanho === "") {
                    oldDetTamanho = null; // Trata string vazia como null
                }

                let oldCaractValues = [];
                const oldDetCaractRaw = {!! json_encode(old('det_caract', $detalheOrcamento->det_caract)) !!};
                if (oldDetCaractRaw !== null && oldDetCaractRaw !== undefined) {
                    if (Array.isArray(oldDetCaractRaw)) {
                        oldCaractValues = oldDetCaractRaw;
                    } else if (typeof oldDetCaractRaw === 'string' && oldDetCaractRaw !== '') {
                        oldCaractValues = oldDetCaractRaw.split(',').map(item => item.trim()).filter(item => item !== '');
                    }
                }

                // Função para exibir o modal customizado
                function showCustomMessage(title, message) {
                    $('#modalTitle').text(title);
                    $('#modalMessage').text(message);
                    $('#customMessageModal').removeClass('hidden');
                }

                // Fechar modal customizado
                $('#closeModal').on('click', function() {
                    $('#customMessageModal').addClass('hidden');
                });

                // Função para limpar e desabilitar/tornar readonly TODOS os campos dependentes
                function disableAndClearAllDependentFields() {
                    // Limpa e torna readonly os campos imutáveis
                    immutableFields.forEach(field => {
                        field.val('');
                        field.prop('readonly', true);
                        field.removeClass('bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out')
                              .addClass('bg-gray-100 text-gray-700 cursor-not-allowed focus:ring-0 focus:border-gray-300');
                    });

                    // Limpa e desabilita os campos editáveis
                    editableDependentFields.forEach(field => {
                        field.val(''); // Limpa o valor
                        field.prop('disabled', true); // Desabilita o campo
                    });

                    // Reaplicar a máscara após limpar o valor unitário
                    $('#det_valor_unit').mask('000.000.000.000.000,00', {reverse: true});

                    // Limpa as opções dos selects de tamanho e característica
                    detTamanhoSelect.empty().append('<option value="" class="text-gray-400">Selecione um Tamanho</option>');
                    detCaractSelect.empty(); // Limpa todas as opções
                    // Destrua e re-inicialize o Select2 para detCaractSelect para garantir que o placeholder correto seja exibido
                    if (detCaractSelect.data('select2')) {
                        detCaractSelect.select2('destroy');
                    }
                    detCaractSelect.append('<option value="" class="text-gray-400"></option>'); // Adiciona uma opção vazia para o placeholder do Select2
                    initializeDetCaractSelect2("Selecione uma Característica"); // Re-inicializa com placeholder

                    // Limpa a seleção do Select2 do produto e o re-inicializa
                    produtoSelect.val(null);
                    initializeProdutoSelect2("Selecione um Orçamento primeiro");
                }

                // Função para habilitar SOMENTE os campos editáveis
                function enableEditableDependentFields() {
                    editableDependentFields.forEach(field => {
                        field.prop('disabled', false);
                    });
                }

                // Função para inicializar/re-inicializar o Select2 no campo de produto
                function initializeProdutoSelect2(placeholderText) {
                    // Destrua a instância existente do Select2 se houver
                    if (produtoSelect.data('select2')) {
                        produtoSelect.select2('destroy');
                    }
                    // Inicialize o Select2 para o campo de produto (single select)
                    produtoSelect.select2({
                        placeholder: placeholderText,
                        allowClear: true,
                        language: {
                            noResults: function () {
                                return "Nenhum resultado encontrado";
                            },
                            searching: function () {
                                return "Pesquisando...";
                            }
                        }
                    });
                }

                // Função para inicializar/re-inicializar o Select2 no campo de característica (multiple select)
                function initializeDetCaractSelect2(placeholderText) {
                    // Garante que o elemento não esteja desabilitado antes de inicializar o Select2
                    detCaractSelect.prop('disabled', false); 

                    // Destrua a instância existente do Select2 se houver
                    if (detCaractSelect.data('select2')) {
                        detCaractSelect.select2('destroy');
                    }
                    // Inicialize o Select2 para o campo de característica (multiple select)
                    detCaractSelect.select2({
                        placeholder: placeholderText,
                        allowClear: true,
                        multiple: true, // Habilita múltiplas seleções
                        language: {
                            noResults: function () {
                                return "Nenhum resultado encontrado";
                            },
                            searching: function () {
                                return "Pesquisando...";
                            }
                        }
                    });
                }

                // New function to encapsulate product selection logic
                function handleProductSelection(productId, isInitialLoad) {
                    const selectedOption = produtoSelect.find('option:selected');

                    if (productId) { // Se um produto válido for selecionado
                        enableEditableDependentFields(); // Habilita SOMENTE os campos editáveis

                        // Preenche os campos imutáveis com os dados dos atributos data-*
                        immutableFields.forEach(field => {
                            const dataAttr = field.attr('id').replace('det_', 'prod-');
                            const value = selectedOption.data(dataAttr);
                            field.val(value);
                            field.prop('readonly', true);
                            field.removeClass('bg-white text-gray-900 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out')
                                  .addClass('bg-gray-100 text-gray-700 cursor-not-allowed focus:ring-0 focus:border-gray-300');
                        });

                        // Preencher o valor unitário e reaplicar a máscara
                        const prodPreco = selectedOption.data('prod-preco');
                        if (prodPreco !== undefined && prodPreco !== null) {
                            $('#det_valor_unit').val(prodPreco);
                            $('#det_valor_unit').mask('000.000.000.000.000,00', {reverse: true});
                        }

                        // Popula o campo Tamanho
                        const rawProdTamanhoAttr = selectedOption.attr('data-prod-tamanho');
                        detTamanhoSelect.empty().append('<option value="" class="text-gray-400">Selecione um Tamanho</option>');

                        let tamanhos = [];
                        if (rawProdTamanhoAttr) {
                            try {
                                tamanhos = JSON.parse(rawProdTamanhoAttr);
                                if (!Array.isArray(tamanhos)) {
                                    console.warn('prod_tamanho não é um array após o parseamento:', tamanhos);
                                    tamanhos = [];
                                }
                            } catch (e) {
                                console.error("Erro ao fazer parse de prod_tamanho. Verifique se o dado no banco está em formato JSON válido:", e);
                                tamanhos = [];
                            }
                        }

                        if (Array.isArray(tamanhos) && tamanhos.length > 0) {
                            tamanhos.forEach(tamanho => {
                                const option = $('<option></option>')
                                    .val(tamanho)
                                    .text(tamanho);
                                detTamanhoSelect.append(option);
                            });
                        }

                        // Seleciona o valor para Tamanho
                        let valueToSelectTamanho = null;
                        if (isInitialLoad || productId == initialProductId) {
                             valueToSelectTamanho = oldDetTamanho; // Use o valor inicial de old/db
                        }
                        if (valueToSelectTamanho) {
                            detTamanhoSelect.val(valueToSelectTamanho);
                        } else {
                            detTamanhoSelect.val(null); // Limpa a seleção se não houver valor ou produto diferente
                        }
                        console.log('Tamanho set to:', detTamanhoSelect.val());


                        // Popula o campo Característica (Multiple Select)
                        const prodCaractData = selectedOption.data('prod-caract');
                        detCaractSelect.empty(); // Limpa todas as opções existentes

                        let caracteristicas = [];
                        if (prodCaractData) {
                            caracteristicas = prodCaractData.split(',').map(item => item.trim()).filter(item => item !== '');
                        }

                        if (caracteristicas.length > 0) {
                            caracteristicas.forEach(caract => {
                                const option = $('<option></option>')
                                    .val(caract)
                                    .text(caract);
                                detCaractSelect.append(option);
                            });
                        }

                        // Initialize/re-initialize Select2 for the characteristic field
                        initializeDetCaractSelect2("Selecione uma Característica");

                        // Seleciona os valores para Característica
                        let valuesToSelectCaract = [];
                        if (isInitialLoad || productId == initialProductId) {
                            valuesToSelectCaract = oldCaractValues; // Use os valores iniciais de old/db
                        }

                        if (valuesToSelectCaract.length > 0) {
                            detCaractSelect.val(valuesToSelectCaract).trigger('change');
                        } else {
                            detCaractSelect.val(null).trigger('change'); // Limpa a seleção se não houver valor ou produto diferente
                        }
                        console.log('Característica set to:', detCaractSelect.val());

                    } else { // Se nenhum produto ou seleção limpa
                        disableAndClearAllDependentFields();
                    }
                }

                // --- Lógica de Inicialização no Carregamento da Página de EDIÇÃO ---
                // Inicializa Select2 para Orçamento
                orcamentoSelect.select2({
                    placeholder: "Selecione um Orçamento",
                    allowClear: true,
                    language: {
                        noResults: function () { return "Nenhum resultado encontrado"; },
                        searching: function () { return "Pesquisando..."; }
                    }
                });

                // Inicializa Select2 para Produto (já com o valor selecionado do $detalheOrcamento)
                initializeProdutoSelect2("Pesquise ou selecione um Produto");

                // Dispara o evento change no produtoSelect para preencher os campos e configurar os Select2
                // Isso é importante para carregar os dados do produto e as opções de tamanho/característica
                // quando a página de edição é carregada com um detalhe existente.
                if (produtoSelect.val()) {
                    handleProductSelection(produtoSelect.val(), true); // Passa true para indicar carregamento inicial
                } else {
                    // Se não houver produto selecionado (e.g., erro de validação que limpou o produto),
                    // desabilita e limpa todos os campos dependentes.
                    disableAndClearAllDependentFields();
                }

                // --- Event Listener para o Select de Orçamento ---
                orcamentoSelect.on('change', function() {
                    if ($(this).val()) { // Se um orçamento válido for selecionado
                        produtoSelect.prop('disabled', false); // Habilita o select de produto
                        initializeProdutoSelect2("Pesquise ou selecione um Produto"); // Re-inicializa com placeholder de produto

                        // Se já houver um produto selecionado, tenta preencher e habilitar os editáveis
                        if (produtoSelect.val()) {
                            handleProductSelection(produtoSelect.val(), false); // Não é carregamento inicial
                        } else {
                            // Se nenhum produto selecionado, limpa e desabilita todos os dependentes
                            disableAndClearAllDependentFields();
                        }
                    } else { // Se nenhum orçamento ou seleção limpa
                        produtoSelect.prop('disabled', true); // Desabilita o select de produto
                        produtoSelect.val(null).trigger('change'); // Limpa a seleção do produto e dispara change
                        initializeProdutoSelect2("Selecione um Orçamento primeiro"); // Re-inicializa com placeholder de orçamento
                        disableAndClearAllDependentFields(); // Limpa e desabilita TODOS os campos dependentes
                    }
                });


                // --- Event Listener para o Select de Produto ---
                produtoSelect.on('change', function() {
                    const selectedProductId = $(this).val();
                    handleProductSelection(selectedProductId, false); // Passa false para indicar mudança de produto
                });

                // --- Lógica de Validação de Conflito para Característica (Com Bolso / Sem Bolso e Manga Curta / Manga Longa) ---
                detCaractSelect.on('select2:selecting', function (e) {
                    const itemToSelect = e.params.args.data.id;
                    let currentSelected = $(this).val() || []; // Valores já selecionados antes da nova seleção

                    const isComBolso = itemToSelect === 'Com Bolso';
                    const isSemBolso = itemToSelect === 'Sem Bolso';
                    const isMangaCurta = itemToSelect === 'Manga Curta';
                    const isMangaLonga = itemToSelect === 'Manga Longa';

                    const hasComBolsoAlready = currentSelected.includes('Com Bolso');
                    const hasSemBolsoAlready = currentSelected.includes('Sem Bolso');
                    const hasMangaCurtaAlready = currentSelected.includes('Manga Curta');
                    const hasMangaLongaAlready = currentSelected.includes('Manga Longa');

                    // Verifica conflito entre "Com Bolso" e "Sem Bolso"
                    if ((isComBolso && hasSemBolsoAlready) || (isSemBolso && hasComBolsoAlready)) { // Corrigido o typo aqui
                        e.preventDefault(); // Previne a seleção do item que causa o conflito
                        showCustomMessage('Atenção!', 'Não é possível selecionar "Com Bolso" e "Sem Bolso" simultaneamente.');
                    }
                    // Verifica conflito entre "Manga Curta" e "Manga Longa"
                    else if ((isMangaCurta && hasMangaLongaAlready) || (isMangaLonga && hasMangaCurtaAlready)) {
                        e.preventDefault(); // Previne a seleção do item que causa o conflito
                        showCustomMessage('Atenção!', 'Não é possível selecionar "Manga Curta" e "Manga Longa" simultaneamente.');
                    }
                });

                // Lógica para converter o array de características em string antes do envio do formulário
                form.on('submit', function(event) {
                    const selectedCaracts = detCaractSelect.val(); // Isso já é um array

                    // Remove o atributo 'name' do select original para que ele não seja enviado como um array
                    detCaractSelect.removeAttr('name');

                    // Cria um campo hidden para enviar a string de características
                    let hiddenCaractInput = $('<input>').attr({
                        type: 'hidden',
                        name: 'det_caract', // Este é o nome que o backend espera
                        id: 'det_caract_hidden'
                    });

                    if (selectedCaracts && selectedCaracts.length > 0) {
                        hiddenCaractInput.val(selectedCaracts.join(', ')); // Converte o array em string separada por vírgulas
                    } else {
                        hiddenCaractInput.val(''); // Envia uma string vazia se nada foi selecionado
                    }
                    form.append(hiddenCaractInput); // Adiciona o campo hidden ao formulário
                });
            });
        </script>
    @endpush
@endsection
