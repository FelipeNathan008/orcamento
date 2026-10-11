@extends('layouts.app_financeiro')

@section('title', 'Editar Notificação')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header
            title="Editar Notificação"
            :back-url="route('notificacao.show', array_merge(
                ['id' => CryptHelper::encrypt($notificacao->detalheFormaPag?->id_det_forma)],
                $filtros
            ))" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form
            id="notificacaoForm"
            action="{{ route('notificacao.update', CryptHelper::encrypt($notificacao->id_notificacao)) }}"
            method="POST"
            class="px-6 sm:px-8 pt-6 pb-8">

            @csrf
            @method('PUT')

            @foreach($filtros as $nome => $valor)
            @if(filled($valor))
            <input
                type="hidden"
                name="{{ $nome }}"
                value="{{ $valor }}">
            @endif
            @endforeach

            <input
                type="hidden"
                name="id_det_forma"
                value="{{ $notificacao->detalheFormaPag?->id_det_forma }}">

            <x-info-card
                title="Parcela selecionada"
                :name="$notificacao->detalheFormaPag?->formaPagamento?->financeiro?->fin_nome_cliente ?? 'Não informado'"
                type="Cliente"
                :fields="[
                    [
                        'label' => 'ID Orçamento',
                        'value' => $notificacao->detalheFormaPag?->formaPagamento?->financeiro?->orcamento?->id_orcamento ?? 'N/D',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cód. Interno',
                        'value' => $notificacao->detalheFormaPag?->formaPagamento?->financeiro?->orcamento?->orc_cod_interno ?: 'N/D',
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $notificacao->detalheFormaPag?->formaPagamento?->financeiro?->orcamento?->orc_cod_fabrica ?: 'N/D',
                    ],
                    [
                        'label' => 'Valor',
                        'value' => 'R$ ' . number_format($notificacao->detalheFormaPag?->det_forma_valor_parcela ?? 0, 2, ',', '.'),
                    ],
                    [
                        'label' => 'Vencimento',
                        'value' => $notificacao->detalheFormaPag?->det_forma_data_venc
                            ? \Carbon\Carbon::parse($notificacao->detalheFormaPag->det_forma_data_venc)->format('d/m/Y')
                            : 'Não informado',
                    ],
                    [
                        'label' => 'Status',
                        'value' => $notificacao->detalheFormaPag?->det_situacao ?? 'Não informado',
                    ],
                    [
                        'label' => 'Tipo de Pagamento',
                        'value' => $notificacao->detalheFormaPag?->formaPagamento?->tipoPagamento?->tipo_plano_fin ?? 'Não informado',
                        'break' => true,
                    ],
                ]" />

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 mt-6">

                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">
                            Dados da notificação
                        </h2>

                        <p class="text-xs text-gray-500 mt-0.5">
                            Altere o tipo e a descrição da notificação.
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2">
                        <label
                            for="not_tipo"
                            class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Tipo da Notificação
                        </label>

                        <select
                            name="not_tipo"
                            id="not_tipo"
                            required
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">

                            <option
                                value="1"
                                {{ old('not_tipo', $notificacao->not_tipo) == 1 ? 'selected' : '' }}>
                                Aviso Bancário
                            </option>

                            <option
                                value="2"
                                {{ old('not_tipo', $notificacao->not_tipo) == 2 ? 'selected' : '' }}>
                                E-mail / Telefone
                            </option>

                            <option
                                value="3"
                                {{ old('not_tipo', $notificacao->not_tipo) == 3 ? 'selected' : '' }}>
                                Carta Registrada
                            </option>

                            <option
                                value="4"
                                {{ old('not_tipo', $notificacao->not_tipo) == 4 ? 'selected' : '' }}>
                                Protesto
                            </option>

                        </select>

                    </div>

                    <div class="md:col-span-2">
                        <label
                            for="not_descricao"
                            class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Descrição da Notificação
                        </label>

                        <textarea
                            name="not_descricao"
                            id="not_descricao"
                            rows="5"
                            required
                            placeholder="Descreva a notificação..."
                            class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none">{{ old('not_descricao', $notificacao->not_descricao) }}</textarea>

                    </div>

                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mt-6">
                <div class="flex items-start gap-3">

                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-yellow-100 text-yellow-600 flex-shrink-0">
                        <svg
                            class="w-5 h-5"
                            fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M8.257 3.099c.763-1.36 2.723-1.36 3.486 0l5.58 9.968c.75 1.34-.213 2.983-1.743 2.983H4.42c-1.53 0-2.493-1.643-1.743-2.983l5.58-9.968zM10 7a1 1 0 01.993.883L11 8v2a1 1 0 11-2 0V8a1 1 0 011-1zm0 6a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-sm font-bold text-yellow-800">
                            Atenção antes de atualizar
                        </h3>

                        <p class="mt-1 text-sm text-yellow-700">
                            Confira cuidadosamente o tipo e a descrição antes de confirmar a atualização desta notificação.
                        </p>
                    </div>

                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">

                <x-secondary-button
                    :href="route('notificacao.show', array_merge(
                        ['id' => CryptHelper::encrypt($notificacao->detalheFormaPag?->id_det_forma)],
                        $filtros
                    ))">
                    Voltar para a lista
                </x-secondary-button>

                <x-primary-button
                    type="submit"
                    id="btnAtualizarNotificacao"
                    class="px-6">
                    <span id="textoAtualizar">
                        Atualizar notificação
                    </span>
                </x-primary-button>

            </div>

        </form>
    </div>
</div>

<x-modal-confirmacao
    id="modalAtualizarNotificacao"
    titulo="Confirmar atualização"
    mensagem="A notificação será atualizada. Confira o tipo e a descrição antes de continuar. Deseja realmente atualizar esta notificação?"
    textoConfirmar="Atualizar" />

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('notificacaoForm');
        const btnAtualizar = document.getElementById('btnAtualizarNotificacao');
        const textoAtualizar = document.getElementById('textoAtualizar');

        if (!form || !btnAtualizar) {
            return;
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (btnAtualizar.disabled) {
                return;
            }

            abrirModal('modalAtualizarNotificacao', function() {
                btnAtualizar.disabled = true;
                textoAtualizar.innerText = 'Atualizando...';
                btnAtualizar.classList.add('opacity-70', 'cursor-not-allowed');

                form.submit();
            });
        });

    });
</script>
@endpush

@endsection