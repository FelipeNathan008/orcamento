@extends('layouts.app_financeiro')

@section('title', 'Detalhes da Forma de Pagamento')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho da Seção --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Parcelas do Pagamento
        </h1>

        @php
        $idFinanceiro = request()->query('fin');
        @endphp

        <a href="{{ url('/forma_pagamento?' . $idFinanceiro) }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
            style="background-color: #6B7280;">
            &larr; Voltar
        </a>
    </div>

    {{-- Mensagem de Aviso --}}
    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded-md mb-6">
        <strong class="font-bold">Atenção!</strong>
        <span class="block sm:inline">
            Não é possível alterar ou excluir parcelas diretamente por aqui.
            Para corrigir, você deve excluir a forma de pagamento e criar uma nova corretamente.
        </span>
    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if($detalhes->isEmpty())
    <p class="text-gray-600 text-center py-8">Nenhum detalhe cadastrado ainda.</p>

    @else
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">ID Forma Pagamento</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Valor Parcela</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Data Vencimento</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($detalhes as $det)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $det->id_forma_pag }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        R$ {{ number_format($det->det_forma_valor_parcela, 2, ',', '.') }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ date('d/m/Y', strtotime($det->det_forma_data_venc)) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>

@endsection
