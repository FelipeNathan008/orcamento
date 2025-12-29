@extends('layouts.app')

{{-- Define o título da página usando o nome do cliente --}}
@section('title', 'Editar Cliente: ' . $cliente->clie_nome)

@section('content')
    {{-- Contêiner principal para centralizar o formulário e definir largura máxima --}}
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">
        {{-- Título do formulário, centralizado e com cor customizada --}}
        <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Editar Cliente: {{ $cliente->clie_nome }}</h1>

        {{-- Formulário de edição com método PUT para atualização --}}
        <form id="editForm" action="{{ route('cliente.update', $cliente->id_cliente) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT') {{-- Importante: Laravel usa PUT para atualizar recursos --}}

            {{-- Seção de campos do formulário organizada em layout de duas colunas (responsivo) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- PRIMEIRA COLUNA (ESQUERDA) - Campos Nome, E-mail, Logradouro, Bairro, Cidade --}}
                <div>
                    <!-- Campo Nome -->
                    <div>
                        <label for="clie_nome" class="block text-sm font-medium text-custom-dark-text mb-1">Nome
                            Completo</label>
                        <input type="text" name="clie_nome" id="clie_nome"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Nome completo da Prospecção" maxlength="80"
                            value="{{ old('clie_nome', $cliente->clie_nome) }}" required
                            oninput="this.value = this.value.replace(/[0-9]/g, '');">

                        @error('clie_nome')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo E-mail -->
                    <div class="mt-6">
                        <label for="clie_email" class="block text-sm font-medium text-custom-dark-text mb-1">E-mail</label>
                        <input type="email" name="clie_email" id="clie_email"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="nome@exemplo.com" maxlength="85"
                            value="{{ old('clie_email', $cliente->clie_email) }}" required>
                        @error('clie_email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Logradouro -->
                    <div class="mt-6">
                        <label for="clie_logradouro"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Logradouro</label>
                        <input type="text" name="clie_logradouro" id="clie_logradouro"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Rua, Avenida, etc." maxlength="100"
                            value="{{ old('clie_logradouro', $cliente->clie_logradouro) }}" required>
                        @error('clie_logradouro')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Bairro -->
                    <div class="mt-6">
                        <label for="clie_bairro" class="block text-sm font-medium text-custom-dark-text mb-1">Bairro</label>
                        <input type="text" name="clie_bairro" id="clie_bairro"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Nome do bairro" maxlength="80"
                            value="{{ old('clie_bairro', $cliente->clie_bairro) }}" required>
                        @error('clie_bairro')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Cidade -->
                    <div class="mt-6">
                        <label for="clie_cidade" class="block text-sm font-medium text-custom-dark-text mb-1">Cidade</label>
                        <input type="text" name="clie_cidade" id="clie_cidade"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Nome da cidade" maxlength="60"
                            value="{{ old('clie_cidade', $cliente->clie_cidade) }}" required>
                        @error('clie_cidade')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim da primeira coluna --}}

                {{-- SEGUNDA COLUNA (DIREITA) - Campos Tipo Doc, Número Doc, Telefone, CEP, UF --}}
                <div>
                    <!-- Campo Tipo de Documento (Select) -->
                    <div>
                        <label for="clie_tipo_doc" class="block text-sm font-medium text-custom-dark-text mb-1">Tipo de
                            Documento</label>
                        <select name="clie_tipo_doc" id="clie_tipo_doc"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="" class="text-gray-400">Selecione</option>
                            <option value="CPF" {{ old('clie_tipo_doc', $cliente->clie_tipo_doc) == 'CPF' ? 'selected' : '' }}>CPF</option>
                            <option value="CNPJ" {{ old('clie_tipo_doc', $cliente->clie_tipo_doc) == 'CNPJ' ? 'selected' : '' }}>CNPJ</option>
                        </select>
                        @error('clie_tipo_doc')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo CNPJ ou CPF (Input para o número do documento) -->
                    <div class="mt-6">
                        <label for="clie_doc_numero" class="block text-sm font-medium text-custom-dark-text mb-1">Número do
                            Documento</label>
                        <input type="text" name="clie_doc_numero" id="clie_doc_numero"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Informe o número do documento" maxlength="18"
                            value="{{ old('clie_doc_numero', $cliente->clie_tipo_doc === 'CPF' ? $cliente->clie_cpf : ($cliente->clie_tipo_doc === 'CNPJ' ? $cliente->clie_cnpj : null)) }}"
                            required>
                        @error('clie_doc_numero')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- CAMPOS CELULAR E TELEFONE LADO A LADO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        {{-- Campo Celular --}}
                        <div>
                            <label for="clie_celular" class="block text-sm font-medium text-custom-dark-text mb-1">Número
                                Celular</label>
                            <input type="text" name="clie_celular" id="clie_celular"
                                class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                                placeholder="(XX) XXXXX-XXXX" value="{{ old('clie_celular', $cliente->clie_celular) }}">
                            @error('clie_celular')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Telefone --}}
                        <div>
                            <label for="clie_telefone" class="block text-sm font-medium text-custom-dark-text mb-1">Número
                                Telefone</label>
                            <input type="text" name="clie_telefone" id="clie_telefone"
                                class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                                placeholder="(XX) XXXX-XXXX" value="{{ old('clie_telefone', $cliente->clie_telefone) }}">
                            @error('clie_telefone')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    {{-- Mensagem de erro para Celular/Telefone --}}
                    <p id="mensagemErroContato" class="mt-2 text-sm text-red-600 hidden">Por favor, preencha o celular ou o
                        telefone.</p>

                    <!-- Campo CEP -->
                    <div class="mt-6">
                        <label for="clie_cep" class="block text-sm font-medium text-custom-dark-text mb-1">CEP</label>
                        <input type="text" name="clie_cep" id="clie_cep"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="XXXXX-XXX" maxlength="9" pattern="[0-9]{5}\-?[0-9]{3}"
                            value="{{ old('clie_cep', $cliente->clie_cep) }}" required>
                        @error('clie_cep')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo UF AGORA É UM SELECT -->
                    <div class="mt-6">
                        <label for="clie_uf" class="block text-sm font-medium text-custom-dark-text mb-1">UF
                            (Estado)</label>
                        <select name="clie_uf" id="clie_uf"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="" class="text-gray-400">Selecione o Estado</option>
                            @php
                                $estados = [
                                    'AC' => 'Acre',
                                    'AL' => 'Alagoas',
                                    'AP' => 'Amapá',
                                    'AM' => 'Amazonas',
                                    'BA' => 'Bahia',
                                    'CE' => 'Ceará',
                                    'DF' => 'Distrito Federal',
                                    'ES' => 'Espírito Santo',
                                    'GO' => 'Goiás',
                                    'MA' => 'Maranhão',
                                    'MT' => 'Mato Grosso',
                                    'MS' => 'Mato Grosso do Sul',
                                    'MG' => 'Minas Gerais',
                                    'PA' => 'Pará',
                                    'PB' => 'Paraíba',
                                    'PR' => 'Paraná',
                                    'PE' => 'Pernambuco',
                                    'PI' => 'Piauí',
                                    'RJ' => 'Rio de Janeiro',
                                    'RN' => 'Rio Grande do Norte',
                                    'RS' => 'Rio Grande do Sul',
                                    'RO' => 'Rondônia',
                                    'RR' => 'Roraima',
                                    'SC' => 'Santa Catarina',
                                    'SP' => 'São Paulo',
                                    'SE' => 'Sergipe',
                                    'TO' => 'Tocantins'
                                ];
                                $selectedUf = old('clie_uf', $cliente->clie_uf ?? '');
                            @endphp
                            @foreach ($estados as $ufAbbr => $ufNome)
                                <option value="{{ $ufAbbr }}" {{ $selectedUf == $ufAbbr ? 'selected' : '' }}>
                                    {{ $ufNome }}
                                </option>
                            @endforeach
                        </select>
                        @error('clie_uf')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim da segunda coluna --}}

            </div> {{-- Fim do grid principal --}}

            <!-- Botão de Envio -->
            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                    ATUALIZAR
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        {{-- Inclua o jQuery e o jQuery Mask Plugin --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script>
            $(document).ready(function () {
                var docNumero = $('#clie_doc_numero');
                var tipoDoc = $('#clie_tipo_doc');
                var celularInput = $('#clie_celular');
                var telefoneInput = $('#clie_telefone');
                var form = $('#editForm');
                var mensagemErroContato = $('#mensagemErroContato');

                function applyDocMask(type) {
                    docNumero.unmask();
                    if (type === 'CPF') {
                        docNumero.mask('000.000.000-00');
                        docNumero.attr('placeholder', '000.000.000-00');
                        docNumero.attr('maxlength', '14');
                    } else if (type === 'CNPJ') {
                        docNumero.mask('00.000.000/0000-00');
                        docNumero.attr('placeholder', '00.000.000/0000-00');
                        docNumero.attr('maxlength', '18');
                    } else {
                        docNumero.attr('placeholder', 'Informe o número do documento');
                        docNumero.attr('maxlength', '45');
                    }
                }

                // Impede números no campo Nome
                $('#clie_nome').on('input', function () {
                    this.value = this.value.replace(/[0-9]/g, '');
                });

                // Lógica de preenchimento inicial e aplicação da máscara
                var initialDocValue = "{{ $cliente->clie_tipo_doc === 'CPF' ? $cliente->clie_cpf : ($cliente->clie_tipo_doc === 'CNPJ' ? $cliente->clie_cnpj : null) }}";
                var initialDocType = tipoDoc.val();

                docNumero.val(initialDocValue);
                applyDocMask(initialDocType);

                // Altera a máscara quando o tipo de documento muda
                tipoDoc.on('change', function () {
                    docNumero.val('');
                    applyDocMask($(this).val());
                });

                // Aplica máscaras nos campos de contato
                telefoneInput.mask('(00) 0000-0000');
                celularInput.mask('(00) 00000-0000');

                // Garante que os valores iniciais (se existirem) sejam mascarados
                if (telefoneInput.val()) {
                    telefoneInput.mask('(00) 0000-0000').val(telefoneInput.val());
                }
                if (celularInput.val()) {
                    celularInput.mask('(00) 00000-0000').val(celularInput.val());
                }

                // Validação para impedir o envio se Celular e Telefone estiverem vazios
                form.on('submit', function (event) {
                    var celularValue = celularInput.val().replace(/\D/g, '');
                    var telefoneValue = telefoneInput.val().replace(/\D/g, '');

                    if (celularValue === '' && telefoneValue === '') {
                        event.preventDefault();
                        mensagemErroContato.removeClass('hidden');
                    } else {
                        mensagemErroContato.addClass('hidden');
                    }
                });

                // Lógica para esconder a mensagem de erro quando o usuário preencher um dos campos
                celularInput.on('input', function () {
                    var celularValue = $(this).val().replace(/\D/g, '');
                    var telefoneValue = telefoneInput.val().replace(/\D/g, '');
                    if (celularValue !== '' || telefoneValue !== '') {
                        mensagemErroContato.addClass('hidden');
                    }
                });
                telefoneInput.on('input', function () {
                    var celularValue = celularInput.val().replace(/\D/g, '');
                    var telefoneValue = $(this).val().replace(/\D/g, '');
                    if (celularValue !== '' || telefoneValue !== '') {
                        mensagemErroContato.addClass('hidden');
                    }
                });
            });
        </script>

    @endpush

@endsection