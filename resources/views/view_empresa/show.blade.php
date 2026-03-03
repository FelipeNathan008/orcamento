@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">
            Detalhes da Empresa
        </h1>

        <div class="flex space-x-3">
            <a href="{{ route('empresa.edit', $empresa->id_emp) }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Editar Empresa
            </a>

            <a href="{{ route('empresa.index') }}"
               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>
        </div>
    </div>

    {{-- Card --}}
    <div class="bg-white shadow-xl rounded-lg p-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nome ocupa linha inteira --}}
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Nome:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $empresa->emp_nome }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">CNPJ:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    @php
                        $cnpj = preg_replace('/\D/', '', $empresa->emp_cnpj ?? '');
                    @endphp
                    {{ preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj) }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">CEP:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    @php
                        $cep = preg_replace('/\D/', '', $empresa->emp_cep ?? '');
                    @endphp
                    {{ preg_replace('/(\d{5})(\d{3})/', '$1-$2', $cep) }}
                </p>
            </div>

            {{-- Logradouro ocupa linha inteira --}}
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Logradouro:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $empresa->emp_logradouro }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Bairro:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $empresa->emp_bairro }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">Cidade:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $empresa->emp_cidade }}
                </p>
            </div>

            <div class="mb-4">
                <p class="text-gray-600 text-sm">UF:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $empresa->emp_uf }}
                </p>
            </div>

            {{-- Datas ocupam linha inteira --}}
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Criado em:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $empresa->created_at->format('d/m/Y H:i') }}
                </p>
            </div>

            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Última Atualização:</p>
                <p class="text-gray-900 text-lg font-semibold">
                    {{ $empresa->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>

        </div>

    </div>
</div>
@endsection