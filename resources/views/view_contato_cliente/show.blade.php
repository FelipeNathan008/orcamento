@extends('layouts.app') {{-- Assumindo que você tem um layout principal chamado 'app' --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes do Contato do Cliente</h1>
        <div class="flex space-x-3">
            <a href="{{ route('contato_cliente.edit', $contatoCliente->id_contato) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Editar Contato
            </a>
            <a href="{{ route('contato_cliente.index', ['cliente_orcamento' => $contatoCliente->cliente_orcamento_id_co]) }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>
            <div class="flex justify-center mb-8">

            </div>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Cliente de Orçamento:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $contatoCliente->clienteOrcamento->clie_orc_nome ?? 'Cliente Não Encontrado' }}
                </p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Nome do Contato:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $contatoCliente->cont_nome }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Telefone:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    @php
                    $telefone = preg_replace('/\D/', '', $contatoCliente->cont_telefone);
                    @endphp

                    @if($telefone && strlen($telefone) === 10)
                    {{ preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone) }}
                    @elseif($telefone && strlen($telefone) === 11)
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone) }}
                    @else
                    {{ $contatoCliente->cont_telefone ?? 'N/A' }}
                    @endif
                </p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Celular:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    @php
                    $celular = preg_replace('/\D/', '', $contatoCliente->cont_celular);
                    @endphp

                    @if(strlen($celular) === 11)
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celular) }}
                    @else
                    {{ $contatoCliente->cont_celular }}
                    @endif
                </p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Email:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $contatoCliente->cont_email }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Tipo de Contato:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ ucfirst($contatoCliente->cont_tipo) }}</p>
            </div>
            @if ($contatoCliente->cont_descricao)
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Descrição:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $contatoCliente->cont_descricao }}</p>
            </div>
            @endif
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Criado em:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $contatoCliente->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Última Atualização:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $contatoCliente->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection