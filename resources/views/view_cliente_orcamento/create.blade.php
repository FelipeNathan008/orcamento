@extends('layouts.app')

@section('title', 'Criar Novo Cliente de Orçamento')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins"> {{-- Contêiner principal unificado --}}

    {{-- Título do formulário unificado --}}
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Cadastro de Cliente</h1>



    <form action="{{ route('cliente_orcamento.store') }}" method="POST" id="clientForm" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- PRIMEIRA COLUNA (ESQUERDA) --}}
            <div>
                {{-- Campo Nome --}}
                <div>
                    <label for="clie_orc_nome" class="block text-sm font-medium text-custom-dark-text mb-1">Nome</label>
                    <input type="text" name="clie_orc_nome" id="clie_orc_nome"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Nome completo do cliente" maxlength="80" value="{{ old('clie_orc_nome') }}"
                        required>
                    <p id="erroNome" class="mt-2 text-sm text-red-600 hidden">Por favor, insira um nome completo válido.
                    </p>
                    @error('clie_orc_nome')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Email --}}
                <div class="mt-6">
                    <label for="clie_orc_email"
                        class="block text-sm font-medium text-custom-dark-text mb-1">E-mail</label>
                    <input type="email" name="clie_orc_email" id="clie_orc_email"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="nome@exemplo.com" maxlength="85" value="{{ old('clie_orc_email') }}" required>
                    @error('clie_orc_email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Logradouro --}}
                <div class="mt-6">
                    <label for="clie_orc_logradouro"
                        class="block text-sm font-medium text-custom-dark-text mb-1">Logradouro</label>
                    <input type="text" name="clie_orc_logradouro" id="clie_orc_logradouro"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Rua, Avenida, etc." maxlength="100" value="{{ old('clie_orc_logradouro') }}"
                        required>
                    @error('clie_orc_logradouro')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Bairro --}}
                <div class="mt-6">
                    <label for="clie_orc_bairro"
                        class="block text-sm font-medium text-custom-dark-text mb-1">Bairro</label>
                    <input type="text" name="clie_orc_bairro" id="clie_orc_bairro"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Nome do bairro" maxlength="80" value="{{ old('clie_orc_bairro') }}" required>
                    @error('clie_orc_bairro')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Cidade --}}
                <div class="mt-6">
                    <label for="clie_orc_cidade"
                        class="block text-sm font-medium text-custom-dark-text mb-1">Cidade</label>
                    <input type="text" name="clie_orc_cidade" id="clie_orc_cidade"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Nome da cidade" maxlength="60" value="{{ old('clie_orc_cidade') }}" required>
                    @error('clie_orc_cidade')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- SEGUNDA COLUNA (DIREITA) --}}
            <div>
                {{-- Campo Tipo de Documento --}}
                <div>
                    <label for="clie_orc_tipo_doc" class="block text-sm font-medium text-custom-dark-text mb-1">Tipo de
                        Documento</label>
                    <select name="clie_orc_tipo_doc" id="clie_orc_tipo_doc"
                        class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        required>
                        <option value="" class="text-gray-400">Selecione</option>
                        <option value="CPF" {{ old('clie_orc_tipo_doc') == 'CPF' ? 'selected' : '' }}>CPF</option>
                        <option value="CNPJ" {{ old('clie_orc_tipo_doc') == 'CNPJ' ? 'selected' : '' }}>CNPJ</option>
                    </select>
                    @error('clie_orc_tipo_doc')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Número do Documento --}}
                <div class="mt-6">
                    <label for="clie_orc_doc_numero" class="block text-sm font-medium text-custom-dark-text mb-1">Número
                        do Documento</label>
                    <input type="text" name="clie_orc_doc_numero" id="clie_orc_doc_numero"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Informe o número do documento" maxlength="18"
                        value="{{ old('clie_orc_doc_numero') }}" required>
                    @error('clie_orc_doc_numero')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campos Celular e Telefone Lado a Lado --}}
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Campo Celular (agora obrigatório) --}}
                    <div>
                        <label for="clie_orc_celular"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Número
                            Celular</label>
                        <input type="text" name="clie_orc_celular" id="clie_orc_celular"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="(XX) XXXXX-XXXX" maxlength="15" required value="{{ old('clie_orc_celular') }}">
                        @error('clie_orc_celular')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo Telefone (opcional) --}}
                    <div>
                        <label for="clie_orc_telefone"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Número
                            Telefone</label>
                        <input type="text" name="clie_orc_telefone" id="clie_orc_telefone"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="(XX) XXXX-XXXX" maxlength="14" value="{{ old('clie_orc_telefone') }}">
                        @error('clie_orc_telefone')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                {{-- MENSAGEM DE ERRO DE CONTATO (ADICIONADA) --}}
                <p id="mensagemErroContato" class="mt-2 text-sm text-red-600 hidden">Por favor, preencha o celular ou o telefone.</p>

                {{-- Campo CEP --}}
                <div class="mt-6">
                    <label for="clie_orc_cep" class="block text-sm font-medium text-custom-dark-text mb-1">CEP</label>
                    <input type="text" name="clie_orc_cep" id="clie_orc_cep"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="XXXXX-XXX" maxlength="9" value="{{ old('clie_orc_cep') }}" required>
                    @error('clie_orc_cep')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo UF --}}
                <div class="mt-6">
                    <label for="clie_orc_uf" class="block text-sm font-medium text-custom-dark-text mb-1">UF
                        (Estado)</label>
                    <select name="clie_orc_uf" id="clie_orc_uf"
                        class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        required>
                        <option value="" class="text-gray-400">Selecione o Estado</option>
                        <option value="AC" {{ old('clie_orc_uf') == 'AC' ? 'selected' : '' }}>Acre</option>
                        <option value="AL" {{ old('clie_orc_uf') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                        <option value="AP" {{ old('clie_orc_uf') == 'AP' ? 'selected' : '' }}>Amapá</option>
                        <option value="AM" {{ old('clie_orc_uf') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                        <option value="BA" {{ old('clie_orc_uf') == 'BA' ? 'selected' : '' }}>Bahia</option>
                        <option value="CE" {{ old('clie_orc_uf') == 'CE' ? 'selected' : '' }}>Ceará</option>
                        <option value="DF" {{ old('clie_orc_uf') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                        <option value="ES" {{ old('clie_orc_uf') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                        <option value="GO" {{ old('clie_orc_uf') == 'GO' ? 'selected' : '' }}>Goiás</option>
                        <option value="MA" {{ old('clie_orc_uf') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                        <option value="MT" {{ old('clie_orc_uf') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                        <option value="MS" {{ old('clie_orc_uf') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                        <option value="MG" {{ old('clie_orc_uf') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                        <option value="PA" {{ old('clie_orc_uf') == 'PA' ? 'selected' : '' }}>Pará</option>
                        <option value="PB" {{ old('clie_orc_uf') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                        <option value="PR" {{ old('clie_orc_uf') == 'PR' ? 'selected' : '' }}>Paraná</option>
                        <option value="PE" {{ old('clie_orc_uf') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                        <option value="PI" {{ old('clie_orc_uf') == 'PI' ? 'selected' : '' }}>Piauí</option>
                        <option value="RJ" {{ old('clie_orc_uf') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                        <option value="RN" {{ old('clie_orc_uf') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                        <option value="RS" {{ old('clie_orc_uf') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                        <option value="RO" {{ old('clie_orc_uf') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                        <option value="RR" {{ old('clie_orc_uf') == 'RR' ? 'selected' : '' }}>Roraima</option>
                        <option value="SC" {{ old('clie_orc_uf') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                        <option value="SP" {{ old('clie_orc_uf') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                        <option value="SE" {{ old('clie_orc_uf') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                        <option value="TO" {{ old('clie_orc_uf') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                    </select>
                    @error('clie_orc_uf')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Campo Código Interno --}}
        <div class="md:col-span-2 mt-6">
            <label for="clie_orc_cod_interno"
                class="block text-sm font-medium text-custom-dark-text mb-1">
                Código Interno
            </label>

            <input type="text"
                name="clie_orc_cod_interno"
                id="clie_orc_cod_interno"
                class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                placeholder="Informe o código interno"
                maxlength="60"
                value="{{ old('clie_orc_cod_interno') }}">

            @error('clie_orc_cod_interno')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                SALVAR
            </button>
        </div>
        {{-- Botão Voltar unificado e movido para fora do formulário --}}
        <div class="flex justify-center mb-8">
            <a href="{{ route('cliente_orcamento.index') }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>
    </form>
</div>


@endsection

@push('scripts')
{{-- Inclua o jQuery e o jQuery Mask Plugin --}}
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        var docNumero = $('#clie_orc_doc_numero');
        var tipoDoc = $('#clie_orc_tipo_doc');
        var celular = $('#clie_orc_celular');
        var telefone = $('#clie_orc_telefone');
        var cep = $('#clie_orc_cep');
        var nome = $('#clie_orc_nome');
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

        $('#clie_orc_nome').on('keypress', function(e) {
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
                mensagemErroNome.text('Por favor, insira um nome completo válido (ex: Maria da Silva).').removeClass(
                    'hidden');
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
                mensagemErroNome.text('Por favor, insira um nome completo válido (ex: Maria da Silva).')
                    .removeClass('hidden');
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