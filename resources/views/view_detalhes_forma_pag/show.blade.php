@extends('layouts.app') {{-- Usando o mesmo layout do exemplo --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes da Forma de Pagamento</h1>

        <div class="flex space-x-3">
            <a href="{{ route('detalhes_forma_pag.edit', $detalhe->id_det_forma) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Editar Detalhe
            </a>

            <a href="{{ route('detalhes_forma_pag.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $detalhe->id_det_forma }}</p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Forma de Pagamento:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalhe->formaPagamento->forma_nome ?? 'N/A' }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Valor da Parcela:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    R$ {{ number_format($detalhe->det_forma_valor_parcela, 2, ',', '.') }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Data de Vencimento:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ \Carbon\Carbon::parse($detalhe->det_forma_data_venc)->format('d/m/Y') }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Criado em:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalhe->created_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Última Atualização:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $detalhe->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>

        </div>
    </div>
</div>
@endsection
