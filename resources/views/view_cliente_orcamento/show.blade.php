@extends('layouts.app') {{-- Assumindo que você tem um layout principal chamado 'app' --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes do Cliente</h1>
        <div class="flex space-x-3">
            <a href="{{ route('cliente_orcamento.edit', $clienteOrcamento->id_co) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Editar Cliente
            </a>
            <a href="{{ route('cliente_orcamento.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->id_co }}</p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Nome:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_nome }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Email:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_email }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Telefone:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_telefone ?? 'N/A' }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Celular:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_celular }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Tipo de Documento:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_tipo_doc }}</p>
            </div>
            @if ($clienteOrcamento->clie_orc_cpf)
            <div class="mb-4">
                <p class="text-gray-600 text-sm">CPF:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_cpf }}</p>
            </div>
            @endif
            @if ($clienteOrcamento->clie_orc_cnpj)
            <div class="mb-4">
                <p class="text-gray-600 text-sm">CNPJ:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_cnpj }}</p>
            </div>
            @endif
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Logradouro:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_logradouro }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Bairro:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_bairro }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">CEP:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_cep }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Cidade:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_cidade }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">UF:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->clie_orc_uf }}</p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Criado em:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Última Atualização:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $clienteOrcamento->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
