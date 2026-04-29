@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes da Conta Bancária</h1>

        <div class="flex space-x-3">
            <a href="{{ route('conta_bancaria.edit', $conta->id_conta ?? $conta->id) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg">
                Editar
            </a>

            <a href="{{ route('conta_bancaria.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg">
                Voltar
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <p class="text-gray-600 text-sm">Banco</p>
                <p class="text-lg font-semibold">
                    {{ $conta->conta_nome_banco }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Código do Banco</p>
                <p class="text-lg font-semibold">
                    {{ $conta->conta_cod_banco }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Agência</p>
                <p class="text-lg font-semibold">
                    {{ $conta->conta_agencia }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Conta</p>
                <p class="text-lg font-semibold">
                    {{ $conta->numero_conta_corrente }}
                </p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Dígito</p>
                <p class="text-lg font-semibold">
                    {{ $conta->numero_digito_corrente }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-600 text-sm">Descrição</p>
                <p class="text-lg font-semibold">
                    {{ $conta->conta_desc ?? 'N/A' }}
                </p>
            </div>

        </div>

    </div>

</div>
@endsection