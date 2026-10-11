@extends('layouts.app_financeiro')

@section('title', 'Detalhes da Forma de Pagamento')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header
            title="Detalhes da Forma de Pagamento"
            :back-url="route('forma_pagamento.index', CryptHelper::encrypt($formaPagamento->financeiro_id_fin))" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-6 pb-8">
            <x-info-card
                title="Forma de pagamento"
                :name="'#'.$formaPagamento->id_forma_pag"
                type="Pagamento"
                :fields="[
                [
                    'label' => 'Tipo de pagamento',
                    'value' => $formaPagamento->tipoPagamento?->tipo_plano_fin ?? 'N/D',
                    'bold' => true,
                ],
                [
                    'label' => 'Conta bancária',
                    'value' => $formaPagamento->conta?->conta_nome_banco ?? 'Não informado',
                ],
                [
                    'label' => 'Prazo',
                    'value' => $formaPagamento->forma_prazo,
                ],
                [
                    'label' => 'Data do pagamento',
                    'value' => $formaPagamento->forma_data
                        ? \Carbon\Carbon::parse($formaPagamento->forma_data)->format('d/m/Y')
                        : 'N/D',
                ],
                [
                    'label' => 'Quantidade de parcelas',
                    'value' => $formaPagamento->forma_qtd_parcela,
                ],
                [
                    'label' => 'Valor',
                    'value' => 'R$ '.number_format($formaPagamento->forma_valor, 2, ',', '.'),
                    'bold' => true,
                ],
                [
                    'label' => 'Descrição',
                    'value' => $formaPagamento->forma_descricao ?: 'Não informado',
                ],
            ]" />

            @if($formaPagamento->forma_prazo === 'Parcelado')
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 mt-6">
                <div class="flex items-center justify-between gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                            <x-icons.document class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-800">Parcelas</h2>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Parcelas vinculadas a esta forma de pagamento.
                            </p>
                        </div>
                    </div>

                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-orange-50 border border-orange-100 text-xs font-semibold text-orange-700">
                        {{ $formaPagamento->detalhes->count() }} parcela(s)
                    </span>
                </div>

                @if($formaPagamento->detalhes->isEmpty())
                <x-empty-state
                    title="Nenhuma parcela cadastrada"
                    message="Ainda não existem parcelas vinculadas a esta forma de pagamento." />
                @else
                <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm bg-white">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead style="background-color:#343A40;">
                                <tr>
                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Parcela</th>
                                    <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Valor</th>
                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Vencimento</th>
                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Situação</th>
                                    <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Pagamento</th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-100">
                                @foreach($formaPagamento->detalhes as $parcela)
                                @php
                                $tipoStatus = match ($parcela->det_situacao) {
                                'Não Pago' => 'yellow',
                                'Pago' => 'green',
                                'Quitado' => 'emerald',
                                'Acordo' => 'blue',
                                'Inadimplencia' => 'red',
                                'Atrasado' => 'red',
                                default => 'gray',
                                };
                                @endphp

                                <tr class="group hover:bg-orange-50/40 transition">
                                    <td class="px-5 py-5 whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-800">
                                            #{{ $parcela->id_det_forma }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-5 text-right whitespace-nowrap">
                                        <span class="text-sm font-bold text-gray-800">
                                            R$ {{ number_format($parcela->det_forma_valor_parcela, 2, ',', '.') }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-5 whitespace-nowrap">
                                        <span class="text-sm {{ $parcela->classe_vencimento }}">
                                            {{ \Carbon\Carbon::parse($parcela->det_forma_data_venc)->format('d/m/Y') }}
                                        </span>
                                    </td>

                                    <td class="px-5 py-5 whitespace-nowrap">
                                        <x-badge
                                            :type="$tipoStatus"
                                            :text="$parcela->det_situacao" />
                                    </td>

                                    <td class="px-5 py-5 whitespace-nowrap">
                                        <span class="text-sm text-gray-700">
                                            {{ $parcela->det_forma_data_pagamento
                                            ? \Carbon\Carbon::parse($parcela->det_forma_data_pagamento)->format('d/m/Y')
                                            : 'N/D' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
            @endif

            <div class="flex justify-end mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="route('forma_pagamento.index', CryptHelper::encrypt($formaPagamento->financeiro_id_fin))">
                    Voltar para a lista
                </x-secondary-button>
            </div>
        </div>
    </div>
</div>
@endsection