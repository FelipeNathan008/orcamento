@extends('layouts.app') {{-- Assumindo que você tem um layout principal chamado 'app' --}}

@section('title', 'Criar Novo Preço de Customização')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins"> {{-- Contêiner principal para centralizar --}}
    {{-- Título centralizado --}}
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Criar Novo Preço de Customização</h1>

    {{-- Mensagem de sucesso --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- Mensagens de erro de validação --}}
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">Opa!</strong>
        <span class="block sm:inline">Existem alguns problemas com seus dados.</span>
        <ul class="mt-3 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Formulário para criar novo preço de customização --}}
    <div>
        <form action="{{ route('preco_customizacao.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Seção de campos do formulário em uma única coluna --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                {{-- PRIMEIRA COLUNA (ESQUERDA) --}}
                <div class="space-y-6">
                    <!-- Campo Tipo -->
                    <div>
                        <label for="preco_tipo" class="block text-sm font-medium text-custom-dark-text mb-1">Tipo</label>
                        <input type="text" name="preco_tipo" id="preco_tipo"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            value="{{ old('preco_tipo') }}" placeholder="Ex: Estampa DTF" maxlength="45" required>
                        @error('preco_tipo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Tamanho -->
                    <div>
                        <label for="preco_tamanho" class="block text-sm font-medium text-custom-dark-text mb-1">Tamanho</label>
                        <input type="text" name="preco_tamanho" id="preco_tamanho"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            value="{{ old('preco_tamanho') }}" placeholder="Ex: Pequeno (até 9cm)" maxlength="30" required>
                        @error('preco_tamanho')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim da PRIMEIRA COLUNA --}}

                {{-- SEGUNDA COLUNA (DIREITA) --}}
                <div class="space-y-6">
                    <!-- Campo Valor -->
                    <div>
                        <label for="preco_valor" class="block text-sm font-medium text-custom-dark-text mb-1">Valor</label>
                        <input type="text" name="preco_valor" id="preco_valor"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            value="{{ old('preco_valor') }}" placeholder="Ex: 50.99" required>
                        @error('preco_valor')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim da SEGUNDA COLUNA --}}

            </div> {{-- Fim do grid principal --}}

            <!-- Botões de Ação -->
            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                    SALVAR
                </button>
            </div>
            {{-- Botão Voltar unificado e movido para fora do formulário --}}
            <div class="flex justify-center mb-8">
                <a href="{{ route('preco_customizacao.index') }}"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                    VOLTAR PARA A LISTA
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const precoValorInput = document.getElementById('preco_valor');

        if (precoValorInput) {
            precoValorInput.addEventListener('input', function(event) {
                // Remove todos os caracteres não numéricos
                let value = event.target.value.replace(/\D/g, '');

                // Limita a um máximo de 4 dígitos (2 inteiros + 2 decimais)
                value = value.substring(0, 4);

                if (value.length > 2) {
                    // Adiciona o ponto decimal duas posições antes do final
                    value = value.substring(0, value.length - 2) + '.' + value.substring(value.length - 2);
                }

                event.target.value = value;
            });
        }
    });
</script>
@endpush