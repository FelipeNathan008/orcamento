@extends('layouts.app_financeiro')

@section('title', 'Formas de Pagamento')
@section('content')

<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes da Forma de Pagamento</h1>

        <div class="flex space-x-3">


            <a href="{{ route('forma_pagamento.index', $formaPagamento->financeiro_id_fin) }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-gray-600 text-sm">ID:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $formaPagamento->id_forma_pag }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Tipo de Pagamento:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $formaPagamento->tipoPagamento?->tipo_plano_fin ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Conta Bancária:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $formaPagamento->conta?->conta_nome_banco ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Prazo:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $formaPagamento->forma_prazo }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Data do Pagamento:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $formaPagamento->forma_data 
                        ? \Carbon\Carbon::parse($formaPagamento->forma_data)->format('d/m/Y') 
                        : 'N/D' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Quantidade de Parcelas:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $formaPagamento->forma_qtd_parcela }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Valor:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    R$ {{ number_format($formaPagamento->forma_valor, 2, ',', '.') }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-600 text-sm">Descrição:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $formaPagamento->forma_descricao }}
                </p>
            </div>


        </div>
    </div>

    {{-- PARCELAS --}}
    @if($formaPagamento->forma_prazo === 'Parcelado')
    <div class="bg-white shadow-xl rounded-lg p-8 mt-6">

        <h2 class="text-xl font-bold text-gray-800 mb-4">Parcelas</h2>

        @if($formaPagamento->detalhes->isEmpty())
        <p class="text-gray-600">Nenhuma parcela cadastrada.</p>
        @else
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-xs font-bold uppercase">Valor</th>
                    <th class="px-4 py-2 text-xs font-bold uppercase">Vencimento</th>
                    <th class="px-4 py-2 text-xs font-bold uppercase">Situação</th>
                    <th class="px-4 py-2 text-xs font-bold uppercase">Pagamento</th>
                </tr>
            </thead>
            <tbody>
                @foreach($formaPagamento->detalhes as $parcela)
                <tr>
                    <td class="px-4 py-2 text-sm">
                        R$ {{ number_format($parcela->det_forma_valor_parcela,2,',','.') }}
                    </td>

                    <td class="px-4 py-2 text-sm">
                        {{ \Carbon\Carbon::parse($parcela->det_forma_data_venc)->format('d/m/Y') }}
                    </td>

                    <td class="px-4 py-2 text-sm">
                        {{ $parcela->det_situacao }}
                    </td>
                    
                    <td class="px-4 py-2 text-sm">
                        {{ $parcela->det_forma_data_pagamento ? \Carbon\Carbon::parse($parcela->det_forma_data_pagamento)->format('d/m/Y') 
                            : 'N/D' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

    </div>
    @endif

</div>
@endsection