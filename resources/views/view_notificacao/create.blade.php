{{-- resources/views/view_notificacao/create.blade.php --}}
@extends('layouts.app_financeiro')

@section('title', 'Cadastrar Nova Notificação')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header
            title="Cadastrar Nova Notificação"
            :back-url="route('notificacao.index', $filtros)" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="notificacaoForm"
            action="{{ route('notificacao.store') }}"
            method="POST"
            class="px-6 sm:px-8 pt-6 pb-8">
            @csrf

            <input type="hidden"
                name="id_det_forma"
                value="{{ $detalheForma?->id_det_forma }}">

            <x-info-card
                title="Parcela selecionada"
                :name="$detalheForma?->formaPagamento?->financeiro?->fin_nome_cliente ?? 'Não informado'"
                type="Cliente"
                :fields="[
                    [
                        'label' => 'ID Orçamento',
                        'value' => $detalheForma?->formaPagamento?->financeiro?->orcamento?->id_orcamento ?? 'N/D',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cód. Interno',
                        'value' => $detalheForma?->formaPagamento?->financeiro?->orcamento?->orc_cod_interno ?: 'N/D',
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $detalheForma?->formaPagamento?->financeiro?->orcamento?->orc_cod_fabrica ?: 'N/D',
                    ],
                    [
                        'label' => 'Valor',
                        'value' => 'R$ ' . number_format($detalheForma?->det_forma_valor_parcela ?? 0, 2, ',', '.'),
                    ],
                    [
                        'label' => 'Vencimento',
                        'value' => $detalheForma?->det_forma_data_venc
                            ? \Carbon\Carbon::parse($detalheForma->det_forma_data_venc)->format('d/m/Y')
                            : 'Não informado',
                    ],
                    [
                        'label' => 'Status',
                        'value' => $detalheForma?->det_situacao ?? 'Não informado',
                    ],
                    [
                        'label' => 'Tipo de Pagamento',
                        'value' => $detalheForma?->formaPagamento?->tipoPagamento?->tipo_plano_fin ?? 'Não informado',
                        'break' => true,
                    ],
                ]" />

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 mt-6">

                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados da notificação</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Informe o tipo e a descrição da notificação.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2">
                        <label for="not_tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Tipo da Notificação
                        </label>

                        <select name="not_tipo"
                            id="not_tipo"
                            required
                            {{ $proximoTipo > 4 ? 'disabled' : '' }}
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            @if($proximoTipo == 1)
                            <option value="1" selected>Aviso Bancário</option>
                            @elseif($proximoTipo == 2)
                            <option value="2" selected>E-mail / Telefone</option>
                            @elseif($proximoTipo == 3)
                            <option value="3" selected>Carta Registrada</option>
                            @elseif($proximoTipo == 4)
                            <option value="4" selected>Protesto</option>
                            @else
                            <option disabled selected>Todas as notificações já foram enviadas</option>
                            @endif
                        </select>

                                           </div>

                    <div class="md:col-span-2">
                        <label for="not_descricao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Descrição da Notificação
                        </label>

                        <textarea name="not_descricao"
                            id="not_descricao"
                            rows="5"
                            required
                            placeholder="Descreva a notificação..."
                            class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none">{{ old('not_descricao') }}</textarea>

                                           </div>

                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mt-6">
                <div class="flex items-start gap-3">
                    <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-yellow-100 text-yellow-600 flex-shrink-0">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.763-1.36 2.723-1.36 3.486 0l5.58 9.968c.75 1.34-.213 2.983-1.743 2.983H4.42c-1.53 0-2.493-1.643-1.743-2.983l5.58-9.968zM10 7a1 1 0 01.993.883L11 8v2a1 1 0 11-2 0V8a1 1 0 011-1zm0 6a1 1 0 100-2 1 1 0 000 2z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-yellow-800">Atenção antes de salvar</h3>
                        <p class="mt-1 text-sm text-yellow-700">
                            Após o cadastro, esta notificação será registrada para a parcela selecionada.
                            Confira cuidadosamente o tipo e a descrição antes de salvar.
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="route('notificacao.index', $filtros)">
                    Voltar para a lista
                </x-secondary-button>

                <x-primary-button
                    type="submit"
                    id="btnSalvarNotificacao"
                    class="px-6"
                    :disabled="$proximoTipo > 4">
                    <span id="textoSalvar">Salvar notificação</span>
                </x-primary-button>
            </div>

        </form>
    </div>
</div>

<x-modal-confirmacao
    id="modalSalvarNotificacao"
    titulo="Confirmar cadastro"
    mensagem="A notificação será registrada para a parcela selecionada. Confira o tipo e a descrição antes de continuar. Deseja realmente salvar esta notificação?"
    textoConfirmar="Salvar" />

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('notificacaoForm');
        const btnSalvar = document.getElementById('btnSalvarNotificacao');
        const textoSalvar = document.getElementById('textoSalvar');

        if (!form || !btnSalvar) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            if (btnSalvar.disabled) return;

            abrirModal('modalSalvarNotificacao', function() {
                btnSalvar.disabled = true;
                textoSalvar.innerText = 'Salvando...';
                btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
                form.submit();
            });
        });
    });
</script>
@endpush

@endsection