@extends('layouts.app')

@section('title', 'Layout da Camisa')

@php
use App\Helpers\CryptHelper;

$detalhe = $customizacao->detalhesOrcamento;
$orcamento = $detalhe->orcamento;
$cliente = $orcamento->clienteOrcamento;

$orcamentoBloqueado = in_array(
strtolower(trim($orcamento->orc_status ?? '')),
['aprovado', 'finalizado', 'rejeitado']
);

$urlVoltar = $urlVoltar ?? route('customizacao.index', [
'id' => CryptHelper::encrypt($detalhe->id_det),
]);
@endphp

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header
            title="Layout da Camisa"
            :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-6 pb-8">

            <x-info-card
                title="Informações do Produto"
                :name="'Produto: ' . ($detalhe->det_nome ?: 'Não informado') . ' | Código: ' . ($detalhe->det_cod ?: 'Não informado')"
                type="Produto"
                :fields="[
                    [
                        'label' => 'Cód. Interno',
                        'value' => $orcamento->orc_cod_interno ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $orcamento->orc_cod_fabrica ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cliente',
                        'value' => $cliente->clie_orc_nome ?? 'Não informado',
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
                    [
                        'label' => 'Status do Orçamento',
                        'value' => ucfirst($orcamento->orc_status ?? 'Não informado'),
                    ],
                ]" />

            <div class="mt-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Customizações do Produto</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Visualize e gerencie as customizações utilizadas no layout da camisa.</p>
                    </div>

                    <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                        <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                        <span class="text-xs font-semibold text-orange-700">
                            {{ $allCustomizacoesForDetail->count() }} customização(ões)
                        </span>
                    </div>
                </div>

                @if($allCustomizacoesForDetail->isNotEmpty())
                <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead style="background-color:#343A40;">
                                <tr>
                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Local</th>
                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Posição</th>
                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Tipo</th>
                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Tamanho</th>
                                    <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Imagem</th>
                                    <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($allCustomizacoesForDetail as $cust)
                                <tr class="group transition hover:bg-orange-50/40">
                                    <td class="px-5 py-5">
                                        <span class="text-sm text-gray-700">{{ $cust->cust_local ?: 'Não informado' }}</span>
                                    </td>
                                    <td class="px-5 py-5">
                                        <span class="text-sm font-semibold text-gray-800">{{ $cust->cust_posicao ?: 'Não informado' }}</span>
                                    </td>
                                    <td class="px-5 py-5">
                                        <span class="text-sm text-gray-700">{{ $cust->cust_tipo ?: 'Não informado' }}</span>
                                    </td>
                                    <td class="px-5 py-5">
                                        <span class="text-sm text-gray-700">{{ $cust->cust_tamanho ?: 'Não informado' }}</span>
                                    </td>
                                    <td class="px-5 py-5 text-center">
                                        @if($cust->cust_imagem)
                                        <img
                                            src="{{ asset('images_customizacoes/' . $cust->cust_imagem) }}"
                                            alt="Imagem da customização"
                                            class="w-14 h-14 object-cover rounded-lg border border-gray-200 shadow-sm mx-auto">
                                        @else
                                        <span class="text-xs text-gray-500">Sem imagem</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-5 text-center whitespace-nowrap">
                                        <x-table-actions
                                            :show-route="route('customizacao.show', [
                                                'customizacao' => CryptHelper::encrypt($cust->id_customizacao),
                                                'return_url' => $urlVoltar,
                                            ])"
                                            show-text="Ver"
                                            :edit-route="!$orcamentoBloqueado ? route('customizacao.edit', [
                                                'customizacao' => CryptHelper::encrypt($cust->id_customizacao),
                                                'return_url' => $urlVoltar,
                                            ]) : null"
                                            edit-text="Editar"
                                            :delete-action="!$orcamentoBloqueado ? route('customizacao.destroy', [
                                                'customizacao' => CryptHelper::encrypt($cust->id_customizacao),
                                                'return_url' => $urlVoltarLimpo,
                                            ]) : null"
                                            delete-id="formExcluirCustomizacao{{ $cust->id_customizacao }}"
                                            delete-modal="modalExcluirCustomizacao" />
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @else
                <x-empty-state
                    title="Nenhuma customização cadastrada"
                    message="Ainda não existem customizações cadastradas para este produto."
                    route="customizacao.index"
                    :route-params="[
                        'id' => CryptHelper::encrypt($detalhe->id_det),
                        'return_url' => $urlVoltar,
                    ]"
                    button-text="Voltar" />
                @endif
            </div>

            <div class="mt-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Visualização da Camisa</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Representação visual das posições e customizações cadastradas.</p>
                    </div>
                </div>

                @php
                $ombro_dir = '';
                $ombro_esq = '';
                $frente_pos1 = '';
                $frente_pos2 = '';
                $frente_pos3 = '';
                $frente_pos4 = '';
                $frente_pos5 = '';
                $frente_pos6 = '';
                $frente_pos7 = '';
                $frente_pos8 = '';
                $frente_pos9 = '';
                $costa_pos1 = '';
                $costa_pos2 = '';
                $costa_pos3 = '';
                $costa_pos4 = '';
                $costa_pos5 = '';
                $costa_pos6 = '';
                $costa_pos7 = '';
                $costa_pos8 = '';
                $costa_pos9 = '';

                $positionMap = [
                'Ombro' => [
                'Direito' => 'ombro_esq',
                'Esquerdo' => 'ombro_dir',
                ],
                'Frente' => [
                'Esquerdo' => 'frente_pos3',
                'Posição 2' => 'frente_pos2',
                'Direito' => 'frente_pos1',
                'Posição 4' => 'frente_pos4',
                'Centro' => 'frente_pos5',
                'Posição 6' => 'frente_pos6',
                'Posição 7' => 'frente_pos7',
                'Posição 8' => 'frente_pos8',
                'Posição 9' => 'frente_pos9',
                ],
                'Costa' => [
                'Posição 1' => 'costa_pos1',
                'Topo' => 'costa_pos2',
                'Posição 3' => 'costa_pos3',
                'Posição 4' => 'costa_pos4',
                'Centro' => 'costa_pos5',
                'Posição 6' => 'costa_pos6',
                'Posição 7' => 'costa_pos7',
                'Rodapé' => 'costa_pos8',
                'Posição 9' => 'costa_pos9',
                ],
                ];
                @endphp

                @foreach($allCustomizacoesForDetail as $cust)
                @php
                $content = '';
                $isRodapePosition = $cust->cust_local === 'Costa' && $cust->cust_posicao === 'Rodapé';
                $isFrente = $cust->cust_local === 'Frente' || $cust->cust_local === 'Ombro';
                $isCosta = $cust->cust_local === 'Costa';

                if (!empty($cust->cust_imagem)) {
                $imagePath = asset('images_customizacoes/' . $cust->cust_imagem);

                if ($isFrente) {
                $content = '<img src="' . $imagePath . '" alt="' . $cust->cust_local . ' ' . $cust->cust_posicao . '" class="custom-image" style="width:230px;height:110px;object-fit:contain;">';
                } elseif ($isCosta) {
                $content = '<img src="' . $imagePath . '" alt="' . $cust->cust_local . ' ' . $cust->cust_posicao . '" class="custom-image" style="width:250px;height:100px;object-fit:contain;">';
                }
                } else {
                $content = '<span class="text-xs text-gray-700">' . $cust->id_customizacao . '</span>';

                if (!$isRodapePosition) {
                $content .= '<br><span class="text-xs text-gray-700">Tipo: ' . $cust->cust_tipo . '</span>';

                if (!empty($cust->cust_tamanho)) {
                $content .= '<br><span class="text-xs text-gray-700">Tamanho: ' . $cust->cust_tamanho . '</span>';
                }
                }
                }

                if (isset($positionMap[$cust->cust_local][$cust->cust_posicao])) {
                $variableName = $positionMap[$cust->cust_local][$cust->cust_posicao];
                $$variableName = $content;
                }
                @endphp
                @endforeach

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-8">

                    <div class="flex items-center justify-center gap-3 pb-5 mb-6 border-b border-gray-200">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Frente da Camisa</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Visualização das customizações na parte frontal.</p>
                        </div>
                    </div>

                    <div class="flex justify-center overflow-x-auto py-6">
                        <div class="camisa-container-frente">
                            <div class="div-pescoco"></div>

                            <div class="manga-esquerda">
                                @if($ombro_esq){!! $ombro_esq !!}@endif
                            </div>

                            <div class="manga-direita">
                                @if($ombro_dir){!! $ombro_dir !!}@endif
                            </div>

                            <div class="div-frente" style="grid-template-columns:1fr 1fr;grid-template-rows:1fr 1.3fr;">
                                <div class="frente-area">{!! $frente_pos1 !!}</div>
                                <div class="frente-area">{!! $frente_pos3 !!}</div>
                                <div class="frente-area frente-area-rowspan" style="grid-column:1 / span 2;display:flex;justify-content:center;align-items:center;flex-direction:column;">
                                    @if($frente_pos4){!! $frente_pos4 !!}@endif
                                    @if($frente_pos5){!! $frente_pos5 !!}@endif
                                    @if($frente_pos6){!! $frente_pos6 !!}@endif
                                </div>
                                <div class="frente-area" style="grid-column:1 / span 3;border-top:1px dashed #ccc;">
                                    @if($frente_pos7){!! $frente_pos7 !!}@endif
                                    @if($frente_pos8){!! $frente_pos8 !!}@endif
                                    @if($frente_pos9){!! $frente_pos9 !!}@endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-center gap-3 pb-5 mb-6 mt-6 border-b border-gray-200">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Costa da Camisa</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Visualização das customizações na parte traseira.</p>
                        </div>
                    </div>

                    <div class="flex justify-center overflow-x-auto py-6">
                        <div class="camisa-container-costa">
                            <div class="div-pescoco"></div>
                            <div class="manga-esquerda"></div>
                            <div class="manga-direita"></div>

                            <div class="div-costa">
                                <div class="costa-area costa-area-topo" style="grid-column:1 / span 3;">
                                    @if($costa_pos1){!! $costa_pos1 !!}@endif
                                    @if($costa_pos2){!! $costa_pos2 !!}@endif
                                    @if($costa_pos3){!! $costa_pos3 !!}@endif
                                </div>
                                <div class="costa-area costa-area-centro" style="grid-column:1 / span 3;">
                                    @if($costa_pos4){!! $costa_pos4 !!}@endif
                                    @if($costa_pos5){!! $costa_pos5 !!}@endif
                                    @if($costa_pos6){!! $costa_pos6 !!}@endif
                                </div>
                                <div class="costa-area costa-area-rodape" style="grid-column:1 / span 3;">
                                    @if($costa_pos7){!! $costa_pos7 !!}@endif
                                    @if($costa_pos8){!! $costa_pos8 !!}@endif
                                    @if($costa_pos9){!! $costa_pos9 !!}@endif
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="flex justify-end mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para customizações
                </x-secondary-button>
            </div>

        </div>
    </div>
