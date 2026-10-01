@extends('layouts.app')

@section('title', 'Cadastrar Nova Empresa')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header title="Cadastrar Nova Empresa" :back-url="$urlVoltar" />
        
        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="empresaForm" action="{{ route('empresa.store') }}" method="POST" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">

                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados da empresa</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Preencha as informações cadastrais da empresa.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2">
                        <label for="emp_nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Nome da Empresa
                        </label>

                        <input type="text"
                            name="emp_nome"
                            id="emp_nome"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Nome completo da empresa"
                            maxlength="85"
                            value="{{ old('emp_nome') }}"
                            required>
                    </div>

                    <div>
                        <label for="emp_cnpj" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            CNPJ
                        </label>

                        <input type="text"
                            name="emp_cnpj"
                            id="emp_cnpj"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="00.000.000/0000-00"
                            maxlength="18"
                            value="{{ old('emp_cnpj') }}"
                            required>
                    </div>

                    <div>
                        <label for="emp_cep" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            CEP
                        </label>

                        <input type="text"
                            name="emp_cep"
                            id="emp_cep"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="XXXXX-XXX"
                            maxlength="9"
                            value="{{ old('emp_cep') }}"
                            required>
                    </div>

                    <div>
                        <label for="emp_cidade" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Cidade
                        </label>

                        <input type="text"
                            name="emp_cidade"
                            id="emp_cidade"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Nome da cidade"
                            maxlength="45"
                            value="{{ old('emp_cidade') }}"
                            required>
                    </div>

                    <div>
                        <label for="emp_uf" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            UF (Estado)
                        </label>

                        <select name="emp_uf"
                            id="emp_uf"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
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

                            $selectedUf = old('emp_uf');
                            @endphp

                            @foreach ($estados as $ufAbbr => $ufNome)
                            <option value="{{ $ufAbbr }}" {{ $selectedUf == $ufAbbr ? 'selected' : '' }}>
                                {{ $ufNome }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="emp_bairro" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Bairro
                        </label>

                        <input type="text"
                            name="emp_bairro"
                            id="emp_bairro"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Nome do bairro"
                            maxlength="45"
                            value="{{ old('emp_bairro') }}"
                            required>
                    </div>

                    <div>
                        <label for="emp_logradouro" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Logradouro
                        </label>

                        <input type="text"
                            name="emp_logradouro"
                            id="emp_logradouro"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Rua, Avenida, etc."
                            maxlength="85"
                            value="{{ old('emp_logradouro') }}"
                            required>
                    </div>

                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">

                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>

                <x-primary-button type="submit" id="btnSalvarEmpresa" class="px-6">
                    <span id="textoSalvar">Salvar empresa</span>
                </x-primary-button>

            </div>
        </form>
    </div>
</div>

@push('scripts')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    const form = document.getElementById('empresaForm');
    const btnSalvar = document.getElementById('btnSalvarEmpresa');
    const textoSalvar = document.getElementById('textoSalvar');

    form.addEventListener('submit', function() {
        if (btnSalvar.disabled) {
            return false;
        }

        btnSalvar.disabled = true;
        textoSalvar.innerText = 'Salvando...';
        btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
    });

    $(document).ready(function() {
        $('#emp_cnpj').mask('AA.AAA.AAA/AAAA-AA', {
            translation: {
                'A': {
                    pattern: /[A-Za-z0-9]/
                }
            }
        });

        $('#emp_cep').mask('00000-000');

        $('#emp_cep').on('blur', function() {
            const cep = $(this).val().replace(/\D/g, '');

            if (cep.length !== 8) {
                return;
            }

            $('#emp_logradouro').val('Consultando...');
            $('#emp_bairro').val('Consultando...');
            $('#emp_cidade').val('Consultando...');

            $.getJSON('https://viacep.com.br/ws/' + cep + '/json/', function(dados) {
                if (dados.erro) {
                    alert('CEP não encontrado.');

                    $('#emp_logradouro').val('');
                    $('#emp_bairro').val('');
                    $('#emp_cidade').val('');
                    $('#emp_uf').val('');

                    return;
                }

                $('#emp_logradouro').val(dados.logradouro);
                $('#emp_bairro').val(dados.bairro);
                $('#emp_cidade').val(dados.localidade);
                $('#emp_uf').val(dados.uf);
            }).fail(function() {
                alert('Não foi possível consultar o CEP. Tente novamente.');

                $('#emp_logradouro').val('');
                $('#emp_bairro').val('');
                $('#emp_cidade').val('');
                $('#emp_uf').val('');
            });
        });
    });
</script>

@endpush
@endsection