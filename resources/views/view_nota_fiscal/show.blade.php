@extends('layouts.app')

@section('title', 'Detalhes da Nota Fiscal')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Detalhes da Nota Fiscal
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('nota_fiscal.edit', $nota->id_nota_fiscal) }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover">
                Editar
            </a>

            <a href="{{ route('nota_fiscal.index') }}"
                class="px-4 py-2 text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400">
                Voltar
            </a>

        </div>

    </div>

    {{-- CONTEÚDO --}}
    <div class="bg-white">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-sm text-gray-600">Data</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ \Carbon\Carbon::parse($nota->nota_data)->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-600">Número da Nota</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $nota->nota_numero }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-600">Orçamento</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $nota->orcamento_id_orcamento }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-600">Tipo</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $nota->tipo->tipo_flu_nome ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-600">Movimentação</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $nota->movimentacao->mov_nome ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-600">Valor</p>
                <p class="text-lg font-semibold text-gray-900">
                    R$ {{ number_format($nota->nota_valor, 2, ',', '.') }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-gray-600">Descrição</p>
                <p class="text-lg font-semibold text-gray-900">
                    {{ $nota->nota_desc ?? 'N/A' }}
                </p>
            </div>

        </div>

    </div>

</div>

@endsection