</div>

<style>
    body {
        font-family: 'Inter', sans-serif
    }

    .camisa-container-frente,
    .camisa-container-costa {
        position: relative;
        width: 300px;
        height: 400px;
        background-color: #fff;
        border: 2px solid #333;
        border-radius: 5px;
        display: flex;
        flex-direction: column;
        margin: 20px auto;
        box-shadow: 0 4px 8px rgba(0, 0, 0, .1);
        overflow: visible;
        z-index: 0
    }

    .manga-esquerda,
    .manga-direita {
        position: absolute;
        width: 150px;
        height: 100px;
        background-color: #fff;
        border: 2px solid #333;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 2em;
        font-weight: bold;
        overflow: hidden
    }

    .manga-esquerda {
        top: -5px;
        left: -130px;
        transform: rotate(-20deg);
        transform-origin: 100% 50%;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0
    }

    .manga-direita {
        top: -5px;
        right: -130px;
        transform: rotate(20deg);
        transform-origin: 0 50%;
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
        border-top-left-radius: 0;
        border-bottom-left-radius: 0
    }

    .div-frente,
    .div-costa {
        background-color: #fff;
        flex-grow: 1;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        grid-template-rows: repeat(3, 1fr);
        gap: 5px;
        border-radius: 5px;
        padding: 10px;
        box-sizing: border-box;
        position: relative;
        z-index: 1
    }

    .frente-area,
    .costa-area {
        border: 1px dashed #ccc;
        background-color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        font-size: 1.2em;
        font-weight: bold;
        border-radius: 4px;
        min-height: 50px;
        overflow: hidden;
        padding: 5px;
        text-align: center
    }

    .frente-pos2-hidden {
        visibility: hidden;
        background-color: transparent;
        border: none;
        padding: 0;
        min-height: 0
    }

    .custom-image {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain
    }

    .div-pescoco {
        position: absolute;
        top: -20px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 40px;
        background-color: #fff;
        border: 2px solid #333;
        border-top-left-radius: 50%;
        border-top-right-radius: 50%;
        border-bottom: none;
        z-index: 1
    }
</style>

<x-modal-confirmacao
    id="modalExcluirCustomizacao"
    titulo="Excluir customização"
    mensagem="Deseja realmente apagar esta customização?"
    textoConfirmar="Excluir" />
@endsection