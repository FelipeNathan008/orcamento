@extends('layouts.app') {{-- Assumindo que você tem um layout principal chamado 'app' --}}

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes da Prospecção</h1>
        <div class="flex space-x-3">
            <a href="{{ route('cliente.edit', $cliente->id_cliente) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Editar Prospecção
            </a>
            <a href="{{ route('cliente.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Nome:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $cliente->clie_nome }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Email:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $cliente->clie_email }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Telefone:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    @php
                    $telefone = preg_replace('/\D/', '', $cliente->clie_telefone ?? '');
                    @endphp

                    @if(strlen($telefone) === 11)
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $telefone) }}
                    @elseif(strlen($telefone) === 10)
                    {{ preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $telefone) }}
                    @else
                    N/A
                    @endif
                </p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Celular:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    @php
                    $celular = preg_replace('/\D/', '', $cliente->clie_celular ?? '');
                    @endphp

                    @if($celular)
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celular) }}
                    @else
                    N/A
                    @endif
                </p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Tipo de Documento:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $cliente->clie_tipo_doc }}</p>
            </div>
            @if ($cliente->clie_cpf)
            <div class="mb-4">
                @if ($cliente->clie_tipo_doc === 'CPF' && $cliente->clie_cpf)
                <div class="mb-4">
                    <p class="text-gray-600 text-sm">CPF:</p>
                    <p class="text-gray-900 text-lg font-semibold">
                        @php
                        $cpf = preg_replace('/\D/', '', $cliente->clie_cpf);
                        @endphp
                        {{ preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf) }}
                    </p>
                </div>
                @endif
            </div>
            @endif
            @if ($cliente->clie_cnpj)
            <div class="mb-4">
                @if ($cliente->clie_tipo_doc === 'CNPJ' && $cliente->clie_cnpj)
                <div class="mb-4">
                    <p class="text-gray-600 text-sm">CNPJ:</p>
                    <p class="text-gray-900 text-lg font-semibold">
                        @php
                        $cnpj = preg_replace('/\D/', '', $cliente->clie_cnpj);
                        @endphp
                        {{ preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj) }}
                    </p>
                </div>
                @endif
            </div>
            @endif
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Logradouro:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $cliente->clie_logradouro }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Bairro:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $cliente->clie_bairro }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">CEP:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    @php
                    $cep = preg_replace('/\D/', '', $cliente->clie_cep ?? '');
                    @endphp
                    {{ preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep) }}
                </p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">Cidade:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $cliente->clie_cidade }}</p>
            </div>
            <div class="mb-4">
                <p class="text-gray-600 text-sm">UF:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $cliente->clie_uf }}</p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Criado em:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $cliente->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Última Atualização:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $cliente->updated_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection