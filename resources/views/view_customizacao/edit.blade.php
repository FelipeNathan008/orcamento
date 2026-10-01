@extends('layouts.app')

@section('title', 'Editar Customização')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Editar Customização" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        @if(isset($detalhe))
        <div class="px-6 sm:px-8 pt-6">
            <x-info-card
                title="Informações do Produto"
                :name="'Produto: ' . ($detalhe->det_nome) . ' | Código: ' . ($detalhe->det_cod)"
                type="Produto"
                :fields="[
                ['label' => 'Cód. Interno', 'value' => $detalhe->orcamento->orc_cod_interno ?: 'Não informado', 'bold' => true],
                ['label' => 'Cód. Fábrica', 'value' => $detalhe->orcamento->orc_cod_fabrica ?: 'Não informado', 'bold' => true],
                ['label' => 'Cliente', 'value' => $detalhe->orcamento->clienteOrcamento->clie_orc_nome ?? 'Não informado', 'break' => true],
                ['label' => 'Código', 'value' => $detalhe->det_cod ?: 'Não informado', 'break' => true],
                ['label' => 'Categoria', 'value' => $detalhe->det_categoria ?: 'Não informado'],
                ['label' => 'Cor / Tamanho', 'value' => trim(($detalhe->det_cor ?: 'N/I') . ' / ' . ($detalhe->det_tamanho ?: 'N/I'))],
                ['label' => 'Características', 'value' => $detalhe->det_caract ?: 'Não informado', 'break' => true],
                ['label' => 'Quantidade', 'value' => $detalhe->det_quantidade ?? '0'],
            ]" />
        </div>
        @endif

        <form id="customizacaoForm" action="{{ route('customizacao.update', CryptHelper::encrypt($customizacao->id_customizacao)) }}" method="POST" enctype="multipart/form-data" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            @method('PUT')
            <input type="hidden" name="return_url" value="{{ $urlVoltar }}">
            
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 mt-2">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados da customização</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Atualize as informações da customização do produto.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="cust_tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tipo</label>
                        <select name="cust_tipo" id="cust_tipo" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                            <option value="">Selecione um tipo</option>
                            @foreach($precos->unique('preco_tipo') as $tipo)
                            <option value="{{ $tipo->preco_tipo }}" {{ old('cust_tipo', $customizacao->cust_tipo) == $tipo->preco_tipo ? 'selected' : '' }}>{{ $tipo->preco_tipo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="cust_local" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Local</label>
                        <select name="cust_local" id="cust_local" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                            <option value="">Selecione um local</option>
                            <option value="Ombro" {{ old('cust_local', $customizacao->cust_local) == 'Ombro' ? 'selected' : '' }}>Ombro</option>
                            <option value="Frente" {{ old('cust_local', $customizacao->cust_local) == 'Frente' ? 'selected' : '' }}>Frente</option>
                            <option value="Costa" {{ old('cust_local', $customizacao->cust_local) == 'Costa' ? 'selected' : '' }}>Costa</option>
                        </select>
                    </div>

                    <div>
                        <label for="cust_posicao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Posição</label>
                        <select name="cust_posicao" id="cust_posicao" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                            <option value="">Selecione uma posição</option>
                            <option value="Direito" {{ old('cust_posicao', $customizacao->cust_posicao) == 'Direito' ? 'selected' : '' }}>Direito</option>
                            <option value="Esquerdo" {{ old('cust_posicao', $customizacao->cust_posicao) == 'Esquerdo' ? 'selected' : '' }}>Esquerdo</option>
                            <option value="Topo" {{ old('cust_posicao', $customizacao->cust_posicao) == 'Topo' ? 'selected' : '' }}>Topo</option>
                            <option value="Centro" {{ old('cust_posicao', $customizacao->cust_posicao) == 'Centro' ? 'selected' : '' }}>Centro</option>
                            <option value="Rodapé" {{ old('cust_posicao', $customizacao->cust_posicao) == 'Rodapé' ? 'selected' : '' }}>Rodapé</option>
                        </select>
                    </div>

                    <div>
                        <label for="cust_tamanho_select_part" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tamanho</label>
                        <select id="cust_tamanho_select_part" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                            <option value="">Selecione um tamanho</option>
                        </select>

                        <div id="medidas_container" class="hidden mt-4">
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Medidas</label>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <input type="number" step="0.1" name="cust_largura" id="cust_tamanho_numeric_part_x" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" placeholder="Largura (cm)" min="0.1">
                                <input type="number" step="0.1" name="cust_altura" id="cust_tamanho_numeric_part_y" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" placeholder="Altura (cm)" min="0.1">
                            </div>
                        </div>

                        <input type="hidden" name="cust_tamanho" id="cust_tamanho_final_value" value="{{ old('cust_tamanho', $customizacao->cust_tamanho) }}">
                    </div>

                    <div>
                        <label for="cust_formatacao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Formatação</label>
                        <select name="cust_formatacao" id="cust_formatacao" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                            <option value="">Selecione uma formatação</option>
                            <option value="Imagem" {{ old('cust_formatacao', $customizacao->cust_formatacao) == 'Imagem' ? 'selected' : '' }}>Imagem</option>
                            <option value="Escrita" {{ old('cust_formatacao', $customizacao->cust_formatacao) == 'Escrita' ? 'selected' : '' }}>Escrita</option>
                        </select>
                    </div>

                    <div>
                        <label for="cust_valor" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Valor da Customização</label>
                        <input type="text" name="cust_valor" id="cust_valor" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" placeholder="R$ 0,00" value="{{ old('cust_valor', 'R$ ' . number_format($customizacao->cust_valor, 2, ',', '.')) }}" readonly required>
                    </div>

                    <div class="md:col-span-2">
                        <label for="cust_descricao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Descrição</label>
                        <textarea name="cust_descricao" id="cust_descricao" rows="3" maxlength="90" class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none" placeholder="Informações adicionais sobre a customização...">{{ old('cust_descricao', $customizacao->cust_descricao) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="cust_imagem" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Imagem da Customização</label>
                        <div id="image-warning-message" class="{{ old('cust_formatacao', $customizacao->cust_formatacao) === 'Escrita' ? '' : 'hidden' }} mb-3 text-xs text-yellow-600">
                            A formatação "Escrita" ainda depende da disponibilização de uma imagem que contenha o texto presumido.
                        </div>

                        @if($customizacao->cust_imagem)
                        <div class="mb-4">
                            <p class="text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Imagem atual</p>
                            <div class="inline-flex items-center gap-3 p-3 bg-white border border-gray-200 rounded-lg">
                                <img src="{{ asset('images_customizacoes/' . $customizacao->cust_imagem) }}" class="w-20 h-20 object-cover rounded-lg border border-gray-200 shadow-sm">
                                <div>
                                    <p class="text-xs text-gray-500">Imagem cadastrada</p>
                                    <p class="text-xs font-semibold text-gray-700 mt-1 break-all">{{ $customizacao->cust_imagem }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <input type="file" name="cust_imagem" id="cust_imagem" accept="image/png,image/jpeg,image/jpg,image/gif" class="block w-full text-sm text-gray-600 bg-white border border-gray-300 rounded-lg cursor-pointer file:mr-4 file:py-2.5 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 transition">

                        <p class="text-xs text-gray-500 mt-2">Selecione uma nova imagem somente se desejar substituir a imagem atual.</p>

                        <div id="preview_container" class="hidden mt-4">
                            <p class="text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Preview da nova imagem</p>
                            <img id="preview_imagem" class="max-h-40 rounded-lg border border-gray-200 shadow-sm">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="btnAtualizarCustomizacao" class="px-6">
                    <span id="textoAtualizarCustomizacao">Atualizar customização</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<div id="precos-data"
    data-precos="{{ base64_encode(json_encode($precos)) }}"
    data-tamanho="{{ old('cust_tamanho', $customizacao->cust_tamanho) }}"
    data-tipo="{{ old('cust_tipo', $customizacao->cust_tipo) }}"
    data-local="{{ old('cust_local', $customizacao->cust_local) }}"
    data-posicao="{{ old('cust_posicao', $customizacao->cust_posicao) }}"
    data-formatacao="{{ old('cust_formatacao', $customizacao->cust_formatacao) }}">
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('customizacaoForm');
        const btnAtualizar = document.getElementById('btnAtualizarCustomizacao');
        const textoAtualizar = document.getElementById('textoAtualizarCustomizacao');

        form.addEventListener('submit', function() {
            if (btnAtualizar.disabled) return false;
            btnAtualizar.disabled = true;
            textoAtualizar.textContent = 'Atualizando...';
            btnAtualizar.classList.add('opacity-70', 'cursor-not-allowed');
        });

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

        const precosElement = document.getElementById('precos-data');
        const precosData = JSON.parse(atob(precosElement.dataset.precos));
        const tamanhoSalvo = precosElement.dataset.tamanho || '';
        const tipoSalvo = precosElement.dataset.tipo || '';
        const localSalvo = precosElement.dataset.local || '';
        const posicaoSalva = precosElement.dataset.posicao || '';
        const formatacaoSalva = precosElement.dataset.formatacao || '';


        function formatCurrency(value) {
            return Number(value).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function updateCustomizacaoValor() {
            const tipo = $custTipo.val();
            let tamanho = $custTamanhoSelect.val();

            if (!tamanho && $custTamanhoFinal.val()) tamanho = $custTamanhoFinal.val().split(':')[0];

            if (tipo && tamanho) {
                const precoEncontrado = precosData.find(preco => preco.preco_tipo === tipo && preco.preco_tamanho === tamanho);
                if (precoEncontrado) $custValor.val(formatCurrency(parseFloat(precoEncontrado.preco_valor)));
            }
        }

        function updateFinalValue() {
            const tamanhoSelecionado = $custTamanhoSelect.val();
            const largura = $custTamanhoX.val().replace(',', '.');
            const altura = $custTamanhoY.val().replace(',', '.');

            if (tamanhoSelecionado && tamanhoSelecionado.toLowerCase().includes('cm') && largura && altura) {
                $custTamanhoFinal.val(`${tamanhoSelecionado}: ${largura.replace('.', ',')}x${altura.replace('.', ',')}cm`);
            } else {
                $custTamanhoFinal.val(tamanhoSelecionado);
            }

            updateCustomizacaoValor();
        }

        function preencherMedidas() {
            const tamanho = tamanhoSalvo || '';
            const match = tamanho.match(/:\s*([\d.,]+)x([\d.,]+)cm/i);

            if (match) {
                $custTamanhoX.val(match[1].replace(',', '.'));
                $custTamanhoY.val(match[2].replace(',', '.'));
                $medidasContainer.removeClass('hidden');
            }
        }

        function preencherTamanhos() {
            const tipo = $custTipo.val();
            const tamanhoAtual = tamanhoSalvo ? tamanhoSalvo.split(':')[0].trim() : '';

            $custTamanhoSelect.empty().append('<option value="">Selecione um tamanho</option>');

            if (!tipo) return;

            const tamanhosUnicos = precosData
                .filter(preco => preco.preco_tipo === tipo)
                .map(preco => preco.preco_tamanho)
                .filter((value, index, self) => self.indexOf(value) === index);

            tamanhosUnicos.forEach(tamanho => {
                const selected = tamanho === tamanhoAtual ? 'selected' : '';
                $custTamanhoSelect.append(`<option value="${tamanho}" ${selected}>${tamanho}</option>`);
            });
        }

        function atualizarPosicoes() {
            const local = $custLocal.val();

            $custPosicao.find('option').addClass('hidden');
            $custPosicao.find('option[value=""]').removeClass('hidden');

            if (local === 'Ombro') {
                $custPosicao.find('option[value="Direito"], option[value="Esquerdo"]').removeClass('hidden');
            } else if (local === 'Frente') {
                $custPosicao.find('option[value="Direito"], option[value="Esquerdo"], option[value="Centro"]').removeClass('hidden');
            } else if (local === 'Costa') {
                $custPosicao.find('option[value="Topo"], option[value="Centro"], option[value="Rodapé"]').removeClass('hidden');
            }

            if (posicaoSalva) $custPosicao.val(posicaoSalva);
        }

        function atualizarAvisoImagem() {
            if ($custFormatacao.val() === 'Escrita') $imageWarningMessage.removeClass('hidden');
            else $imageWarningMessage.addClass('hidden');
        }

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

        $custValor.mask('000.000.000.000.000,00', {
            reverse: true
        });

        $custTipo.on('change', function() {
            preencherTamanhos();
            $custTamanhoFinal.val('');
            $custTamanhoX.val('');
            $custTamanhoY.val('');
            $medidasContainer.addClass('hidden');
            updateCustomizacaoValor();
        });

        $custLocal.on('change', atualizarPosicoes);

        $custTamanhoSelect.on('change', function() {
            const tamanho = $(this).val();

            if (tamanho && tamanho.toLowerCase().includes('cm')) {
                $medidasContainer.removeClass('hidden');
                $custTamanhoX.prop('required', true);
                $custTamanhoY.prop('required', true);
            } else {
                $medidasContainer.addClass('hidden');
                $custTamanhoX.prop('required', false).val('');
                $custTamanhoY.prop('required', false).val('');
            }

            updateFinalValue();
        });

        $custFormatacao.on('change', atualizarAvisoImagem);
        $custTamanhoX.on('input', updateFinalValue);
        $custTamanhoY.on('input', updateFinalValue);

        $custTipo.val(tipoSalvo);
        preencherTamanhos();
        $custLocal.val(localSalvo);
        atualizarPosicoes();
        $custFormatacao.val(formatacaoSalva);

        const tamanhoSelecionado = $custTamanhoSelect.val();

        if (tamanhoSelecionado && tamanhoSelecionado.toLowerCase().includes('cm')) {
            $medidasContainer.removeClass('hidden');
            $custTamanhoX.prop('required', true);
            $custTamanhoY.prop('required', true);
            preencherMedidas();
        }

        atualizarAvisoImagem();
        updateCustomizacaoValor();

        if (tamanhoSalvo && tamanhoSalvo.includes(':')) {
            $custTamanhoFinal.val(tamanhoSalvo);
        }
    });
</script>

@endpush
@endsection