@extends('layouts.app')

@section('title', 'Detalhes do Item do Orçamento')

@php
use App\Helpers\CryptHelper;

$orcamento = $detalheOrcamento->orcamento;
$cliente = $orcamento->clienteOrcamento;

$orcamentoBloqueado = in_array(
strtolower(trim($orcamento->orc_status)),
['aprovado', 'finalizado', 'rejeitado']
);

$quantidade = (float) ($detalheOrcamento->det_quantidade ?? 0);
$valorUnitario = (float) ($detalheOrcamento->det_valor_unit ?? 0);
$total = $quantidade * $valorUnitario;
@endphp

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header
            title="Detalhes do Item do Orçamento"
            :back-url="$urlVoltar">
            @if(!$orcamentoBloqueado)
            <x-header-action href="{{ route('detalhes_orcamento.edit', [
                    'detalhes_orcamento' => CryptHelper::encrypt($detalheOrcamento->id_det),
                    'return_url' => $urlVoltar,
                ]) }}">
                Editar item
            </x-header-action>
            @endif
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-6 pb-8">
            <x-info-card
                title="Informações do Orçamento"
                :name="'Orçamento #' . $orcamento->id_orcamento"
                type="Orçamento"
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
                        'value' => $orcamento->clienteOrcamento->clie_orc_nome ?? 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Data de início',
                        'value' => $orcamento->orc_data_inicio ? $orcamento->orc_data_inicio->format('d/m/Y') : 'Não informado',
                    ],
                    [
                        'label' => 'Status',
                        'value' => ucfirst($orcamento->orc_status),
                    ],
                ]" />

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Informações do item</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Confira os dados cadastrados deste item do orçamento.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="sm:col-span-2 lg:col-span-4 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Produto</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $detalheOrcamento->det_nome ?: 'Não informado' }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Código</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $detalheOrcamento->det_cod ?: 'Não informado' }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Família</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $detalheOrcamento->det_familia ?: 'Não informado' }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Categoria</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $detalheOrcamento->det_categoria ?: 'Não informado' }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Material</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $detalheOrcamento->det_material ?: 'Não informado' }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Modelo</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $detalheOrcamento->det_modelo ?: 'Não informado' }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Cor</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $detalheOrcamento->det_cor ?: 'Não informado' }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Gênero</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $detalheOrcamento->det_genero ?: 'Não informado' }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Tamanho</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $detalheOrcamento->det_tamanho ?: 'Não informado' }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Quantidade</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $detalheOrcamento->det_quantidade }}</p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Valor unitário</p>
                        <p class="text-sm font-semibold text-gray-800">R$ {{ number_format($valorUnitario, 2, ',', '.') }}</p>
                    </div>

                    <div class="bg-white border border-orange-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-[#EA792D] uppercase tracking-wide mb-1">Total</p>
                        <p class="text-base font-bold text-[#EA792D]">R$ {{ number_format($total, 2, ',', '.') }}</p>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-4 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Características</p>
                        <p class="text-sm font-semibold text-gray-800 whitespace-pre-line">{{ $detalheOrcamento->det_caract ?: 'Não informado' }}</p>
                    </div>

                    @if($detalheOrcamento->det_observacao)
                    <div class="sm:col-span-2 lg:col-span-4 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Observação</p>
                        <p class="text-sm font-semibold text-gray-800 whitespace-pre-line">{{ $detalheOrcamento->det_observacao }}</p>
                    </div>
                    @endif

                    @if($detalheOrcamento->det_anotacao)
                    <div class="sm:col-span-2 lg:col-span-4 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Anotação</p>
                        <p class="text-sm font-semibold text-gray-800 whitespace-pre-line">{{ $detalheOrcamento->det_anotacao }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-end mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
            </div>
        </div>
    </div>

</div>
@endsection