@extends('layouts.app')

@section('title', 'Editar Log de Status')

@section('content')
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

        {{-- Título --}}
        <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
            Editar Log de Status
        </h1>

        {{-- Mensagens de erro --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('log_status.update', $logStatus->id_log_status) }}" method="POST" id="logStatusForm" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- GRID PRINCIPAL --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- COLUNA ESQUERDA --}}
                <div>

                    {{-- Status Mercadoria --}}
                    <div>
                        <label for="status_mercadoria_id_status"
                            class="block text-sm font-medium text-custom-dark-text mb-1">
                            Status da Mercadoria
                        </label>

                        <select name="status_mercadoria_id_status" id="status_mercadoria_id_status"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none
                                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border border-gray-300"
                            required>

                            <option value="">Selecione</option>

                            @foreach ($status as $item)
                                <option value="{{ $item->id_status_merc }}"
                                    {{ old('status_mercadoria_id_status', $logStatus->status_mercadoria_id_status) == $item->id_status_merc ? 'selected' : '' }}>
                                    {{ $item->nome_status }}
                                </option>
                            @endforeach
                        </select>

                        @error('status_mercadoria_id_status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ID ORÇAMENTO --}}
                    <div class="mt-6">
                        <label for="log_id_orcamento"
                            class="block text-sm font-medium text-custom-dark-text mb-1">
                            ID do Orçamento
                        </label>
                        <input type="number" name="log_id_orcamento" id="log_id_orcamento"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md
                                   outline-none focus:ring-2 focus:ring-blue-500 border border-gray-300"
                            value="{{ old('log_id_orcamento', $logStatus->log_id_orcamento) }}" required>

                        @error('log_id_orcamento')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- COLUNA DIREITA --}}
                <div>

                    {{-- ID CLIENTE --}}
                    <div>
                        <label for="log_id_cliente"
                            class="block text-sm font-medium text-custom-dark-text mb-1">
                            ID do Cliente
                        </label>
                        <input type="number" name="log_id_cliente" id="log_id_cliente"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md
                                   outline-none focus:ring-2 focus:ring-blue-500 border border-gray-300"
                            value="{{ old('log_id_cliente', $logStatus->log_id_cliente) }}" required>

                        @error('log_id_cliente')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nome Status --}}
                    <div class="mt-6">
                        <label for="log_nome_status"
                            class="block text-sm font-medium text-custom-dark-text mb-1">
                            Nome do Status
                        </label>
                        <input type="text" name="log_nome_status" id="log_nome_status"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md
                                   outline-none focus:ring-2 focus:ring-blue-500 border border-gray-300"
                            maxlength="90"
                            value="{{ old('log_nome_status', $logStatus->log_nome_status) }}" required>

                        @error('log_nome_status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Situação --}}
                    <div class="mt-6">
                        <label for="log_situacao"
                            class="block text-sm font-medium text-custom-dark-text mb-1">
                            Situação
                        </label>

                        <select name="log_situacao" id="log_situacao"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none
                                   focus:ring-2 focus:ring-blue-500 border border-gray-300"
                            required>
                            <option value="">Selecione</option>
                            <option value="1" {{ old('log_situacao', $logStatus->log_situacao) == 1 ? 'selected' : '' }}>Ativo</option>
                            <option value="0" {{ old('log_situacao', $logStatus->log_situacao) == 0 ? 'selected' : '' }}>Inativo</option>
                        </select>

                        @error('log_situacao')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </div>

            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium
                           rounded-md text-white bg-button-save-bg hover:bg-button-save-hover
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                    ATUALIZAR
                </button>
            </div>
        </form>
    </div>

    {{-- Botão voltar --}}
    <div class="flex justify-center mb-8">
        <a href="{{ route('log_status.index') }}"
            class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium
                   rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition">
            VOLTAR PARA A LISTA
        </a>
    </div>

@endsection
