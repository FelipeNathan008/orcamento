@extends('layouts.app') {{-- Assumindo que você tem um layout principal chamado 'app' --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes do Orçamento</h1>
        <div class="flex space-x-3">
            <a href="{{ route('orcamento.edit', $orcamento->id_orcamento) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Editar Orçamento
            </a>
            <a href="{{ route('orcamento.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID do Orçamento:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $orcamento->id_orcamento }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Cliente:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'Cliente Não Encontrado' }}
                </p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Data Início:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $orcamento->orc_data_inicio->format('d/m/Y') }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Data Fim:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $orcamento->orc_data_fim->format('d/m/Y') }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Status:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    <span class="relative inline-block px-3 py-1 font-semibold leading-tight text-gray-900">
                        <span aria-hidden="true" class="absolute inset-0 opacity-50 rounded-full bg-gray-200"></span>
                        <span class="relative">{{ ucfirst($orcamento->orc_status) }}</span>
                    </span>
                </p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Criado em:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $orcamento->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Última Atualização:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $orcamento->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
