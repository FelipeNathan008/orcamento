@extends('layouts.app')

@section('title', 'Editar Cliente de Orçamento: ' . $clienteOrcamento->clie_orc_nome)

@section('content')

@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header title="Editar Cliente de Orçamento" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="clienteOrcamentoForm"
            action="{{ route('cliente_orcamento.update', CryptHelper::encrypt($clienteOrcamento->id_co)) }}"
            method="POST"
            class="px-6 sm:px-8 pt-6 pb-8">

            @csrf
            @method('PUT')

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">

                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>

                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do cliente</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Atualize as informações cadastrais do cliente de orçamento.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2">
                        <label for="clie_orc_nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Nome
                        </label>

                        <input type="text" name="clie_orc_nome" id="clie_orc_nome"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Nome completo do cliente" maxlength="45"
                            value="{{ old('clie_orc_nome', $clienteOrcamento->clie_orc_nome) }}" required>
                    </div>

                    <div>
                        <label for="clie_orc_email" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            E-mail
                        </label>

                        <input type="email" name="clie_orc_email" id="clie_orc_email"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="nome@exemplo.com" maxlength="45"
                            value="{{ old('clie_orc_email', $clienteOrcamento->clie_orc_email) }}" required>
                    </div>

                    <div>
                        <label for="clie_orc_ie" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Inscrição Estadual (Coloque "-" se for nulo)
                        </label>

                        <input type="text" name="clie_orc_ie" id="clie_orc_ie"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Informe a inscrição estadual" maxlength="90"
                            value="{{ old('clie_orc_ie', $clienteOrcamento->clie_orc_ie) }}" required>
                    </div>

                    <div>
                        <label for="clie_orc_tipo_doc" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Tipo de documento
                        </label>

                        <select name="clie_orc_tipo_doc" id="clie_orc_tipo_doc"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            required>
                            <option value="">Selecione</option>
                            <option value="CPF" {{ old('clie_orc_tipo_doc', $clienteOrcamento->clie_orc_tipo_doc) === 'CPF' ? 'selected' : '' }}>CPF</option>
                            <option value="CNPJ" {{ old('clie_orc_tipo_doc', $clienteOrcamento->clie_orc_tipo_doc) === 'CNPJ' ? 'selected' : '' }}>CNPJ</option>
                            <option value="RG" {{ old('clie_orc_tipo_doc', $clienteOrcamento->clie_orc_tipo_doc) === 'RG' ? 'selected' : '' }}>RG</option>
                        </select>
                    </div>

                    <div>
                        <label for="clie_orc_doc_numero" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Número do documento
                        </label>

                        <input type="text" name="clie_orc_doc_numero" id="clie_orc_doc_numero"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Informe o número do documento" maxlength="18"
                            value="{{ old('clie_orc_doc_numero',
                                $clienteOrcamento->clie_orc_tipo_doc === 'CPF'
                                    ? $clienteOrcamento->clie_orc_cpf
                                    : ($clienteOrcamento->clie_orc_tipo_doc === 'CNPJ'
                                        ? $clienteOrcamento->clie_orc_cnpj
                                        : $clienteOrcamento->clie_orc_rg)
                            ) }}" required>
                    </div>

                    <div>
                        <label for="clie_orc_celular" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Celular
                        </label>

                        <input type="text" name="clie_orc_celular" id="clie_orc_celular"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="(00) 00000-0000" maxlength="15"
                            value="{{ old('clie_orc_celular', $clienteOrcamento->clie_orc_celular) }}">
                    </div>

                    <div>
                        <label for="clie_orc_telefone" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Telefone
                        </label>

                        <input type="text" name="clie_orc_telefone" id="clie_orc_telefone"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="(00) 0000-0000" maxlength="14"
                            value="{{ old('clie_orc_telefone', $clienteOrcamento->clie_orc_telefone) }}">
                    </div>

                    <div>
                        <label for="clie_orc_cep" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            CEP
                        </label>

                        <input type="text" name="clie_orc_cep" id="clie_orc_cep"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="00000-000" maxlength="9"
                            value="{{ old('clie_orc_cep', $clienteOrcamento->clie_orc_cep) }}" required>
                    </div>

                    <div>
                        <label for="clie_orc_logradouro" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Logradouro
                        </label>

                        <input type="text" name="clie_orc_logradouro" id="clie_orc_logradouro"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Rua, Avenida, etc." maxlength="45"
                            value="{{ old('clie_orc_logradouro', $clienteOrcamento->clie_orc_logradouro) }}" required>
                    </div>

                    <div>
                        <label for="clie_orc_bairro" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Bairro
                        </label>

                        <input type="text" name="clie_orc_bairro" id="clie_orc_bairro"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Nome do bairro" maxlength="45"
                            value="{{ old('clie_orc_bairro', $clienteOrcamento->clie_orc_bairro) }}" required>
                    </div>

                    <div>
                        <label for="clie_orc_cidade" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Cidade
                        </label>

                        <input type="text" name="clie_orc_cidade" id="clie_orc_cidade"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Nome da cidade" maxlength="45"
                            value="{{ old('clie_orc_cidade', $clienteOrcamento->clie_orc_cidade) }}" required>
                    </div>

                    <div>
                        <label for="clie_orc_uf" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            UF (Estado)
                        </label>

                        <select name="clie_orc_uf" id="clie_orc_uf"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            required>

                            <option value="">Selecione o Estado</option>

                            @php
                            $estados = [
                            'AC'=>'Acre','AL'=>'Alagoas','AP'=>'Amapá','AM'=>'Amazonas',
                            'BA'=>'Bahia','CE'=>'Ceará','DF'=>'Distrito Federal','ES'=>'Espírito Santo',
                            'GO'=>'Goiás','MA'=>'Maranhão','MT'=>'Mato Grosso','MS'=>'Mato Grosso do Sul',
                            'MG'=>'Minas Gerais','PA'=>'Pará','PB'=>'Paraíba','PR'=>'Paraná',
                            'PE'=>'Pernambuco','PI'=>'Piauí','RJ'=>'Rio de Janeiro','RN'=>'Rio Grande do Norte',
                            'RS'=>'Rio Grande do Sul','RO'=>'Rondônia','RR'=>'Roraima','SC'=>'Santa Catarina',
                            'SP'=>'São Paulo','SE'=>'Sergipe','TO'=>'Tocantins'
                            ];
                            @endphp

                            @foreach ($estados as $ufAbbr => $ufNome)
                            <option value="{{ $ufAbbr }}" {{ old('clie_orc_uf', $clienteOrcamento->clie_orc_uf) == $ufAbbr ? 'selected' : '' }}>
                                {{ $ufNome }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="clie_orc_cod_interno" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Código Interno
                        </label>

                        <input type="text" name="clie_orc_cod_interno" id="clie_orc_cod_interno"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Informe o código interno" maxlength="60"
                            value="{{ old('clie_orc_cod_interno', $clienteOrcamento->clie_orc_cod_interno) }}" required>
                    </div>

                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
                
                <x-primary-button type="submit" id="btnSalvarClienteOrcamento" class="px-6">
                    <span id="textoSalvar">Atualizar cliente</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(document).ready(function() {
        $('#clie_orc_cep').mask('00000-000');
        $('#clie_orc_celular').mask('(00) 00000-0000');
        $('#clie_orc_telefone').mask('(00) 0000-0000');

        function aplicarMascaraDocumento() {
            const tipo = $('#clie_orc_tipo_doc').val();
            const campo = $('#clie_orc_doc_numero');

            campo.unmask();

            if (tipo === 'CPF') {
                campo.mask('000.000.000-00');
                campo.attr('placeholder', '000.000.000-00');
                campo.attr('maxlength', '14');
            } else if (tipo === 'CNPJ') {
                campo.mask('00.000.000/0000-00');
                campo.attr('placeholder', '00.000.000/0000-00');
                campo.attr('maxlength', '18');
            } else if (tipo === 'RG') {
                campo.mask('00.000.000-0');
                campo.attr('placeholder', '00.000.000-0');
                campo.attr('maxlength', '12');
            } else {
                campo.attr('placeholder', 'Informe o número do documento');
                campo.attr('maxlength', '18');
            }
        }

        $('#clie_orc_tipo_doc').on('change', function() {
            $('#clie_orc_doc_numero').val('');
            aplicarMascaraDocumento();
        });

        aplicarMascaraDocumento();

        $('#clie_orc_cep').on('blur', function() {
            const cep = $(this).val().replace(/\D/g, '');

            if (cep.length !== 8) {
                return;
            }

            $('#clie_orc_logradouro').val('Consultando...');
            $('#clie_orc_bairro').val('Consultando...');
            $('#clie_orc_cidade').val('Consultando...');
            $('#clie_orc_uf').val('');

            $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(dados) {
                if (dados.erro) {
                    alert('CEP não encontrado.');

                    $('#clie_orc_logradouro').val('');
                    $('#clie_orc_bairro').val('');
                    $('#clie_orc_cidade').val('');
                    $('#clie_orc_uf').val('');

                    return;
                }

                $('#clie_orc_logradouro').val(dados.logradouro);
                $('#clie_orc_bairro').val(dados.bairro);
                $('#clie_orc_cidade').val(dados.localidade);
                $('#clie_orc_uf').val(dados.uf);
            }).fail(function() {
                alert('Não foi possível consultar o CEP. Tente novamente.');

                $('#clie_orc_logradouro').val('');
                $('#clie_orc_bairro').val('');
                $('#clie_orc_cidade').val('');
                $('#clie_orc_uf').val('');
            });
        });

        $('#clienteOrcamentoForm').on('submit', function() {
            const btn = $('#btnSalvarClienteOrcamento');

            btn.prop('disabled', true);
            $('#textoSalvar').text('Atualizando...');
            btn.addClass('opacity-70 cursor-not-allowed');
        });
    });
</script>
@endpush

@endsection