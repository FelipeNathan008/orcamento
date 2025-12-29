<!-- resources/views/empresas/create.blade.php -->
@extends('layouts.app')

@section('title', 'Cadastrar Nova Empresa')

@section('content')
    {{-- Contêiner principal para centralizar o formulário e definir largura máxima --}}
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">
        {{-- Título do formulário, centralizado e com cor customizada --}}
        <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Cadastro de Nova Empresa</h1>

        {{-- Formulário de cadastro --}}
        <form action="{{ route('empresa.store') }}" method="POST" class="space-y-6">
            @csrf {{-- Proteção CSRF obrigatória no Laravel --}}

            {{-- Seção de campos do formulário organizada em layout de duas colunas (responsivo) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Campo Nome da Empresa - Agora ocupa duas colunas --}}
                <div class="md:col-span-2">
                    <!-- Campo Nome da Empresa -->
                    <div>
                        <label for="emp_nome" class="block text-sm font-medium text-custom-dark-text mb-1">Nome da Empresa</label>
                        <input type="text"
                               name="emp_nome"
                               id="emp_nome"
                               class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                               placeholder="Nome completo da empresa"
                               maxlength="85"
                               value="{{ old('emp_nome') }}"
                               required>
                        @error('emp_nome')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- PRIMEIRA COLUNA (ESQUERDA) - Campos Cidade, Bairro, Logradouro --}}
                <div>
                    <!-- Campo Cidade -->
                    <div class="mt-0"> {{-- Removido mt-6 para alinhar com o campo acima --}}
                        <label for="emp_cidade" class="block text-sm font-medium text-custom-dark-text mb-1">Cidade</label>
                        <input type="text"
                               name="emp_cidade"
                               id="emp_cidade"
                               class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                               placeholder="Nome da cidade"
                               maxlength="45"
                               value="{{ old('emp_cidade') }}"
                               required>
                        @error('emp_cidade')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Bairro -->
                    <div class="mt-6">
                        <label for="emp_bairro" class="block text-sm font-medium text-custom-dark-text mb-1">Bairro</label>
                        <input type="text"
                               name="emp_bairro"
                               id="emp_bairro"
                               class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                               placeholder="Nome do bairro"
                               maxlength="45"
                               value="{{ old('emp_bairro') }}"
                               required>
                        @error('emp_bairro')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Logradouro -->
                    <div class="mt-6">
                        <label for="emp_logradouro" class="block text-sm font-medium text-custom-dark-text mb-1">Logradouro</label>
                        <input type="text"
                               name="emp_logradouro"
                               id="emp_logradouro"
                               class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                               placeholder="Rua, Avenida, etc."
                               maxlength="85"
                               value="{{ old('emp_logradouro') }}"
                               required>
                        @error('emp_logradouro')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim da primeira coluna --}}

                {{-- SEGUNDA COLUNA (DIREITA) - Campos CNPJ, UF, CEP --}}
                <div>
                    <!-- Campo CNPJ -->
                    <div>
                        <label for="emp_cnpj" class="block text-sm font-medium text-custom-dark-text mb-1">CNPJ</label>
                        <input type="text"
                               name="emp_cnpj"
                               id="emp_cnpj"
                               class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                               placeholder="00.000.000/0000-00"
                               maxlength="18"
                               value="{{ old('emp_cnpj') }}"
                               required>
                        @error('emp_cnpj')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo UF AGORA É UM SELECT -->
                    <div class="mt-6">
                        <label for="emp_uf" class="block text-sm font-medium text-custom-dark-text mb-1">UF (Estado)</label>
                        <select name="emp_uf"
                                id="emp_uf"
                                class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                                required>
                            <option value="" class="text-gray-400">Selecione o Estado</option>
                            @php
                                $estados = [
                                    'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas', 'BA' => 'Bahia',
                                    'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo', 'GO' => 'Goiás',
                                    'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul', 'MG' => 'Minas Gerais',
                                    'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná', 'PE' => 'Pernambuco', 'PI' => 'Piauí',
                                    'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte', 'RS' => 'Rio Grande do Sul',
                                    'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina', 'SP' => 'São Paulo',
                                    'SE' => 'Sergipe', 'TO' => 'Tocantins'
                                ];
                                $selectedUf = old('emp_uf');
                            @endphp
                            @foreach ($estados as $ufAbbr => $ufNome)
                                <option value="{{ $ufAbbr }}" {{ $selectedUf == $ufAbbr ? 'selected' : '' }}>{{ $ufNome }}</option>
                            @endforeach
                        </select>
                        @error('emp_uf')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo CEP -->
                    <div class="mt-6">
                        <label for="emp_cep" class="block text-sm font-medium text-custom-dark-text mb-1">CEP</label>
                        <input type="text"
                               name="emp_cep"
                               id="emp_cep"
                               class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                               placeholder="XXXXX-XXX"
                               maxlength="9"
                               value="{{ old('emp_cep') }}"
                               required>
                        @error('emp_cep')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim da segunda coluna --}}

            </div> {{-- Fim do grid principal --}}

            <!-- Botão de Envio -->
            <div class="flex justify-center mt-8">
                <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                    CADASTRAR EMPRESA
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        {{-- Inclua o jQuery e o jQuery Mask Plugin --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script>
            $(document).ready(function() {
                // Máscara para CNPJ
                $('#emp_cnpj').mask('00.000.000/0000-00');

                // Máscara para CEP
                $('#emp_cep').mask('00000-000');
            });
        </script>
    @endpush
@endsection
