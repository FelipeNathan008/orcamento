@extends('layouts.app')

@section('title', 'Criar Nova Customização')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins"> {{-- Contêiner principal para centralizar o formulário --}}

    {{-- Título e botão de voltar --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0 flex items-center">
            Criar Nova Customização

            {{-- Novo span para "Com Bolso" --}}
            <span id="bolso-indicator" class="ml-2 text-red-600 text-base font-semibold hidden">(Com Bolso)</span>
        </h1>
        <a href="{{ route('customizacao.index') }}"
            class="inline-flex items-center justify-center py-3 px-8 border border-gray-300 shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
            Voltar para a Lista
        </a>
    </div>
    <span id="product-name-display" class="ml-3 text-xl text-gray-600 font-normal"></span>
    {{-- Mensagens de sucesso e erro --}}
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

    <form action="{{ route('customizacao.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6"
        id="customizacaoForm">
        @csrf

        {{-- Campo de seleção para o Detalhe do Orçamento --}}
        <div>
            <label for="detalhes_orcamento_id_det" class="block text-sm font-medium text-custom-dark-text mb-1">Detalhes
                do Orçamento:</label>
            {{-- Três campos de busca divididos --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-2">
                <input type="text" id="search_orcamento_id"
                    class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    placeholder="Buscar ID do Orçamento">
                <input type="text" id="search_produto"
                    class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    placeholder="Buscar Produto">
                <input type="text" id="search_cliente"
                    class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    placeholder="Buscar Cliente">
            </div>
            <select name="detalhes_orcamento_id_det" id="detalhes_orcamento_id_det"
                class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                required>
                <option value="">Selecione um Detalhe de Orçamento</option>
                @foreach ($detalhesOrcamento as $detalhe)
                <option value="{{ $detalhe->id_det }}" {{ (old('detalhes_orcamento_id_det') == $detalhe->id_det || request('detalhe_id') == $detalhe->id_det) ? 'selected' : '' }}
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
                    {{ optional(optional($detalhe->orcamento)->clienteOrcamento)->clie_orc_nome ?? 'N/A' }}|
                    {{ optional($detalhe->produto)->prod_cod ?? 'N/A' }} |
                    {{ optional($detalhe->produto)->prod_nome ?? 'N/A' }} |
                    {{ optional($detalhe->produto)->prod_categoria ?? 'N/A' }} |
                    {{ optional($detalhe->produto)->prod_cor ?? 'N/A' }}
                    )
                </option>
                @endforeach
            </select>
            @error('detalhes_orcamento_id_det')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                {{-- Tipo de customização --}}
                <div>
                    <label for="cust_tipo" class="block text-sm font-medium text-custom-dark-text mb-1">Tipo:</label>
                    <select name="cust_tipo" id="cust_tipo"
                        class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        disabled>
                        <option value="">Selecione um Tipo</option>
                        @foreach($precos->unique('preco_tipo') as $tipo)
                        <option value="{{ $tipo->preco_tipo }}" {{ old('cust_tipo') == $tipo->preco_tipo ? 'selected' : '' }}>{{ $tipo->preco_tipo }}
                        </option>
                        @endforeach
                    </select>
                    @error('cust_tipo')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Local da customização --}}
                <div class="mt-6">
                    <label for="cust_local" class="block text-sm font-medium text-custom-dark-text mb-1">Local:</label>
                    <select name="cust_local" id="cust_local"
                        class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        disabled>
                        <option value="">Selecione um Local</option>
                        <option value="Ombro" {{ old('cust_local') == 'Ombro' ? 'selected' : '' }}>Ombro</option>
                        <option value="Frente" {{ old('cust_local') == 'Frente' ? 'selected' : '' }}>Frente</option>
                        <option value="Costa" {{ old('cust_local') == 'Costa' ? 'selected' : '' }}>Costa</option>
                    </select>
                    @error('cust_local')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                {{-- Posição --}}
                <div>
                    <label for="cust_posicao"
                        class="block text-sm font-medium text-custom-dark-text mb-1">Posição:</label>
                    <select name="cust_posicao" id="cust_posicao"
                        class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        disabled>
                        <option value="">Selecione uma Posição</option>
                        <option value="Direito" {{ old('cust_posicao') == 'Direito' ? 'selected' : '' }}>Direito</option>
                        <option value="Esquerdo" {{ old('cust_posicao') == 'Esquerdo' ? 'selected' : '' }}>Esquerdo
                        </option>
                        <option value="Topo" {{ old('cust_posicao') == 'Topo' ? 'selected' : '' }} class="hidden">Topo
                        </option>
                        <option value="Centro" {{ old('cust_posicao') == 'Centro' ? 'selected' : '' }} class="hidden">
                            Centro</option>
                        <option value="Rodapé" {{ old('cust_local') == 'Rodapé' ? 'selected' : '' }} class="hidden">Rodapé
                        </option>
                    </select>
                    @error('cust_posicao')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tamanho e Medidas --}}
                <div class="mt-6">
                    <label for="cust_tamanho_select_part"
                        class="block text-sm font-medium text-custom-dark-text mb-1">Tamanho:</label>
                    <select id="cust_tamanho_select_part"
                        class="block w-full px-4 py-2 h-10 bg-white text-gray-900 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        disabled>
                        <option value="">Selecione...</option>
                    </select>

                    {{-- Container para os campos de medidas (largura x altura) --}}
                    <div id="medidas_container" class="hidden mt-2">
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">Medidas:</label>
                        <div class="flex space-x-4">
                            <div class="relative w-1/2">
                                <input type="number" step="0.1" name="cust_largura" id="cust_tamanho_numeric_part_x"
                                    class="w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                                    placeholder="Largura (cm)" disabled min="0.1">
                            </div>
                            <div class="relative w-1/2">
                                <input type="number" step="0.1" name="cust_altura" id="cust_tamanho_numeric_part_y"
                                    class="w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                                    placeholder="Altura (cm)" disabled min="0.1">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="cust_tamanho" id="cust_tamanho_final_value"
                        value="{{ old('cust_tamanho') }}">
                    @error('cust_tamanho')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Formatação e Valor --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label for="cust_formatacao"
                    class="block text-sm font-medium text-custom-dark-text mb-1">Formatação:</label>
                <select name="cust_formatacao" id="cust_formatacao"
                    class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    disabled>
                    <option value="">Selecione uma Formatação</option>
                    <option value="Imagem" {{ old('cust_formatacao') == 'Imagem' ? 'selected' : '' }}>Imagem</option>
                    <option value="Escrita" {{ old('cust_formatacao') == 'Escrita' ? 'selected' : '' }}>Escrita
                    </option>
                </select>
                @error('cust_formatacao')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="cust_valor" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Valor da Customização (R$)
                </label>
                <input type="text" name="cust_valor" id="cust_valor"
                    class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    placeholder="Ex: 50,00" value="{{ old('cust_valor') }}" disabled readonly>
                @error('cust_valor')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Descrição e Imagem --}}
        <div class="mt-6">
            <label for="cust_descricao" class="block text-sm font-medium text-custom-dark-text mb-1">Descrição:</label>
            <textarea name="cust_descricao" id="cust_descricao" rows="3"
                class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                placeholder="Informações adicionais sobre a customização..." maxlength="90"
                disabled>{{ old('cust_descricao') }}</textarea>
            @error('cust_descricao')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <label for="cust_imagem" class="block text-sm font-medium text-custom-dark-text mb-1">Imagem da
                Customização:</label>
            {{-- Mensagem de aviso para formatação 'Escrita' --}}
            <div id="image-warning-message" class="mt-2 text-sm text-yellow-600 hidden">
                <i class="fas fa-exclamation-triangle mr-1"></i>A formatação 'Escrita' ainda depende da disponibilização
                de uma imagem que contenha o texto presumido.
            </div>
            <input type="file" name="cust_imagem" id="cust_imagem"
                class="block w-full px-4 py-2 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out text-custom-dark-text
                                                                                                                file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-blue-700 hover:file:bg-gray-200"
                disabled>
            @error('cust_imagem')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col items-center mt-8">
            <button type="submit" id="submitButton"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D; focus:ring-color: #EA792D;">
                SALVAR CUSTOMIZAÇÃO
            </button>
        </div>
        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            <strong>Erro!</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
            {{ session('error') }}
        </div>
        @endif

        @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
            {{ session('success') }}
        </div>
        @endif

    </form>

    {{-- Tabela de Customizações Existentes --}}
    <div class="mt-10">
        <h2 class="text-2xl font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4">
            Customizações Existentes para este Detalhe
        </h2>
        <div id="exampleTableContainer" class="w-full rounded-lg shadow-xl mb-4 overflow-x-auto border border-gray-200">
            <table class="min-w-full w-full divide-y divide-gray-200" id="customizationsTable">
                <thead class="bg-gray-700">
                    <tr>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins rounded-tl-lg">
                            ID Customização
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                            ID Detalhe
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                            Orçamento ID
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                            Nome do Cliente
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                            Produto
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                            Tipo Customização
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                            Local
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                            Posição
                        </th>
                        <th scope="col"
                            class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins rounded-tr-lg">
                            Ações
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="customizationsTableBody">
                    {{-- O conteúdo será preenchido via JavaScript na carga inicial e ao mudar o detalhe --}}
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(document).ready(function() {
        // ===============================================
        // Seletores de Elementos
        // ===============================================
        const $detalhesOrcamentoIdDet = $('#detalhes_orcamento_id_det');
        const $searchOrcamentoId = $('#search_orcamento_id');
        const $searchProduto = $('#search_produto');
        const $searchCliente = $('#search_cliente');
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
        const precosData = @json($precos);

        // Agrupa campos em etapas
        const steps = {
            tipo: $custTipo,
            local: $custLocal,
            posicao: $custPosicao,
            tamanho: $custTamanhoSelect,
            formatacao: $custFormatacao,
            descricao: $custDescricao,
            imagem: $custImagem,
            medidas: $custTamanhoX.add($custTamanhoY),
            valor: $custValor
        };

        // ===============================================
        // Funções de Utilitário
        // ===============================================

        function resetFields(startFrom) {
            let shouldReset = false;
            for (const key in steps) {
                if (key === startFrom) {
                    shouldReset = true;
                }
                if (shouldReset && key !== 'valor') {
                    steps[key].prop('disabled', true).val('').removeAttr('required');
                }
            }
            $medidasContainer.addClass('hidden');
            $imageWarningMessage.addClass('hidden');
            steps.valor.prop('disabled', true).val('');
        }

        function formatCurrency(value) {
            return new Intl.NumberFormat('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value);
        }

        function updateCustomizacaoValor() {
            const tipo = $custTipo.val();
            let tamanho = $custTamanhoSelect.val();

            if (!tamanho && $custTamanhoFinal.val()) {
                tamanho = $custTamanhoFinal.val().split(':')[0];
            }

            if (tipo && tamanho) {
                const precoEncontrado = precosData.find(preco =>
                    preco.preco_tipo === tipo && preco.preco_tamanho === tamanho
                );

                if (precoEncontrado) {
                    const valorFormatado = formatCurrency(parseFloat(precoEncontrado.preco_valor));
                    $custValor.val(valorFormatado);
                    $custValor.prop('disabled', false);
                } else {
                    $custValor.val('');
                    $custValor.prop('disabled', true);
                }
            } else {
                $custValor.val('');
                $custValor.prop('disabled', true);
            }
        }

        function updateFinalValue() {
            const tamanhoSelecionado = $custTamanhoSelect.val();
            const largura = $custTamanhoX.val().replace(',', '.');
            const altura = $custTamanhoY.val().replace(',', '.');

            if (tamanhoSelecionado.toLowerCase().includes('cm') && largura && altura) {
                $custTamanhoFinal.val(`${tamanhoSelecionado}: ${largura.replace('.', ',')}x${altura.replace('.', ',')}cm`);
            } else {
                $custTamanhoFinal.val(tamanhoSelecionado);
            }
            updateCustomizacaoValor();
        }

        // ===============================================
        // Lógica de Eventos
        // ===============================================
        $custValor.mask('000.000.000.000.000,00', {
            reverse: true
        });

        function filterDetalhesOrcamento() {
            const orcamentoIdTerm = $searchOrcamentoId.val().toLowerCase();
            const produtoTerm = $searchProduto.val().toLowerCase();
            const clienteTerm = $searchCliente.val().toLowerCase();

            $detalhesOrcamentoIdDet.find('option').each(function() {
                const option = $(this);
                const orcamentoId = option.data('orcamento-id')?.toString().toLowerCase() || '';
                const produto = option.data('product-name')?.toString().toLowerCase() || '';
                const cliente = option.data('client-name')?.toString().toLowerCase() || '';

                const matchesOrcamentoId = orcamentoId.includes(orcamentoIdTerm);
                const matchesProduto = produto.includes(produtoTerm);
                const matchesCliente = cliente.includes(clienteTerm);

                if (matchesOrcamentoId && matchesProduto && matchesCliente) {
                    option.show();
                } else {
                    option.hide();
                }
            });

            $detalhesOrcamentoIdDet.find('option[value=""]').show();
        }

        $detalhesOrcamentoIdDet.on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const idDetalhe = selectedOption.val();

            if (idDetalhe) {
                resetFields('tipo');
                steps.tipo.prop('disabled', false).attr('required', 'required');
                $productNameDisplay.text(
                    selectedOption.data('product-code') + ' - ' +
                    selectedOption.data('product-categ') + ' - ' +
                    selectedOption.data('product-cor') + ' - ' +
                    selectedOption.data('product-tam')
                );
                const detCaract = selectedOption.data('det-caract');
                if (detCaract && detCaract.toLowerCase().includes('com bolso')) {
                    $bolsoIndicator.removeClass('hidden');
                } else {
                    $bolsoIndicator.addClass('hidden');
                }
                // TODO: Fazer a chamada AJAX para buscar e preencher a tabela de customizações
            } else {
                resetFields('tipo');
                $productNameDisplay.text('');
                $bolsoIndicator.addClass('hidden');
            }
        });

        $custTipo.on('change', function() {
            const tipo = $(this).val();
            resetFields('local');
            $custTamanhoSelect.empty().append('<option value="">Selecione...</option>');
            if (tipo) {
                const tamanhosUnicos = precosData
                    .filter(preco => preco.preco_tipo === tipo)
                    .map(preco => preco.preco_tamanho)
                    .filter((value, index, self) => self.indexOf(value) === index);
                tamanhosUnicos.forEach(tamanho => {
                    $custTamanhoSelect.append(`<option value="${tamanho}">${tamanho}</option>`);
                });
                steps.local.prop('disabled', false).attr('required', 'required');
            }
        });

        $custLocal.on('change', function() {
            const local = $(this).val();
            resetFields('posicao');
            if (local) {
                steps.posicao.prop('disabled', false).attr('required', 'required');
            }
            $custPosicao.find('option').addClass('hidden');
            $custPosicao.find('option[value=""]').removeClass('hidden');

            if (local === 'Ombro') {
                $custPosicao.find('option[value="Direito"], option[value="Esquerdo"]').removeClass('hidden');
            } else if (local === 'Frente') {
                $custPosicao.find('option[value="Direito"], option[value="Esquerdo"], option[value="Centro"]').removeClass('hidden');
            } else if (local === 'Costa') {
                $custPosicao.find('option[value="Topo"], option[value="Centro"], option[value="Rodapé"]').removeClass('hidden');
            }
        });

        $custPosicao.on('change', function() {
            const posicao = $(this).val();
            resetFields('tamanho');
            if (posicao) {
                steps.tamanho.prop('disabled', false).attr('required', 'required');
            }
        });

        $custTamanhoSelect.on('change', function() {
            const tamanho = $(this).val();
            resetFields('formatacao');
            if (tamanho) {
                const hasMeasures = tamanho.toLowerCase().includes('cm');
                if (hasMeasures) {
                    $medidasContainer.removeClass('hidden');
                    steps.medidas.prop('disabled', false).attr('required', 'required');
                } else {
                    steps.medidas.prop('disabled', true).removeAttr('required').val('');
                }
                steps.formatacao.prop('disabled', false).attr('required', 'required');
                updateFinalValue();
            }
        });

        $custFormatacao.on('change', function() {
            const formatacao = $(this).val();
            steps.descricao.prop('disabled', false).attr('required', 'required');
            steps.imagem.prop('disabled', false).attr('required', 'required');
            if (formatacao === 'Escrita') {
                $imageWarningMessage.removeClass('hidden');
            } else {
                $imageWarningMessage.addClass('hidden');
            }
        });

        $custTamanhoX.on('input', updateFinalValue);
        $custTamanhoY.on('input', updateFinalValue);
        $searchOrcamentoId.on('keyup', filterDetalhesOrcamento);
        $searchProduto.on('keyup', filterDetalhesOrcamento);
        $searchCliente.on('keyup', filterDetalhesOrcamento);

        // Dispara o filtro na carga inicial para lidar com valores pré-selecionados
        filterDetalhesOrcamento();
        if ($detalhesOrcamentoIdDet.val()) {
            $detalhesOrcamentoIdDet.trigger('change');
        }
    });
</script>
@endpush