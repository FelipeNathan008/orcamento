@extends('layouts.app_financeiro')

@section('title', 'Cadastrar Detalhe de Forma de Pagamento')

@section('content')
<div class="max-w-4xl mx-auto p-8 mt-10 mb-10 font-poppins">

    {{-- Título --}}
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Cadastro de Detalhe da Forma de Pagamento
    </h1>

    {{-- Mensagens de Erro do Laravel --}}
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
        <ul class="mt-1 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('detalhes_forma_pag.store') }}" method="POST" id="detForm" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Campo Forma de Pagamento --}}
            <div class="md:col-span-2">
                <label for="id_forma_pag" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Forma de Pagamento
                </label>
                {{-- MOSTRAR O VALOR TOTAL AO LADO DO LABEL --}}
                @php
                $formaSelecionada = $formas->where('id_forma_pag', request('id_forma_pag'))->first();
                @endphp
                @if($formaSelecionada)
                <span class="text-blue-700 font-semibold">
                    Orçamento Total: R$ {{ number_format($formaSelecionada->financeiro->fin_valor_total, 2, ',', '.') }}<br>
                    Total Débito: R$ {{ number_format($formaSelecionada->forma_valor, 2, ',', '.') }}
                </span>
                @endif
                {{-- INPUT HIDDEN PARA ENVIAR O VALOR --}}
                <input type="hidden" name="id_forma_pag" value="{{ request('id_forma_pag') }}">

                <select id="id_forma_pag_disabled"
                    class="block w-full px-4 py-2 bg-gray-200 text-gray-700 rounded-md shadow-sm outline-none 
               cursor-not-allowed border border-gray-300"
                    disabled>
                    @foreach ($formas->where('id_forma_pag', request('id_forma_pag')) as $forma)
                    <option>
                        {{ $forma->financeiro->fin_nome_cliente }} -
                        Total Débito: R$ {{ number_format($forma->forma_valor, 2, ',', '.') }} -
                        {{ $forma->tipoPagamento->tipo_plano_fin }} -
                        {{ $forma->forma_qtd_parcela }}x | {{ $forma->forma_prazo }}
                    </option>
                    @endforeach
                </select>

                <input type="hidden" id="total_debito" value="{{ $formaSelecionada->forma_valor }}">
                <input type="hidden" id="qtd_parcela" value="{{ $formaSelecionada->forma_qtd_parcela }}">

                @error('id_forma_pag')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Campo Data de Vencimento (fica à esquerda) --}}
            <div>
                <label for="det_forma_data_venc" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Data de Vencimento
                </label>
                <input type="date" name="det_forma_data_venc" id="det_forma_data_venc"
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md outline-none 
                   focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border border-gray-300"
                    value="{{ old('det_forma_data_venc') }}"
                    required>
                @error('det_forma_data_venc')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo Valor da Parcela (fica à direita) --}}
            <div>
                <label for="det_forma_valor_parcela" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Valor da Parcela
                </label>

                <!-- ÚNICO CAMPO, SOMENTE LEITURA E ENVIADO NO FORM -->
                <input type="text"
                    name="det_forma_valor_parcela"
                    id="det_forma_valor_parcela"
                    class="block w-full px-4 py-2 bg-gray-100 text-gray-900 rounded-md 
                  outline-none border border-gray-300 cursor-not-allowed"
                    readonly
                    required>

                @error('det_forma_valor_parcela')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            {{-- Lista de Parcelas Geradas --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-custom-dark-text mb-1">
                    Parcelas
                </label>

                <div id="listaParcelas" class="space-y-2 bg-gray-100 p-4 rounded-md border border-gray-300">
                    <!-- As parcelas serão inseridas aqui pelo JS -->
                </div>
            </div>

            <!-- CONTAINER SOMENTE PARA PARCELAS -->
            <div id="parcelasHidden"></div>
        </div>


        {{-- Botão Salvar --}}
        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium 
                           rounded-md text-white bg-button-save-bg hover:bg-button-save-hover 
                           focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                SALVAR
            </button>
        </div>
    </form>
</div>

{{-- Botão Voltar --}}
<div class="flex justify-center mb-8">
    <a href="{{ route('detalhes_forma_pag.index') }}"
        class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm 
                   text-base font-medium rounded-md text-custom-dark-text bg-gray-300 
                   hover:bg-gray-400 transition">
        VOLTAR PARA A LISTA
    </a>
</div>

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(document).ready(function() {
        $('#det_forma_valor_parcela').mask('000.000.000,00', {
            reverse: true
        });
    });

    $(document).ready(function() {

        // Máscara
        $('#det_forma_valor_parcela').mask('000.000.000,00', {
            reverse: true
        });

        // Pegar valores
        const totalDebito = parseFloat($('#total_debito').val());
        const qtdParcelas = parseInt($('#qtd_parcela').val());

        if (!isNaN(totalDebito) && !isNaN(qtdParcelas) && qtdParcelas > 0) {

            // Cálculo
            let valorParcela = totalDebito / qtdParcelas;

            // Arredondar para 2 casas decimais
            valorParcela = parseFloat(valorParcela.toFixed(2));

            // Converter para formato brasileiro
            let valorFormatado = valorParcela.toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });

            // Inserir no input
            $('#det_forma_valor_parcela').val(valorFormatado);
        }
    });

    $(document).ready(function() {

        const totalDebito = parseFloat($('#total_debito').val());
        const qtdParcelas = parseInt($('#qtd_parcela').val());

        // Valor da parcela
        let valorParcela = totalDebito / qtdParcelas;
        valorParcela = parseFloat(valorParcela.toFixed(2));


        // Função que SOMA 30 dias corretamente
        function add30dias(data) {
            let novaData = new Date(data);
            novaData.setDate(novaData.getDate() + 30);
            return novaData;
        }


        // Função para gerar parcelas
        function gerarParcelas(dataInicialString) {

            // LIMPA TUDO ANTES (garantia total)
            $('#listaParcelas').html('');
            $('#parcelasHidden').html('');

            let partes = dataInicialString.split('-');
            let data = new Date(
                parseInt(partes[0]),
                parseInt(partes[1]) - 1,
                parseInt(partes[2])
            );

            for (let i = 1; i <= qtdParcelas; i++) {

                let dia = String(data.getDate()).padStart(2, '0');
                let mes = String(data.getMonth() + 1).padStart(2, '0');
                let ano = data.getFullYear();

                let dataFormatada = `${dia}/${mes}/${ano}`;

                let valorBR = valorParcela.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                $('#listaParcelas').append(`
            <div class="p-2 bg-white rounded border border-gray-300">
                Parcela ${i}: <strong>${dataFormatada}</strong> — R$ ${valorBR}
            </div>
        `);

                $('#parcelasHidden').append(`
            <input type="hidden" name="datas_parcelas[]" value="${ano}-${mes}-${dia}">
            <input type="hidden" name="valores_parcelas[]" value="${valorParcela}">
        `);

                data = add30dias(data);
            }
        }



        // Quando selecionar a data
        $('#det_forma_data_venc').on('change', function() {
            const data = $(this).val();
            if (data) gerarParcelas(data);
        });

    });
</script>
@endpush
@endsection