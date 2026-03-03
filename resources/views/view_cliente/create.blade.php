@extends('layouts.app')

@section('title', 'Cadastrar Novo Cliente')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins"> {{-- Contêiner principal para centralizar o formulário --}}

    {{-- Título do formulário --}}
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Cadastro de Nova Prospecção</h1>


    <form action="{{ route('cliente.store') }}" method="POST" id="clientForm" class="space-y-6">
        @csrf

        {{-- Seção de campos do formulário em layout de duas colunas, centralizada --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- PRIMEIRA COLUNA (ESQUERDA) --}}
            <div>
                {{-- Campo Nome --}}
                <div>
                    <label for="clie_nome" class="block text-sm font-medium text-custom-dark-text mb-1">Nome
                        Completo</label>
                    <input type="text" name="clie_nome" id="clie_nome"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Nome completo da Prospecção" maxlength="80" value="{{ old('clie_nome') }}"
                        required>
                    <p id="erroNome" class="mt-2 text-sm text-red-600 hidden">Por favor, insira um nome completo válido.
                    </p>
                    @error('clie_nome')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Email --}}
                <div class="mt-6">
                    <label for="clie_email" class="block text-sm font-medium text-custom-dark-text mb-1">E-mail</label>
                    <input type="email" name="clie_email" id="clie_email"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="nome@exemplo.com" maxlength="85" value="{{ old('clie_email') }}" required>
                    @error('clie_email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Logradouro --}}
                <div class="mt-6">
                    <label for="clie_logradouro"
                        class="block text-sm font-medium text-custom-dark-text mb-1">Logradouro</label>
                    <input type="text" name="clie_logradouro" id="clie_logradouro"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Rua, Avenida, etc." maxlength="100" value="{{ old('clie_logradouro') }}" required>
                    @error('clie_logradouro')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Bairro --}}
                <div class="mt-6">
                    <label for="clie_bairro" class="block text-sm font-medium text-custom-dark-text mb-1">Bairro</label>
                    <input type="text" name="clie_bairro" id="clie_bairro"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Nome do bairro" maxlength="80" value="{{ old('clie_bairro') }}" required>
                    @error('clie_bairro')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Cidade --}}
                <div class="mt-6">
                    <label for="clie_cidade" class="block text-sm font-medium text-custom-dark-text mb-1">Cidade</label>
                    <input type="text" name="clie_cidade" id="clie_cidade"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Nome da cidade" maxlength="60" value="{{ old('clie_cidade') }}" required>
                    @error('clie_cidade')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div> {{-- Fim da primeira coluna --}}

            {{-- SEGUNDA COLUNA (DIREITA) --}}
            <div>
                {{-- Campo Tipo de Documento --}}
                <div>
                    <label for="clie_tipo_doc" class="block text-sm font-medium text-custom-dark-text mb-1">Tipo de
                        Documento</label>
                    <select name="clie_tipo_doc" id="clie_tipo_doc"
                        class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        required>
                        <option value="" class="text-gray-400">Selecione</option>
                        <option value="CPF" {{ old('clie_tipo_doc') == 'CPF' ? 'selected' : '' }}>CPF</option>
                        <option value="CNPJ" {{ old('clie_tipo_doc') == 'CNPJ' ? 'selected' : '' }}>CNPJ</option>
                    </select>
                    @error('clie_tipo_doc')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Número do Documento --}}
                <div class="mt-6">
                    <label for="clie_doc_numero" class="block text-sm font-medium text-custom-dark-text mb-1">Número do
                        Documento</label>
                    <input type="text" name="clie_doc_numero" id="clie_doc_numero"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Informe o número do documento" maxlength="18" value="{{ old('clie_doc_numero') }}"
                        required>
                    @error('clie_doc_numero')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campos Celular e Telefone Lado a Lado --}}
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Campo Celular (agora obrigatório) --}}
                    <div>
                        <label for="clie_celular" class="block text-sm font-medium text-custom-dark-text mb-1">Número
                            Celular</label>
                        <input type="text" name="clie_celular" id="clie_celular"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="(XX) XXXXX-XXXX" maxlength="15" required value="{{ old('clie_celular') }}">
                        @error('clie_celular')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo Telefone (opcional) --}}
                    <div>
                        <label for="clie_telefone" class="block text-sm font-medium text-custom-dark-text mb-1">Número
                            Telefone</label>
                        <input type="text" name="clie_telefone" id="clie_telefone"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="(XX) XXXX-XXXX" maxlength="14" value="{{ old('clie_telefone') }}">
                        @error('clie_telefone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p id="mensagemErroContato" class="mt-2 text-sm text-red-600 hidden">Por favor, preencha o celular ou o
                    telefone.</p>

                {{-- Campo CEP --}}
                <div class="mt-6">
                    <label for="clie_cep" class="block text-sm font-medium text-custom-dark-text mb-1">CEP</label>
                    <input type="text" name="clie_cep" id="clie_cep"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="XXXXX-XXX" maxlength="9" value="{{ old('clie_cep') }}" required>
                    @error('clie_cep')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo UF (Estado) --}}
                <div class="mt-6">
                    <label for="clie_uf" class="block text-sm font-medium text-custom-dark-text mb-1">UF
                        (Estado)</label>
                    <select name="clie_uf" id="clie_uf"
                        class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        required>
                        <option value="" class="text-gray-400">Selecione o Estado</option>
                        <option value="AC" {{ old('clie_uf') == 'AC' ? 'selected' : '' }}>Acre</option>
                        <option value="AL" {{ old('clie_uf') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                        <option value="AP" {{ old('clie_uf') == 'AP' ? 'selected' : '' }}>Amapá</option>
                        <option value="AM" {{ old('clie_uf') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                        <option value="BA" {{ old('clie_uf') == 'BA' ? 'selected' : '' }}>Bahia</option>
                        <option value="CE" {{ old('clie_uf') == 'CE' ? 'selected' : '' }}>Ceará</option>
                        <option value="DF" {{ old('clie_uf') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                        <option value="ES" {{ old('clie_uf') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                        <option value="GO" {{ old('clie_uf') == 'GO' ? 'selected' : '' }}>Goiás</option>
                        <option value="MA" {{ old('clie_uf') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                        <option value="MT" {{ old('clie_uf') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                        <option value="MS" {{ old('clie_uf') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                        <option value="MG" {{ old('clie_uf') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                        <option value="PA" {{ old('clie_uf') == 'PA' ? 'selected' : '' }}>Pará</option>
                        <option value="PB" {{ old('clie_uf') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                        <option value="PR" {{ old('clie_uf') == 'PR' ? 'selected' : '' }}>Paraná</option>
                        <option value="PE" {{ old('clie_uf') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                        <option value="PI" {{ old('clie_uf') == 'PI' ? 'selected' : '' }}>Piauí</option>
                        <option value="RJ" {{ old('clie_uf') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                        <option value="RN" {{ old('clie_uf') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                        <option value="RS" {{ old('clie_uf') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                        <option value="RO" {{ old('clie_uf') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                        <option value="RR" {{ old('clie_uf') == 'RR' ? 'selected' : '' }}>Roraima</option>
                        <option value="SC" {{ old('clie_uf') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                        <option value="SP" {{ old('clie_uf') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                        <option value="SE" {{ old('clie_uf') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                        <option value="TO" {{ old('clie_uf') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                    </select>
                    @error('clie_uf')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div> {{-- Fim da segunda coluna --}}

        </div> {{-- Fim do grid principal --}}

        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                SALVAR
            </button>
        </div>
        {{-- Botão Voltar unificado e movido para fora do formulário --}}
        <div class="flex justify-center mb-8">
            <a href="{{ route('cliente.index') }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>
    </form>
</div>

@push('scripts')
{{-- Inclua o jQuery e o jQuery Mask Plugin --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        var docNumero = $('#clie_doc_numero');
        var tipoDoc = $('#clie_tipo_doc');
        var celular = $('#clie_celular');
        var telefone = $('#clie_telefone');
        var cep = $('#clie_cep');
        var nome = $('#clie_nome');
        var formulario = $('#clientForm');
        var mensagemErroNome = $('#erroNome');
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
                docNumero.attr('maxlength', '18');
            }
        }

        function validarNomeCompleto(nome) {
            nome = nome.trim();
            const palavras = nome.split(' ');
            if (palavras.length < 2) {
                return false;
            }
            const regex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;
            for (const palavra of palavras) {
                if (palavra.trim() === '' || !regex.test(palavra)) {
                    return false;
                }
            }
            return true;
        }

        // Aplica a máscara inicial se já houver um valor selecionado
        applyDocMask(tipoDoc.val());
        if (docNumero.val()) {
            docNumero.val(docNumero.val());
        }

        // Altera a máscara quando o tipo de documento muda
        tipoDoc.on('change', function() {
            docNumero.val('');
            applyDocMask($(this).val());
        });

        // Máscaras para telefone celular e fixo
        celular.mask('(00) 00000-0000');
        telefone.mask('(00) 0000-0000');

        // Máscara para CEP
        cep.mask('00000-000');

        $('#clie_nome').on('keypress', function(e) {
            var char = String.fromCharCode(e.which);
            if (!/^[a-zA-ZÀ-ÿ\s]+$/.test(char)) {
                e.preventDefault();
            }
        });

        // Lógica de validação do formulário no evento de SUBMIT
        formulario.on('submit', function(event) {
            let isValid = true;

            // Valida o campo de nome completo
            if (!validarNomeCompleto(nome.val())) {
                mensagemErroNome.text('Por favor, insira um nome completo válido (ex: Maria da Silva).').removeClass('hidden');
                nome.focus();
                isValid = false;
            } else {
                mensagemErroNome.addClass('hidden');
            }

            // Valida os campos de contato (celular OU telefone)
            const celularValor = celular.val().trim();
            const telefoneValor = telefone.val().trim();
            if (!celularValor && !telefoneValor) {
                mensagemErroContato.removeClass('hidden');
                if (isValid) {
                    celular.focus();
                }
                isValid = false;
            } else {
                mensagemErroContato.addClass('hidden');
            }

            if (!isValid) {
                event.preventDefault();
            } else {
                console.log('Formulário validado com sucesso!');
            }
        });

        // Opcional: Adiciona validação em tempo real para o campo de nome
        nome.on('blur', function() {
            if (!validarNomeCompleto(nome.val())) {
                mensagemErroNome.text('Por favor, insira um nome completo válido (ex: Maria da Silva).').removeClass('hidden');
            } else {
                mensagemErroNome.addClass('hidden');
            }
        });

        // Validação em tempo real para os campos de contato
        celular.on('blur', function() {
            const celularValor = celular.val().trim();
            const telefoneValor = telefone.val().trim();
            if (celularValor || telefoneValor) {
                mensagemErroContato.addClass('hidden');
            }
        });

        telefone.on('blur', function() {
            const celularValor = celular.val().trim();
            const telefoneValor = telefone.val().trim();
            if (celularValor || telefoneValor) {
                mensagemErroContato.addClass('hidden');
            }
        });
    });
</script>
@endpush

@endsection