@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes do Log de Status</h1>

        <div class="flex space-x-3">
            <a href="{{ route('log_status.edit', $logStatus->id_log_status) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Editar Log
            </a>

            <a href="{{ route('log_status.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $logStatus->id_log_status }}</p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID do Cliente:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $logStatus->log_id_cliente }}</p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID do Orçamento:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $logStatus->log_id_orcamento }}</p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Status da Mercadoria (ID):</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $logStatus->status_mercadoria_id_status }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Nome do Status:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $logStatus->log_nome_status }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Situação:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $logStatus->log_situacao == 1 ? 'Ativo' : 'Inativo' }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Criado em:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $logStatus->created_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Última Atualização:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $logStatus->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>

        </div>
    </div>
</div>
@endsection
