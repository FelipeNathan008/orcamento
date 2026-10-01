@extends('layouts.app')

@section('title', 'Criar Nova Customização')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Cadastrar Customização" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        @if(isset($detalhe))
        <div class="px-6 sm:px-8 pt-6">
            <x-info-card
                title="Informações do Produto"
                :name="'Produto: ' . ($detalhe->det_nome).' | Código: '. ($detalhe->det_cod)"
                type="Produto"
                :fields="[
                    [
                        'label' => 'Cód. Interno',
                        'value' => $detalhe->orcamento->orc_cod_interno ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $detalhe->orcamento->orc_cod_fabrica ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cliente',
                        'value' => $detalhe->orcamento->clienteOrcamento->clie_orc_nome ?? 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Código',
                        'value' => $detalhe->det_cod ?: 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Categoria',
                        'value' => $detalhe->det_categoria ?: 'Não informado',
                    ],
                    [
                        'label' => 'Cor / Tamanho',
                        'value' => trim(($detalhe->det_cor ?: 'N/I') . ' / ' . ($detalhe->det_tamanho ?: 'N/I')),
                    ],
                    [
                        'label' => 'Características',
                        'value' => $detalhe->det_caract ?: 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Quantidade',
                        'value' => $detalhe->det_quantidade ?? '0',
                    ],
                ]" />
        </div>
        @endif

        <form id="customizacaoForm" action="{{ route('customizacao.store') }}" method="POST" enctype="multipart/form-data" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            <input type="hidden" name="detalhes_orcamento_id_det" value="{{ $detalhe->id_det }}">
            <input type="hidden" name="return_url" value="{{ $urlVoltar }}">

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados da customização</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Preencha as informações da customização do produto.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="cust_tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tipo</label>
                        <select name="cust_tipo" id="cust_tipo" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" disabled>
                            <option value="">Selecione um tipo</option>
                            @foreach($precos->unique('preco_tipo') as $tipo)
                            <option value="{{ $tipo->preco_tipo }}" {{ old('cust_tipo') == $tipo->preco_tipo ? 'selected' : '' }}>{{ $tipo->preco_tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="cust_local" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Local</label>
                        <select name="cust_local" id="cust_local" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" disabled>
                            <option value="">Selecione um local</option>
                            <option value="Ombro" {{ old('cust_local') == 'Ombro' ? 'selected' : '' }}>Ombro</option>
                            <option value="Frente" {{ old('cust_local') == 'Frente' ? 'selected' : '' }}>Frente</option>
                            <option value="Costa" {{ old('cust_local') == 'Costa' ? 'selected' : '' }}>Costa</option>
                        </select>
                    </div>

                    <div>
                        <label for="cust_posicao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Posição</label>
                        <select name="cust_posicao" id="cust_posicao" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" disabled>
                            <option value="">Selecione uma posição</option>
                            <option value="Direito" {{ old('cust_posicao') == 'Direito' ? 'selected' : '' }}>Direito</option>
                            <option value="Esquerdo" {{ old('cust_posicao') == 'Esquerdo' ? 'selected' : '' }}>Esquerdo</option>
                            <option value="Topo" {{ old('cust_posicao') == 'Topo' ? 'selected' : '' }} class="hidden">Topo</option>
                            <option value="Centro" {{ old('cust_posicao') == 'Centro' ? 'selected' : '' }} class="hidden">Centro</option>
                            <option value="Rodapé" {{ old('cust_posicao') == 'Rodapé' ? 'selected' : '' }} class="hidden">Rodapé</option>
                        </select>
                    </div>

                    <div>
                        <label for="cust_tamanho_select_part" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tamanho</label>
                        <select id="cust_tamanho_select_part" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" disabled>
                            <option value="">Selecione um tamanho</option>
                        </select>

                        <div id="medidas_container" class="hidden mt-4">
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Medidas</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <input type="number" step="0.1" name="cust_largura" id="cust_tamanho_numeric_part_x" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" placeholder="Largura (cm)" disabled min="0.1">
                                <input type="number" step="0.1" name="cust_altura" id="cust_tamanho_numeric_part_y" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" placeholder="Altura (cm)" disabled min="0.1">
                            </div>
                        </div>

                        <input type="hidden" name="cust_tamanho" id="cust_tamanho_final_value" value="{{ old('cust_tamanho') }}">
                    </div>

                    <div>
                        <label for="cust_formatacao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Formatação</label>
                        <select name="cust_formatacao" id="cust_formatacao" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" disabled>
                            <option value="">Selecione uma formatação</option>
                            <option value="Imagem" {{ old('cust_formatacao') == 'Imagem' ? 'selected' : '' }}>Imagem</option>
                            <option value="Escrita" {{ old('cust_formatacao') == 'Escrita' ? 'selected' : '' }}>Escrita</option>
                        </select>
                    </div>

                    <div>
                        <label for="cust_valor" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Valor da Customização</label>
                        <input type="text" name="cust_valor" id="cust_valor" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" placeholder="R$ 0,00" value="{{ old('cust_valor') }}" disabled readonly>
                    </div>

                    <div class="md:col-span-2">
                        <label for="cust_descricao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Descrição</label>
                        <textarea name="cust_descricao" id="cust_descricao" rows="3" maxlength="90" class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none" placeholder="Informações adicionais sobre a customização..." disabled>{{ old('cust_descricao') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="cust_imagem" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Imagem da Customização</label>
                        <div id="image-warning-message" class="hidden mb-3 text-xs text-yellow-600">
                            A formatação "Escrita" ainda depende da disponibilização de uma imagem que contenha o texto presumido.
                        </div>
                        <input type="file" name="cust_imagem" id="cust_imagem" accept="image/png,image/jpeg,image/jpg,image/gif" class="block w-full text-sm text-gray-600 bg-white border border-gray-300 rounded-lg cursor-pointer file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition" disabled>

                        <div id="preview_container" class="hidden mt-4">
                            <p class="text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Preview da imagem</p>
                            <img id="preview_imagem" class="max-h-40 rounded-lg border border-gray-200 shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="btnSalvarCustomizacao" class="px-6">
                    <span id="textoSalvarCustomizacao">Salvar customização</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<div id="detalhe-data"
    data-prod-cod="{{ $detalhe->det_cod }}"
    data-prod-categoria="{{ $detalhe->det_categoria }}"
    data-prod-cor="{{ $detalhe->det_cor }}"
    data-tamanho="{{ $detalhe->det_tamanho }}"
    data-caract="{{ $detalhe->det_caract }}"
    data-old-tipo="{{ old('cust_tipo') }}"
    data-old-local="{{ old('cust_local') }}"
    data-old-posicao="{{ old('cust_posicao') }}"
    data-old-tamanho="{{ old('cust_tamanho') }}"
    data-old-formatacao="{{ old('cust_formatacao') }}"
    data-old-valor="{{ old('cust_valor') }}"
    data-old-descricao="{{ old('cust_descricao') }}"
    data-old-largura="{{ old('cust_largura') }}"
    data-old-altura="{{ old('cust_altura') }}">
</div>

<div id="precos-data" data-precos="{{ $precos->toJson() }}"></div>

<div id="precos-data" data-precos='@json($precos)'></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('customizacaoForm');
        const btnSalvar = document.getElementById('btnSalvarCustomizacao');
        const textoSalvar = document.getElementById('textoSalvarCustomizacao');
        const detalheData = document.getElementById('detalhe-data');

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
        const oldValues = {
            tipo: detalheData.dataset.oldTipo,
            local: detalheData.dataset.oldLocal,
            posicao: detalheData.dataset.oldPosicao,
            tamanho: detalheData.dataset.oldTamanho,
            formatacao: detalheData.dataset.oldFormatacao,
            valor: detalheData.dataset.oldValor,
            descricao: detalheData.dataset.oldDescricao,
            largura: detalheData.dataset.oldLargura,
            altura: detalheData.dataset.oldAltura
        };

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

        form.addEventListener('submit', function() {
            if (btnSalvar.disabled) return false;
            btnSalvar.disabled = true;
            textoSalvar.textContent = 'Salvando...';
            btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
        });

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
            for (const key in steps) {
                if (key === startFrom) shouldReset = true;
                if (shouldReset && !['formatacao', 'descricao', 'imagem'].includes(key) && key !== 'valor') {
                    steps[key].prop('disabled', true).val('').removeAttr('required');
                }
            }
            $medidasContainer.addClass('hidden');
            steps.valor.prop('disabled', true).val('');
        }

        function formatCurrency(value) {
            return Number(value).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
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
                    $custValor.val(formatCurrency(parseFloat(precoEncontrado.preco_valor))).prop('disabled', false);
                } else {
                    $custValor.val('').prop('disabled', true);
                }
            } else {
                $custValor.val('').prop('disabled', true);
            }
        }

        function updateFinalValue() {
            const tamanhoSelecionado = $custTamanhoSelect.val() || '';
            const largura = $custTamanhoX.val().replace(',', '.');
            const altura = $custTamanhoY.val().replace(',', '.');

            if (tamanhoSelecionado.toLowerCase().includes('cm') && largura && altura) {
                $custTamanhoFinal.val(`${tamanhoSelecionado}: ${largura.replace('.', ',')}x${altura.replace('.', ',')}cm`);
            } else {
                $custTamanhoFinal.val(tamanhoSelecionado);
            }

            updateCustomizacaoValor();
        }

        function carregarTamanhos(tipo, tamanhoSelecionado = '') {
            $custTamanhoSelect.empty().append('<option value="">Selecione um tamanho</option>');

            if (!tipo) return;

            const tamanhosUnicos = precosData
                .filter(preco => preco.preco_tipo === tipo)
                .map(preco => preco.preco_tamanho)
                .filter((value, index, self) => self.indexOf(value) === index);

            tamanhosUnicos.forEach(tamanho => {
                $custTamanhoSelect.append(`<option value="${tamanho}">${tamanho}</option>`);
            });

            if (tamanhoSelecionado) {
                const tamanhoBase = tamanhoSelecionado.split(':')[0].trim();
                $custTamanhoSelect.val(tamanhoBase);
            }
        }

        function carregarPosicoes(local, posicaoSelecionada = '') {
            $custPosicao.find('option').addClass('hidden');
            $custPosicao.find('option[value=""]').removeClass('hidden');

            if (local === 'Ombro') {
                $custPosicao.find('option[value="Direito"], option[value="Esquerdo"]').removeClass('hidden');
            } else if (local === 'Frente') {
                $custPosicao.find('option[value="Direito"], option[value="Esquerdo"], option[value="Centro"]').removeClass('hidden');
            } else if (local === 'Costa') {
                $custPosicao.find('option[value="Topo"], option[value="Centro"], option[value="Rodapé"]').removeClass('hidden');
            }

            if (posicaoSelecionada) $custPosicao.val(posicaoSelecionada);
        }

        function restaurarFormulario() {
            if (!oldValues.tipo) {
                $custTipo.prop('disabled', false).attr('required', 'required');
                return;
            }

            $custTipo.val(oldValues.tipo).prop('disabled', false).attr('required', 'required');

            carregarTamanhos(oldValues.tipo, oldValues.tamanho);

            if (oldValues.local) {
                $custLocal.val(oldValues.local).prop('disabled', false).attr('required', 'required');
                carregarPosicoes(oldValues.local, oldValues.posicao);
            }

            if (oldValues.posicao) {
                $custPosicao.prop('disabled', false).attr('required', 'required');
                $custTamanhoSelect.prop('disabled', false).attr('required', 'required');
            }

            if (oldValues.tamanho) {
                const tamanhoBase = oldValues.tamanho.split(':')[0].trim();
                $custTamanhoSelect.val(tamanhoBase).prop('disabled', false).attr('required', 'required');

                if (tamanhoBase.toLowerCase().includes('cm')) {
                    $medidasContainer.removeClass('hidden');
                    $custTamanhoX.prop('disabled', false).attr('required', 'required').val(oldValues.largura);
                    $custTamanhoY.prop('disabled', false).attr('required', 'required').val(oldValues.altura);
                }

                $custTamanhoFinal.val(oldValues.tamanho);
                $custFormatacao.prop('disabled', false).attr('required', 'required');
                updateCustomizacaoValor();
            }

            if (oldValues.formatacao) {
                $custFormatacao.val(oldValues.formatacao).prop('disabled', false).attr('required', 'required');
                $custDescricao.prop('disabled', false).attr('required', 'required').val(oldValues.descricao);
                $custImagem.prop('disabled', false).attr('required', 'required');
                $imageWarningMessage.toggleClass('hidden', oldValues.formatacao !== 'Escrita');
            }
        }

        $custValor.mask('000.000.000.000.000,00', {
            reverse: true
        });

        $custTipo.on('change', function() {
            const tipo = $(this).val();
            resetFields('local');
            carregarTamanhos(tipo);

            if (tipo) {
                $custLocal.prop('disabled', false).attr('required', 'required');
            }
        });

        $custLocal.on('change', function() {
            const local = $(this).val();
            resetFields('posicao');

            if (local) $custPosicao.prop('disabled', false).attr('required', 'required');
            carregarPosicoes(local);
        });

        $custPosicao.on('change', function() {
            const posicao = $(this).val();
            resetFields('tamanho');

            if (posicao) $custTamanhoSelect.prop('disabled', false).attr('required', 'required');
        });

        $custTamanhoSelect.on('change', function() {
            const tamanho = $(this).val();
            resetFields('formatacao');

            if (!tamanho) return;

            if (tamanho.toLowerCase().includes('cm')) {
                $medidasContainer.removeClass('hidden');
                steps.medidas.prop('disabled', false).attr('required', 'required');
            } else {
                steps.medidas.prop('disabled', true).removeAttr('required').val('');
            }

            $custFormatacao.prop('disabled', false).attr('required', 'required');
            updateFinalValue();
        });

        $custFormatacao.on('change', function() {
            const formatacao = $(this).val();

            $custDescricao.prop('disabled', false).attr('required', 'required');
            $custImagem.prop('disabled', false).attr('required', 'required');
            $imageWarningMessage.toggleClass('hidden', formatacao !== 'Escrita');
        });

        $custTamanhoX.on('input', updateFinalValue);
        $custTamanhoY.on('input', updateFinalValue);

        restaurarFormulario();
    });
</script>
@endpush
@endsection