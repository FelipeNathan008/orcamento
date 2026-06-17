@extends('layouts.app')

@section('title', 'Editar Customização')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Editar Customização</h1>


    @if(isset($detalhe))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Produto
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
            <div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600">Cód. Fábrica</p>
                        <p class="font-semibold">
                            {{ $detalhe->orcamento->orc_cod_fabrica }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Cód. Interno</p>
                        <p class="font-semibold">
                            {{ $detalhe->orcamento->orc_cod_interno }}
                        </p>
                    </div>
                </div>
            </div>
            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold text-gray-900">
                    {{ $detalhe->orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Produto</p>
                <p class="font-semibold">
                    {{ $detalhe->produto->prod_cod ?? 'N/A' }} -
                    {{ $detalhe->produto->prod_nome ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Categoria</p>
                <p class="font-semibold text-gray-900">
                    {{ $detalhe->produto->prod_categoria ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Cor / Tamanho</p>
                <p class="font-semibold">
                    {{ $detalhe->produto->prod_cor ?? 'N/A' }} -
                    {{ $detalhe->det_tamanho ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Características</p>
                <p class="font-semibold">
                    {{ $detalhe->det_caract ?? 'N/A' }}
                </p>
            </div>

        </div>

    </div>
    @endif

    <x-alert-flash />

    <form action="{{ route('customizacao.update', ['customizacao' => $customizacao->id ?? $customizacao->id_customizacao]) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="customizacaoForm">
        @csrf
        @method('PUT')

        <input type="hidden" name="detalhes_orcamento_id_det" value="{{ $detalhe->id_det }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                {{-- Tipo de customização --}}
                <div>
                    <label for="cust_tipo" class="block text-sm font-medium text-custom-dark-text mb-1">Tipo:</label>
                    <select name="cust_tipo" id="cust_tipo" class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300">
                        <option value="">Selecione um Tipo</option>
                        @foreach($precos->unique('preco_tipo') as $tipo)
                        <option value="{{ $tipo->preco_tipo }}">{{ $tipo->preco_tipo }}</option>
                        @endforeach
                    </select>
                    @error('cust_tipo')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Local da customização --}}
                <div class="mt-6">
                    <label for="cust_local" class="block text-sm font-medium text-custom-dark-text mb-1">Local:</label>
                    <select name="cust_local" id="cust_local" class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300">
                        <option value="">Selecione um Local</option>
                        <option value="Ombro">Ombro</option>
                        <option value="Frente">Frente</option>
                        <option value="Costa">Costa</option>
                    </select>
                    @error('cust_local')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                {{-- Posição --}}
                <div>
                    <label for="cust_posicao" class="block text-sm font-medium text-custom-dark-text mb-1">Posição:</label>
                    <select name="cust_posicao" id="cust_posicao" class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300">
                        <option value="">Selecione uma Posição</option>
                        <option value="Direito">Direito</option>
                        <option value="Esquerdo">Esquerdo</option>
                        <option value="Topo" class="hidden">Topo</option>
                        <option value="Centro" class="hidden">Centro</option>
                        <option value="Rodapé" class="hidden">Rodapé</option>
                    </select>
                    @error('cust_posicao')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tamanho e Medidas --}}
                <div class="mt-6">
                    <label for="cust_tamanho_select_part" class="block text-sm font-medium text-custom-dark-text mb-1">Tamanho:</label>
                    <select id="cust_tamanho_select_part" class="block w-full px-4 py-2 h-10 bg-white text-gray-900 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300" name="_temp_cust_tamanho_select">
                        <option value="">Selecione...</option>
                    </select>

                    <div id="medidas_container" class="hidden mt-2">
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">Medidas:</label>
                        <div class="flex space-x-4">
                            <div class="relative w-1/2">
                                <input type="number" step="0.1" name="cust_largura" id="cust_tamanho_numeric_part_x" class="w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300" placeholder="Largura (cm)" min="0.1">
                            </div>
                            <div class="relative w-1/2">
                                <input type="number" step="0.1" name="cust_altura" id="cust_tamanho_numeric_part_y" class="w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300" placeholder="Altura (cm)" min="0.1">
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="cust_tamanho" id="cust_tamanho_final_value" value="{{ old('cust_tamanho', $customizacao->cust_tamanho) }}">
                    @error('cust_tamanho')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Formatação e Valor --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div>
                <label for="cust_formatacao" class="block text-sm font-medium text-custom-dark-text mb-1">Formatação:</label>
                <select name="cust_formatacao" id="cust_formatacao" class="block w-full px-4 py-2 h-10 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300">
                    <option value="">Selecione uma Formatação</option>
                    <option value="Imagem">Imagem</option>
                    <option value="Escrita">Escrita</option>
                </select>
                @error('cust_formatacao')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="cust_valor" class="block text-sm font-medium text-custom-dark-text mb-1">Valor da Customização (R$)</label>
                <input type="text" name="cust_valor" id="cust_valor" class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300" placeholder="Ex: 50,00" value="{{ old('cust_valor', number_format($customizacao->cust_valor ?? 0, 2, ',', '.')) }}">
                @error('cust_valor')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Descrição e Imagem --}}
        <div class="mt-6">
            <label for="cust_descricao" class="block text-sm font-medium text-custom-dark-text mb-1">Descrição:</label>
            <textarea name="cust_descricao" id="cust_descricao" rows="3" class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300" placeholder="Informações adicionais sobre a customização..." maxlength="90">{{ old('cust_descricao', $customizacao->cust_descricao) }}</textarea>
            @error('cust_descricao')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <label for="cust_imagem" class="block text-sm font-medium text-custom-dark-text mb-1">
                Imagem da Customização:
            </label>

            {{-- Imagem atual --}}
            <div class="mb-3">
                @if ($customizacao->cust_imagem)
                <p class="text-sm text-gray-600 mb-2">Imagem atual:</p>
                <img src="{{ asset('images_customizacoes/' . $customizacao->cust_imagem) }}"
                    alt="Preview"
                    class="max-w-xs max-h-40 rounded-md border">
                @endif
            </div>
            {{-- Aviso caso seja escrita --}}
            <div id="image-warning-message"
                class="mt-2 text-sm text-yellow-600 hidden">
                <i class="fas fa-exclamation-triangle mr-1"></i>
                A formatação 'Escrita' ainda depende da disponibilização de uma imagem que contenha o texto presumido.
            </div>
            {{-- Upload da nova imagem --}}
            <input type="file"
                name="cust_imagem"
                id="cust_imagem"
                accept="image/png,image/jpeg,image/jpg,image/webp"
                class="block w-full px-4 py-2 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus focus:ring-2 focus:ring-blue-500 transition duration-150 ease-in-out text-custom-dark-text
           file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-blue-700 hover:file:bg-gray-200">
            {{-- Erro de validação --}}
            @error('cust_imagem')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
            {{-- Preview da nova imagem --}}
            <div id="preview_container" class="hidden mt-3">
                <p class="text-sm text-gray-600 mb-2">Nova imagem selecionada:</p>
                <img id="preview_imagem" class="max-h-40 rounded border shadow">
            </div>
        </div>

        <div class="flex justify-center mt-8">
            <button type="submit" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">ATUALIZAR</button>
        </div>

        <div class="flex justify-center mb-8">
            <a href="{{ route('customizacao.index', ['id_det' => $detalhe->id_det]) }}" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">VOLTAR PARA A LISTA</a>
        </div>

    </form>

</div>
@endsection

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<div id="detalhe-data" data-prod-cod="{{ $detalhe->produto->prod_cod }}" data-prod-categoria="{{ $detalhe->produto->prod_categoria }}" data-prod-cor="{{ $detalhe->produto->prod_cor }}" data-tamanho="{{ $detalhe->det_tamanho }}" data-caract="{{ $detalhe->det_caract }}"></div>

<div id="precos-data" data-precos='@json($precos)'></div>

<div id="customizacao-data" data-cust='@json($customizacao)'></div>

<script>
    $(document).ready(function() {
        // Selectors
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

        const precosData = JSON.parse(document.getElementById('precos-data').dataset.precos);
        const custData = JSON.parse(document.getElementById('customizacao-data').dataset.cust);

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
        // Preview da nova imagem selecionada
        $custImagem.on('change', function(e) {

            const file = e.target.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function(event) {
                $('#preview_imagem').attr('src', event.target.result);
                $('#preview_container').removeClass('hidden');
            };

            reader.readAsDataURL(file);

        });

        function resetFields(startFrom) {
            let shouldReset = false;

            // campos que NÃO devem ser apagados quando mexer nos 4 primeiros
            const protectedFields = ['formatacao', 'descricao', 'imagem'];

            for (const key in steps) {

                if (key === startFrom) {
                    shouldReset = true;
                }

                // se for campo protegido não apagar
                if (protectedFields.includes(key)) {
                    continue;
                }

                if (shouldReset && key !== 'valor') {
                    steps[key].prop('disabled', true).val('').removeAttr('required');
                }
            }

            $medidasContainer.addClass('hidden');

            // valor ainda pode ser recalculado
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
                const precoEncontrado = precosData.find(preco => preco.preco_tipo === tipo && preco.preco_tamanho === tamanho);
                if (precoEncontrado) {
                    const valorFormatado = formatCurrency(parseFloat(precoEncontrado.preco_valor));
                    $custValor.val(valorFormatado);
                    $custValor.prop('disabled', false);
                } else {
                    $custValor.prop('disabled', false); // allow manual edit if no preset price
                }
            } else {
                $custValor.prop('disabled', false);
            }
        }

        function updateFinalValue() {
            const tamanhoSelecionado = $custTamanhoSelect.val();
            const largura = $custTamanhoX.val().replace(',', '.');
            const altura = $custTamanhoY.val().replace(',', '.');

            if (tamanhoSelecionado && tamanhoSelecionado.toLowerCase().includes('cm') && largura && altura) {
                $custTamanhoFinal.val(`${tamanhoSelecionado}: ${String(largura).replace('.', ',')}x${String(altura).replace('.', ',')}cm`);
            } else {
                $custTamanhoFinal.val(tamanhoSelecionado || '');
            }

            updateCustomizacaoValor();
        }

        // Mask
        $custValor.mask('000.000.000.000.000,00', {
            reverse: true
        });

        // Populate tipo options already present. Now populate tamanho options when tipo chosen
        function populateTamanhosForTipo(tipo) {
            $custTamanhoSelect.empty().append('<option value="">Selecione...</option>');
            if (!tipo) return;
            const tamanhosUnicos = precosData.filter(preco => preco.preco_tipo === tipo).map(preco => preco.preco_tamanho).filter((v, i, a) => a.indexOf(v) === i);
            tamanhosUnicos.forEach(t => $custTamanhoSelect.append(`<option value="${t}">${t}</option>`));
        }

        // Event handlers same as create
        $custTipo.on('change', function() {
            resetFields('local');
            const tipo = $(this).val();
            populateTamanhosForTipo(tipo);
            if (tipo) {
                $custLocal.prop('disabled', false).attr('required', 'required');
            }
            updateCustomizacaoValor();
        });

        $custLocal.on('change', function() {
            resetFields('posicao');
            const local = $(this).val();
            if (local) {
                $custPosicao.prop('disabled', false).attr('required', 'required');
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
            resetFields('tamanho');
            if ($(this).val()) {
                $custTamanhoSelect.prop('disabled', false).attr('required', 'required');
            }
        });

        $custTamanhoSelect.on('change', function() {
            const tamanho = $(this).val();
            resetFields('formatacao');
            if (tamanho) {
                const hasMeasures = tamanho.toLowerCase().includes('cm');
                if (hasMeasures) {
                    $medidasContainer.removeClass('hidden');
                    $custTamanhoX.prop('disabled', false).attr('required', 'required');
                    $custTamanhoY.prop('disabled', false).attr('required', 'required');
                } else {
                    $custTamanhoX.prop('disabled', true).removeAttr('required').val('');
                    $custTamanhoY.prop('disabled', true).removeAttr('required').val('');
                }
                $custFormatacao.prop('disabled', false).attr('required', 'required');
                updateFinalValue();
            }
        });

        $custFormatacao.on('change', function() {
            $custDescricao.prop('disabled', false).attr('required', 'required');
            $custImagem.prop('disabled', false);
            if ($(this).val() === 'Escrita') {
                $imageWarningMessage.removeClass('hidden');
            } else {
                $imageWarningMessage.addClass('hidden');
            }
        });

        $custTamanhoX.on('input', updateFinalValue);
        $custTamanhoY.on('input', updateFinalValue);

        // --- Prefill flow for edit ---
        // Start with all fields disabled to follow same flow
        resetFields('tipo');
        $custTipo.prop('disabled', false).attr('required', 'required');

        // If there is saved data, step through and trigger changes so UI behaves like create
        if (custData.cust_tipo) {
            $custTipo.val(custData.cust_tipo).trigger('change');
        }

        if (custData.cust_tipo) {
            // populate tamanhos
            populateTamanhosForTipo(custData.cust_tipo);
        }

        if (custData.cust_local) {
            $custLocal.val(custData.cust_local).prop('disabled', false).trigger('change');
        }

        if (custData.cust_posicao) {
            $custPosicao.val(custData.cust_posicao).prop('disabled', false).trigger('change');
        }

        // Handle tamanho that might include measurements
        if (custData.cust_tamanho) {
            // Example stored formats: "P", "GG", or "P: 10,0x15,0cm" or "P: 10,0x15cm". We split by ':'
            const parts = String(custData.cust_tamanho).split(':');
            const tamanhoBase = parts[0].trim();

            // choose option in select if available
            if (tamanhoBase) {
                // ensure tamanhos populated
                if (!$custTamanhoSelect.find(`option[value="${tamanhoBase}"]`).length) {
                    $custTamanhoSelect.append(`<option value="${tamanhoBase}">${tamanhoBase}</option>`);
                }
                $custTamanhoSelect.val(tamanhoBase).prop('disabled', false).trigger('change');
            }

            if (parts.length > 1) {
                // extract largura x altura from second part (eg " 10,0x15,0cm")
                const measures = parts[1].trim();
                // remove trailing 'cm'
                const clean = measures.replace(/cm/i, '').trim();
                // expect format like "10,0x15,0" or "10,0x15"
                const match = clean.match(/([0-9.,]+)x([0-9.,]+)/);
                if (match) {

                    const larg = match[1].replace(',', '.');
                    const alt = match[2].replace(',', '.');

                    $custTamanhoX.val(parseFloat(larg));
                    $custTamanhoY.val(parseFloat(alt));
                    $medidasContainer.removeClass('hidden');
                    $custTamanhoX.prop('disabled', false).attr('required', 'required');
                    $custTamanhoY.prop('disabled', false).attr('required', 'required');

                    updateFinalValue();
                }
            }
        }

        if (custData.cust_formatacao) {
            $custFormatacao.val(custData.cust_formatacao).prop('disabled', false).trigger('change');
        }

        if (custData.cust_descricao) {
            $custDescricao.val(custData.cust_descricao).prop('disabled', false);
        }

        // If there's an explicit value for valor from server, keep it formatted
        if (custData.cust_valor) {
            $custValor.val(custData.cust_valor);
        }

        // allow manual submit even if some price not found
        updateCustomizacaoValor();

    });
</script>
@endpush