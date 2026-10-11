@extends('layouts.app_financeiro')

@section('title', 'Notificações da Parcela')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header
            title="Notificações da Parcela"
            :back-url="route('notificacao.index', $filtros)">

            <x-header-action
                :href="route('notificacao.create', array_merge(['id_det_forma' => CryptHelper::encrypt($detalheForma->id_det_forma)],$filtros))">
                Cadastrar Notificação
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <x-info-card
                title="Informações da Parcela"
                :name="$detalheForma->formaPagamento?->financeiro?->fin_nome_cliente ?? 'Cliente não informado'"
                type="Parcela"
                :fields="[
                    [
                        'label' => 'ID Parcela',
                        'value' => '#' . $detalheForma->id_det_forma,
                        'bold' => true,
                    ],
                    [
                        'label' => 'ID Financeiro',
                        'value' => $detalheForma->formaPagamento?->financeiro?->id_fin ?? 'N/D',
                        'bold' => true,
                    ],
                    [
                        'label' => 'ID Orçamento',
                        'value' => $detalheForma->formaPagamento?->financeiro?->orcamento?->id_orcamento ?? 'N/D',
                    ],
                    [
                        'label' => 'Cód. Interno',
                        'value' => $detalheForma->formaPagamento?->financeiro?->orcamento?->orc_cod_interno ?: 'N/D',
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $detalheForma->formaPagamento?->financeiro?->orcamento?->orc_cod_fabrica ?: 'N/D',
                    ],
                    [
                        'label' => 'Forma de Pagamento',
                        'value' => $detalheForma->formaPagamento?->tipoPagamento?->tipo_plano_fin ?? 'N/D',
                        'break' => true,
                    ],
                    [
                        'label' => 'Valor',
                        'value' => 'R$ ' . number_format($detalheForma->det_forma_valor_parcela ?? 0, 2, ',', '.'),
                    ],
                    [
                        'label' => 'Vencimento',
                        'value' => $detalheForma->det_forma_data_venc
                            ? \Carbon\Carbon::parse($detalheForma->det_forma_data_venc)->format('d/m/Y')
                            : 'N/D',
                    ],
                    [
                        'label' => 'Status',
                        'value' => $detalheForma->det_situacao ?? 'N/D',
                    ],
                ]" />

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-6 mb-4">
                <h2 class="text-lg font-bold text-gray-800">
                    Notificações
                </h2>

                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $detalheForma->notificacoes->count() }}
                        {{ $detalheForma->notificacoes->count() == 1 ? 'notificação' : 'notificações' }}
                    </span>
                </div>
            </div>

            @if($detalheForma->notificacoes->isEmpty())
            <x-empty-state
                title="Nenhuma notificação cadastrada"
                message="Ainda não existem notificações cadastradas para esta parcela."
                route="notificacao.create"
                :route-params="array_merge(['id_det_forma' => CryptHelper::encrypt($detalheForma->id_det_forma)],$filtros)"
                button-text="Cadastrar notificação" />
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Tipo
                                </th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Descrição
                                </th>
                                <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($detalheForma->notificacoes as $notificacao)
                            @php
                            $tipoNome = match((string) $notificacao->not_tipo) {
                            '1' => 'Aviso Bancário',
                            '2' => 'E-mail / Telefone',
                            '3' => 'Carta Registrada',
                            '4' => 'Protesto',
                            default => $notificacao->tipo_nome ?? 'N/D',
                            };
                            @endphp

                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <x-badge
                                        type="orange"
                                        :text="$tipoNome"
                                        :dot="false" />
                                </td>

                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">
                                        {{ $notificacao->not_descricao }}
                                    </span>
                                </td>

                                <td class="px-2 py-5 text-center whitespace-nowrap">
                                    <div class="flex justify-center items-center gap-2 flex-wrap">
                                        <x-table-actions
                                            :edit-route="route('notificacao.edit', array_merge(
                                                        ['id' => CryptHelper::encrypt($notificacao->id_notificacao)],
                                                        $filtros
                                                    ))"
                                            edit-text="Editar"
                                            :delete-action="route('notificacao.destroy', CryptHelper::encrypt($notificacao->id_notificacao))"
                                            :delete-id="'formExcluirNotificacao' . $notificacao->id_notificacao"
                                            delete-modal="modalExcluirNotificacao" />

                                        <form
                                            id="formExcluirNotificacao{{ $notificacao->id_notificacao }}"
                                            action="{{ route('notificacao.destroy', CryptHelper::encrypt($notificacao->id_notificacao)) }}"
                                            method="POST"
                                            class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirNotificacao"
    titulo="Excluir notificação"
    mensagem="Deseja realmente apagar esta notificação?"
    textoConfirmar="Excluir" />
@endsection