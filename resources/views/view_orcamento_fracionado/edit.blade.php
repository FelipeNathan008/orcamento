@extends('layouts.app_financeiro')

@section('title', 'Editar Orçamento Fracionado')

@php
use App\Helpers\CryptHelper;

$statusAtual = old('orc_status', $orcamentoFracionado->orc_status);
$urlVoltar = route('orcamento.fracionado.index', CryptHelper::encrypt($orcamentoFracionado->orcamento_id_orcamento));
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Editar Orçamento Fracionado" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="orcamentoFracionadoForm"
            action="{{ route('orcamento.fracionado.update', CryptHelper::encrypt($orcamentoFracionado->id_orcamento_fracionado)) }}"
            method="POST"
            data-status-anterior="{{ $orcamentoFracionado->orc_status }}"
            class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            @method('PUT')

            <x-info-card
                title="Informações do orçamento fracionado"
                :name="'Fração #' . $orcamentoFracionado->orc_fracao"
                type="Orçamento Fracionado"
                :fields="[
                    [
                        'label' => 'ID',
                        'value' => $orcamentoFracionado->id_orcamento_fracionado,
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cliente',
                        'value' => $orcamentoFracionado->clienteOrcamento->clie_orc_nome ?? 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Código interno',
                        'value' => $orcamentoFracionado->orc_cod_interno ?: 'Não informado',
                    ],
                    [
                        'label' => 'Código da fábrica',
                        'value' => $orcamentoFracionado->orc_cod_fabrica ?: 'Não informado',
                    ],
                ]" />

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 mt-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do orçamento fracionado</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Atualize as informações do orçamento fracionado.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="orc_cod_interno" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Código Interno
                        </label>
                        <input type="text" name="orc_cod_interno" id="orc_cod_interno" maxlength="60"
                            placeholder="Código interno"
                            value="{{ old('orc_cod_interno', $orcamentoFracionado->orc_cod_interno) }}"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                          </div>

                    <div>
                        <label for="orc_cod_fabrica" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Código da Fábrica
                            @if($orcamentoFracionado->orc_status !== 'pendente')
                            <span class="text-red-600">*</span>
                            @endif
                        </label>
                        <input type="text" name="orc_cod_fabrica" id="orc_cod_fabrica" maxlength="60"
                            placeholder="Código da fábrica"
                            value="{{ old('orc_cod_fabrica', $orcamentoFracionado->orc_cod_fabrica) }}"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            @if($orcamentoFracionado->orc_status !== 'pendente') required @endif>
   
                    </div>

                    <div class="md:col-span-2">
                        <label for="orc_anotacao_geral" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Anotação Geral
                        </label>
                        <textarea name="orc_anotacao_geral" id="orc_anotacao_geral" rows="4" maxlength="1000"
                            class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none"
                            placeholder="Digite uma anotação geral">{{ old('orc_anotacao_geral', $orcamentoFracionado->orc_anotacao_geral) }}</textarea>

                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="btnAtualizarFracionado" class="px-6">
                    <span id="textoAtualizarFracionado">Atualizar orçamento</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('orcamentoFracionadoForm');
        const btn = document.getElementById('btnAtualizarFracionado');
        const texto = document.getElementById('textoAtualizarFracionado');

        if (!form || !btn) return;

        form.addEventListener('submit', function() {
            if (btn.disabled) return;
            btn.disabled = true;
            texto.textContent = 'Atualizando...';
            btn.classList.add('opacity-70', 'cursor-not-allowed');
        });
    });
</script>
@endpush
@endsection