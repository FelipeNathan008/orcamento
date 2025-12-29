@extends('layouts.app')

@section('title', 'Editar Financeiro: ' . $financeiro->fin_nome_cliente)

@section('content')
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

        <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
            Editar Financeiro: {{ $financeiro->fin_nome_cliente }}
        </h1>

        <form id="editForm" action="{{ route('financeiro.update', $financeiro->id_fin) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- COLUNA ESQUERDA --}}
                <div>

                    {{-- Nome Cliente --}}
                    <div>
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">Nome do Cliente</label>
                        <input type="text" name="fin_nome_cliente"
                               class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md 
                                      outline-none focus:ring-2 focus:ring-blue-500 border border-gray-300"
                               placeholder="Nome do cliente"
                               value="{{ old('fin_nome_cliente', $financeiro->fin_nome_cliente) }}"
                               maxlength="80"
                               required>
                        @error('fin_nome_cliente')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Valor Total --}}
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">Valor Total</label>
                        <input type="number" step="0.01" name="fin_valor_total"
                               class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md 
                                      outline-none focus:ring-2 focus:ring-blue-500 border border-gray-300"
                               placeholder="0,00"
                               value="{{ old('fin_valor_total', $financeiro->fin_valor_total) }}"
                               required>
                        @error('fin_valor_total')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div> {{-- FIM ESQUERDA --}}

                {{-- COLUNA DIREITA --}}
                <div>

                    {{-- ID Orçamento --}}
                    <div>
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">ID Orçamento</label>
                        <input type="number" name="id_orcamento"
                               class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md 
                                      outline-none focus:ring-2 focus:ring-blue-500 border border-gray-300"
                               placeholder="ID do orçamento"
                               value="{{ old('id_orcamento', $financeiro->id_orcamento) }}"
                               required>
                        @error('id_orcamento')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ID Cliente --}}
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">ID Cliente</label>
                        <input type="number" name="id_cliente"
                               class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md 
                                      outline-none focus:ring-2 focus:ring-blue-500 border border-gray-300"
                               placeholder="ID Cliente"
                               value="{{ old('id_cliente', $financeiro->id_cliente) }}"
                               required>
                        @error('id_cliente')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">Status Financeiro</label>
                        <select name="fin_status"
                                class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm 
                                       outline-none focus:ring-2 focus:ring-blue-500 border border-gray-300"
                                required>
                            <option value="">Selecione</option>
                            <option value="Em aberto" {{ old('fin_status', $financeiro->fin_status) == 'Em aberto' ? 'selected' : '' }}>
                                Em aberto
                            </option>
                            <option value="Pago" {{ old('fin_status', $financeiro->fin_status) == 'Pago' ? 'selected' : '' }}>
                                Pago
                            </option>
                            <option value="Cancelado" {{ old('fin_status', $financeiro->fin_status) == 'Cancelado' ? 'selected' : '' }}>
                                Cancelado
                            </option>
                        </select>
                        @error('fin_status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div> {{-- FIM DIREITA --}}

            </div> {{-- FIM GRID --}}

            {{-- BOTÃO --}}
            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 shadow-sm text-sm font-medium rounded-md text-white 
                           bg-button-edit-bg hover:bg-button-edit-hover focus:ring-2 focus:ring-offset-2 
                           focus:ring-button-edit-bg transition">
                    ATUALIZAR
                </button>
            </div>

        </form>

    </div>

@endsection
