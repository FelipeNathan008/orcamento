@extends('layouts.app')

@section('title', 'Detalhes da Customização')

@php
use App\Helpers\CryptHelper;

$detalhe = $customizacao->detalhesOrcamento;
$orcamento = $detalhe->orcamento;
$cliente = $orcamento->clienteOrcamento;

$orcamentoBloqueado = in_array(
strtolower(trim($orcamento->orc_status)),
['aprovado', 'finalizado', 'rejeitado']
);

$urlVoltar = $urlVoltar ?? route('customizacao.index', [
'id' => CryptHelper::encrypt($detalhe->id_det),
]);
@endphp

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header
            title="Detalhes da Customização"
            :back-url="$urlVoltar">
            @if(!$orcamentoBloqueado)
            <x-header-action href="{{ route('customizacao.edit', [
                'customizacao' => CryptHelper::encrypt($customizacao->id_customizacao),
                'return_url' => $urlVoltar,
            ]) }}">
                Editar Customização
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
                        'value' => $cliente->clie_orc_nome ?? 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Status',
                        'value' => ucfirst($orcamento->orc_status),
                    ],
                ]" />

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 mt-6">

                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Informações da Customização</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Confira os dados cadastrados desta customização.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <div class="sm:col-span-2 lg:col-span-4 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Produto</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $detalhe->det_nome ?: 'Não informado' }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Código</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $detalhe->det_cod ?: 'Não informado' }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Tipo</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $customizacao->cust_tipo ?: 'Não informado' }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Local</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $customizacao->cust_local ?: 'Não informado' }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Posição</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $customizacao->cust_posicao ?: 'Não informado' }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Tamanho</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $customizacao->cust_tamanho ?: 'Não informado' }}
                        </p>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Formatação</p>
                        <p class="text-sm font-semibold text-gray-800">
                            {{ $customizacao->cust_formatacao ?: 'Não informado' }}
                        </p>
                    </div>

                    <div class="bg-white border border-orange-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-[#EA792D] uppercase tracking-wide mb-1">Valor</p>
                        <p class="text-base font-bold text-[#EA792D]">
                            R$ {{ number_format((float) $customizacao->cust_valor, 2, ',', '.') }}
                        </p>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-4 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-1">Descrição</p>
                        <p class="text-sm font-semibold text-gray-800 whitespace-pre-line">
                            {{ $customizacao->cust_descricao ?: 'Sem descrição' }}
                        </p>
                    </div>

                    <div class="sm:col-span-2 lg:col-span-4 bg-white border border-gray-200 rounded-lg p-4">
                        <p class="text-[11px] font-bold text-gray-500 uppercase tracking-wide mb-3">Imagem da Customização</p>

                        @if(!empty($customizacao->cust_imagem))
                        <div class="flex justify-center sm:justify-start">
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-3">
                                <img
                                    src="{{ asset('images_customizacoes/' . $customizacao->cust_imagem) }}"
                                    alt="Imagem da Customização"
                                    class="max-w-full w-auto max-h-96 object-contain rounded-lg"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">

                                <p class="text-red-500 text-sm hidden">
                                    Não foi possível carregar a imagem.
                                </p>
                            </div>
                        </div>
                        @else
                        <p class="text-sm font-semibold text-gray-500">
                            Sem imagem cadastrada.
                        </p>
                        @endif
                    </div>

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