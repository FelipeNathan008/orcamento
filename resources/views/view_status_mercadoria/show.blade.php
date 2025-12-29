@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Detalhes do Status de Mercadoria</h1>

        <div class="flex space-x-3">
            <a href="{{ route('status_mercadoria.edit', $status->id_status_merc) }}"
                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Editar Status
            </a>

            <a href="{{ route('status_mercadoria.index') }}"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Voltar para a Lista
            </a>
        </div>
    </div>

    <div class="bg-white shadow-xl rounded-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- ID --}}
            <div class="mb-4">
                <p class="text-gray-600 text-sm">ID:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $status->id_status_merc }}</p>
            </div>

            {{-- Nome do Status --}}
            <div class="md:col-span-2 mb-4">
                <p class="text-gray-600 text-sm">Nome do Status:</p>
                <p class="text-gray-900 text-lg font-semibold">{{ $status->status_merc_nome }}</p>
            </div>

        </div>
    </div>
</div>
@endsection
