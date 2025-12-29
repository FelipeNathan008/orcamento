@extends('layouts.app')

@section('title', 'Editar Customização: ' . $customizacao->id_customizacao)

@section('content')
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

        {{-- Título e botão de voltar --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h1
                class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0 flex items-center">
                Editar Customização: #{{ $customizacao->id_customizacao }}
                <span id="bolso-indicator" class="ml-2 text-red-600 text-base font-semibold hidden">(Com Bolso)</span>
            </h1>
            <a href="{{ route('customizacao.index', ['search_detalhes_id' => $customizacao->detalhes_orcamento_id_det]) }}"
                class="inline-flex items-center justify-center py-3 px-8 border border-gray-300 shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                Voltar para a Lista
            </a>
        </div>
        <span id="product-name-display" class="ml-3 text-xl text-gray-600 font-normal"></span>

        {{-- Mensagens de sucesso e erro --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4"
                role="alert">
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

        <form action="{{ route('customizacao.update', $customizacao->id_customizacao) }}" method="POST"
            enctype="multipart/form-data" class="space-y-6" id="customizacaoForm">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- PRIMEIRA COLUNA (ESQUERDA) --}}
                <div class="space-y-6">
                    {{-- Campo de seleção para o Detalhe do Orçamento --}}
                    <div>
                        <label for="detalhes_orcamento_id_det"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Detalhes do Orçamento:</label>
                        <select name="detalhes_orcamento_id_det" id="detalhes_orcamento_id_det"
                            class="block w-full px-4 py-2.5 bg-gray-200 text-gray-600 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required disabled>
                            <option value="">Selecione um Detalhe de Orçamento</option>
                            @foreach ($detalhesOrcamento as $detalhe)
                                <option value="{{ $detalhe->id_det }}"
                                    {{ (old('detalhes_orcamento_id_det', $customizacao->detalhes_orcamento_id_det) == $detalhe->id_det) ? 'selected' : '' }}
                                    data-product-name="{{ optional($detalhe->produto)->prod_nome ?? 'N/A' }}"
                                    data-product-code="{{ optional($detalhe->produto)->prod_cod ?? 'N/A' }}"
                                    data-product-categ="{{ optional($detalhe->produto)->prod_categoria ?? 'N/A' }}"
                                    data-product-cor="{{ optional($detalhe->produto)->prod_cor ?? 'N/A' }}"
                                    data-product-tam="{{ $detalhe->det_tamanho ?? 'N/A' }}"
                                    data-client-name="{{ optional(optional($detalhe->orcamento)->clienteOrcamento)->clie_orc_nome ?? 'N/A' }}"
                                    data-orcamento-id="{{ optional($detalhe->orcamento)->id_orcamento ?? 'N/A' }}"
                                    data-det-caract="{{ $detalhe->det_caract ?? '' }}">
                                    ID: {{ $detalhe->id_det }} - Orçamento ID:
                                    {{ optional($detalhe->orcamento)->id_orcamento ?? 'N/A' }}
                                    (Cliente:
                                    {{ optional(optional($detalhe->orcamento)->clienteOrcamento)->clie_orc_nome ?? 'N/A' }},
                                    Produto:
                                    {{ optional($detalhe->produto)->prod_nome ?? 'N/A' }},
                                    Código do Produto:
                                    {{ optional($detalhe->produto)->prod_cod ?? 'N/A' }}
                                    )
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="detalhes_orcamento_id_det"
                            value="{{ $customizacao->detalhes_orcamento_id_det }}">
                        @error('detalhes_orcamento_id_det')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tipo de customização --}}
                    <div>
                        <label for="cust_tipo" class="block text-sm font-medium text-custom-dark-text mb-1">Tipo:</label>
                        <select name="cust_tipo" id="cust_tipo"
                            class="block w-full px-4 py-2.5 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="">Selecione um Tipo</option>
                            @foreach ($precos->unique('preco_tipo') as $tipo)
                                <option value="{{ $tipo->preco_tipo }}"
                                    {{ old('cust_tipo', $customizacao->cust_tipo) == $tipo->preco_tipo ? 'selected' : '' }}>
                                    {{ $tipo->preco_tipo }}
                                </option>
                            @endforeach
                        </select>
                        @error('cust_tipo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Local da customização --}}
                    <div>
                        <label for="cust_local" class="block text-sm font-medium text-custom-dark-text mb-1">Local:</label>
                        <select name="cust_local" id="cust_local"
                            class="block w-full px-4 py-2.5 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="">Selecione um Local</option>
                            <option value="Ombro"
                                {{ old('cust_local', $customizacao->cust_local) == 'Ombro' ? 'selected' : '' }}>Ombro
                            </option>
                            <option value="Frente"
                                {{ old('cust_local', $customizacao->cust_local) == 'Frente' ? 'selected' : '' }}>Frente
                            </option>
                            <option value="Costa"
                                {{ old('cust_local', $customizacao->cust_local) == 'Costa' ? 'selected' : '' }}>Costa
                            </option>
                        </select>
                        @error('cust_local')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- SEGUNDA COLUNA (DIREITA) --}}
                <div class="space-y-6">
                    {{-- Posição --}}
                    <div>
                        <label for="cust_posicao"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Posição:</label>
                        <select name="cust_posicao" id="cust_posicao"
                            class="block w-full px-4 py-2.5 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="">Selecione uma Posição</option>
                            <option value="Direito"
                                {{ old('cust_posicao', $customizacao->cust_posicao) == 'Direito' ? 'selected' : '' }}>
                                Direito</option>
                            <option value="Esquerdo"
                                {{ old('cust_posicao', $customizacao->cust_posicao) == 'Esquerdo' ? 'selected' : '' }}>
                                Esquerdo</option>
                            <option value="Topo"
                                {{ old('cust_posicao', $customizacao->cust_posicao) == 'Topo' ? 'selected' : '' }}
                                class="hidden">Topo
                            </option>
                            <option value="Centro"
                                {{ old('cust_posicao', $customizacao->cust_posicao) == 'Centro' ? 'selected' : '' }}
                                class="hidden">Centro</option>
                            <option value="Rodapé"
                                {{ old('cust_posicao', $customizacao->cust_posicao) == 'Rodapé' ? 'selected' : '' }}
                                class="hidden">Rodapé
                            </option>
                        </select>
                        @error('cust_posicao')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tamanho e Medidas --}}
                    <div>
                        <label for="cust_tamanho_select_part"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Tamanho:</label>
                        <select id="cust_tamanho_select_part"
                            class="block w-full px-4 py-2.5 bg-white text-gray-900 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="">Selecione...</option>
                        </select>

                        {{-- Container para os campos de medidas (largura x altura) --}}
                        <div id="medidas_container" class="mt-6 hidden">
                            <label class="block text-sm font-medium text-custom-dark-text mb-1">Medidas:</label>
                            <div class="flex space-x-4">
                                <div class="relative w-1/2">
                                    <input type="number" step="0.1" name="cust_largura"
                                        id="cust_tamanho_numeric_part_x"
                                        class="w-full px-4 py-2.5 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                                        placeholder="Largura (cm)" min="0.1">
                                </div>
                                <div class="relative w-1/2">
                                    <input type="number" step="0.1" name="cust_altura"
                                        id="cust_tamanho_numeric_part_y"
                                        class="w-full px-4 py-2.5 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                                        placeholder="Altura (cm)" min="0.1">
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="cust_tamanho" id="cust_tamanho_final_value"
                            value="{{ old('cust_tamanho', $customizacao->cust_tamanho) }}">
                        @error('cust_tamanho')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Formatação e Valor --}}
                    <div>
                        <label for="cust_formatacao"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Formatação:</label>
                        <select name="cust_formatacao" id="cust_formatacao"
                            class="block w-full px-4 py-2.5 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="">Selecione uma Formatação</option>
                            <option value="Imagem"
                                {{ old('cust_formatacao', $customizacao->cust_formatacao) == 'Imagem' ? 'selected' : '' }}>
                                Imagem</option>
                            <option value="Escrita"
                                {{ old('cust_formatacao', $customizacao->cust_formatacao) == 'Escrita' ? 'selected' : '' }}>
                                Escrita</option>
                        </select>
                        @error('cust_formatacao')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div> {{-- Fim do grid principal --}}
            
            {{-- Campo Valor e Descrição (largura total) --}}
            <div class="space-y-6">
                <div>
                    <label for="cust_valor" class="block text-sm font-medium text-custom-dark-text mb-1">
                        Valor da Customização (R$)
                    </label>
                    <input type="text" name="cust_valor" id="cust_valor"
                        class="block w-full px-4 py-2.5 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Ex: 50,00" value="{{ old('cust_valor', number_format($customizacao->cust_valor, 2, ',', '.')) }}" readonly>
                    @error('cust_valor')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="cust_descricao" class="block text-sm font-medium text-custom-dark-text mb-1">Descrição:</label>
                    <textarea name="cust_descricao" id="cust_descricao" rows="3"
                        class="block w-full px-4 py-2.5 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Informações adicionais sobre a customização..." maxlength="90"
                        required>{{ old('cust_descricao', $customizacao->cust_descricao) }}</textarea>
                    @error('cust_descricao')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Campo Imagem da Customização (largura total) --}}
            <div class="mt-6">
                <label for="cust_imagem" class="block text-sm font-medium text-custom-dark-text mb-1">Imagem da
                    Customização:</label>
                {{-- Mensagem de aviso para formatação 'Escrita' --}}
                <div id="image-warning-message" class="mt-2 text-sm text-yellow-600 hidden">
                    <i class="fas fa-exclamation-triangle mr-1"></i>A formatação 'Escrita' ainda depende da disponibilização
                    de uma imagem que contenha o texto presumido.
                </div>
                <input type="file" name="cust_imagem" id="cust_imagem"
                    class="block w-full px-4 py-2.5 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out text-custom-dark-text
                                                 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-blue-700 hover:file:bg-gray-200">
                @error('cust_imagem')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                @if ($customizacao->cust_imagem)
                    <p class="text-gray-600 text-sm mt-2">Imagem atual:</p>
                    <img src="data:image/jpeg;base64,{{ base64_encode($customizacao->cust_imagem) }}"
                        alt="Imagem Atual" class="max-w-xs max-h-32 object-contain rounded-md shadow-sm mt-1">
                @else
                    <p class="text-gray-600 text-sm mt-2">Nenhuma imagem atual.</p>
                @endif
            </div>

            <div class="flex flex-col items-center mt-8">
                <button type="submit" id="submitButton"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                    style="background-color: #EA792D; focus:ring-color: #EA792D;">
                    ATUALIZAR CUSTOMIZAÇÃO
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

    {{-- Passa os dados de preços de customização e o objeto atual para o JavaScript --}}
    <script>
        const precosData = @json($precos);
        const customizacaoData = @json($customizacao);
        const oldInput = @json(old());
    </script>

    <script>
        $(document).ready(function() {
            // ===============================================
            // Seletores de Elementos
            // ===============================================
            const $detalhesOrcamentoIdDet = $('#detalhes_orcamento_id_det');
            const $custTipo = $('#cust_tipo');
            const $custLocal = $('#cust_local');
            const $custPosicao = $('#cust_posicao');
            const $custTamanhoSelect = $('#cust_tamanho_select_part');
            const $custTamanhoX = $('#cust_tamanho_numeric_part_x');
            const $custTamanhoY = $('#cust_tamanho_numeric_part_y');
            const $custTamanhoFinal = $('#cust_tamanho_final_value');
            const $custFormatacao = $('#cust_formatacao');
            const $custValor = $('#cust_valor');
            const $custDescricao = $('#cust_descricao');
            const $custImagem = $('#cust_imagem');
            const $medidasContainer = $('#medidas_container');
            const $imageWarningMessage = $('#image-warning-message');
            const $productNameDisplay = $('#product-name-display');
            const $bolsoIndicator = $('#bolso-indicator');
            
            // ===============================================
            // Funções de Utilitário
            // ===============================================
            function formatCurrency(value) {
                return new Intl.NumberFormat('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }).format(value);
            }

            function parseTamanho(tamanhoString) {
                if (!tamanhoString) {
                    return {
                        parteTexto: '',
                        largura: '',
                        altura: ''
                    };
                }
                const partes = tamanhoString.split(':');
                const parteTexto = partes[0].trim();
                const parteMedida = partes[1] ? partes[1].trim() : '';

                if (parteMedida) {
                    const medidas = parteMedida.toLowerCase().replace('cm', '').split('x');
                    return {
                        parteTexto: parteTexto,
                        largura: medidas[0].trim().replace(',', '.'),
                        altura: medidas[1] ? medidas[1].trim().replace(',', '.') : ''
                    };
                }
                return {
                    parteTexto: parteTexto,
                    largura: '',
                    altura: ''
                };
            }

            function updateCustomizacaoValor() {
                const tipo = $custTipo.val();
                let tamanho = $custTamanhoFinal.val();

                if (!tamanho) {
                    $custValor.val('');
                    return;
                }

                // Extrai a parte textual do tamanho para a busca
                const tamanhoParts = tamanho.split(':')[0].trim();

                const precoEncontrado = precosData.find(preco =>
                    preco.preco_tipo === tipo && preco.preco_tamanho === tamanhoParts
                );

                if (precoEncontrado) {
                    const valorFormatado = formatCurrency(parseFloat(precoEncontrado.preco_valor));
                    $custValor.val(valorFormatado);
                } else {
                    $custValor.val('');
                }
            }
            
            function updateFinalValue() {
                const tamanhoSelecionado = $custTamanhoSelect.val();
                const largura = $custTamanhoX.val().replace(',', '.');
                const altura = $custTamanhoY.val().replace(',', '.');
                
                if (tamanhoSelecionado && largura && altura) {
                     $custTamanhoFinal.val(`${tamanhoSelecionado}: ${largura.replace('.', ',')}x${altura.replace('.', ',')}cm`);
                } else if (tamanhoSelecionado) {
                    $custTamanhoFinal.val(tamanhoSelecionado);
                } else {
                    $custTamanhoFinal.val('');
                }
                
                updateCustomizacaoValor();
            }

            // ===============================================
            // Lógica de Inicialização
            // ===============================================

            // Máscara para o valor (moeda)
            $custValor.mask('000.000.000.000.000,00', {
                reverse: true
            });

            // Lógica de pré-preenchimento ao carregar a página
            function initializeForm() {
                const selectedOption = $detalhesOrcamentoIdDet.find('option:selected');
                    $productNameDisplay.text(
                        selectedOption.data('product-code') + ' - ' +
                        selectedOption.data('product-categ') + ' - ' +
                        selectedOption.data('product-cor') + ' - ' +
                        selectedOption.data('product-tam')
                    );

                const detCaract = selectedOption.data('det-caract');
                if (detCaract && detCaract.toLowerCase().includes('com bolso')) {
                    $bolsoIndicator.removeClass('hidden');
                }

                // Preenche as opções de Tamanho com base no Tipo inicial
                const initialTipo = $custTipo.val();
                if (initialTipo) {
                    const tamanhosUnicos = precosData
                        .filter(preco => preco.preco_tipo === initialTipo)
                        .map(preco => preco.preco_tamanho)
                        .filter((value, index, self) => self.indexOf(value) === index);

                    tamanhosUnicos.forEach(tamanho => {
                        $custTamanhoSelect.append(`<option value="${tamanho}">${tamanho}</option>`);
                    });

                    // Seleciona o tamanho inicial e preenche as medidas se existirem
                    const tamanhoAtual = oldInput.cust_tamanho || customizacaoData.cust_tamanho;
                    if (tamanhoAtual) {
                        const {
                            parteTexto,
                            largura,
                            altura
                        } = parseTamanho(tamanhoAtual);
                        $custTamanhoSelect.val(parteTexto);
                        if (parteTexto.toLowerCase().includes('cm')) {
                            $medidasContainer.removeClass('hidden');
                            $custTamanhoX.val(largura.replace('.', ','));
                            $custTamanhoY.val(altura.replace('.', ','));
                        }
                    }
                }
                
                // Exibe a mensagem de aviso se a formatação for 'Escrita'
                const initialFormatacao = $custFormatacao.val();
                if (initialFormatacao === 'Escrita') {
                    $imageWarningMessage.removeClass('hidden');
                }

                updateCustomizacaoValor();
            }

            // ===============================================
            // Lógica de Eventos
            // ===============================================

            // Evento de alteração do campo TIPO
            $custTipo.on('change', function() {
                const tipo = $(this).val();
                $custLocal.val(''); // Limpa o Local
                $custPosicao.val(''); // Limpa a Posição

                // Reinicia o campo de Tamanho e as medidas
                $custTamanhoSelect.empty().append('<option value="">Selecione...</option>');
                $custTamanhoX.val('').prop('disabled', true);
                $custTamanhoY.val('').prop('disabled', true);
                $medidasContainer.addClass('hidden');
                $custTamanhoFinal.val('');
                $custValor.val('');
                
                if (tipo) {
                    $custLocal.prop('disabled', false);
                    const tamanhosUnicos = precosData
                        .filter(preco => preco.preco_tipo === tipo)
                        .map(preco => preco.preco_tamanho)
                        .filter((value, index, self) => self.indexOf(value) === index);
                    tamanhosUnicos.forEach(tamanho => {
                        $custTamanhoSelect.append(`<option value="${tamanho}">${tamanho}</option>`);
                    });
                } else {
                    $custLocal.prop('disabled', true);
                    $custPosicao.prop('disabled', true);
                }
            });

            // Evento de alteração do campo LOCAL
            $custLocal.on('change', function() {
                const local = $(this).val();
                $custPosicao.val(''); // Limpa a Posição

                $custPosicao.prop('disabled', false);
                $custPosicao.find('option').addClass('hidden');
                $custPosicao.find('option[value=""]').removeClass('hidden');

                if (local === 'Ombro') {
                    $custPosicao.find('option[value="Direito"]').removeClass('hidden');
                    $custPosicao.find('option[value="Esquerdo"]').removeClass('hidden');
                } else if (local === 'Frente') {
                    $custPosicao.find('option[value="Direito"]').removeClass('hidden');
                    $custPosicao.find('option[value="Esquerdo"]').removeClass('hidden');
                    $custPosicao.find('option[value="Centro"]').removeClass('hidden');
                } else if (local === 'Costa') {
                    $custPosicao.find('option[value="Topo"]').removeClass('hidden');
                    $custPosicao.find('option[value="Centro"]').removeClass('hidden');
                    $custPosicao.find('option[value="Rodapé"]').removeClass('hidden');
                }
            });

            // Evento de alteração do campo POSIÇÃO
            $custPosicao.on('change', function() {
                // Apenas habilita o próximo campo, sem limpar o Tamanho
                $custTamanhoSelect.prop('disabled', false);
            });

            $custTamanhoSelect.on('change', function() {
                const tamanho = $(this).val();
                // Não limpa os valores, apenas exibe/esconde os campos de medida
                if (tamanho.toLowerCase().includes('cm')) {
                    $medidasContainer.removeClass('hidden');
                    $custTamanhoX.prop('disabled', false).attr('required', 'required');
                    $custTamanhoY.prop('disabled', false).attr('required', 'required');
                } else {
                    $medidasContainer.addClass('hidden');
                    $custTamanhoX.prop('disabled', true).removeAttr('required');
                    $custTamanhoY.prop('disabled', true).removeAttr('required');
                }
                
                updateFinalValue();
            });

            $custFormatacao.on('change', function() {
                const formatacao = $(this).val();
                $imageWarningMessage.toggleClass('hidden', formatacao !== 'Escrita');
            });

            $custTamanhoX.on('input', updateFinalValue);
            $custTamanhoY.on('input', updateFinalValue);

            // Chamada da função de inicialização ao carregar a página
            initializeForm();
        });
    </script>
@endpush
