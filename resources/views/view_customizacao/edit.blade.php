@extends('layouts.app')

@section('title', 'Editar Customização')

@section('content')

<div class="max-w-6xl mx-auto bg-gray-100 p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Editar Customização
    </h1>

    {{-- ALERTAS --}}
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-6">
        <strong>Opa!</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- CARD DETALHE ORÇAMENTO --}}
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">
        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Detalhe do Orçamento
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

            <div>
                <p class="text-gray-600">Orçamento</p>
                <p class="font-semibold">
                    {{ $detalhe->orcamento->id_orcamento ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold">
                    {{ $detalhe->orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Produto</p>
                <p class="font-semibold">
                    {{ $detalhe->produto->prod_cod }} - {{ $detalhe->produto->prod_nome }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Categoria</p>
                <p class="font-semibold">
                    {{ $detalhe->produto->prod_categoria }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Cor / Tamanho</p>
                <p class="font-semibold">
                    {{ $detalhe->produto->prod_cor }} - {{ $detalhe->det_tamanho }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Características</p>
                <p class="font-semibold">
                    {{ $detalhe->det_caract }}
                </p>
            </div>

        </div>
    </div>


    {{-- FORM EDIT --}}
    <form action="{{ route('customizacao.update', $customizacao->id_customizacao) }}"
        method="POST"
        enctype="multipart/form-data"
        id="customizacaoForm"
        class="space-y-6">

        @csrf
        @method('PUT')

        <input type="hidden"
            name="detalhes_orcamento_id_det"
            value="{{ $detalhe->id_det }}">


        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- COLUNA 1 --}}
            <div>

                {{-- TIPO --}}
                <div>
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Tipo
                    </label>

                    <select name="cust_tipo"
                        id="cust_tipo"
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                        <option value="">Selecione</option>

                        @foreach($precos->unique('preco_tipo') as $tipo)

                        <option value="{{ $tipo->preco_tipo }}"
                            {{ old('cust_tipo',$customizacao->cust_tipo) == $tipo->preco_tipo ? 'selected' : '' }}>

                            {{ $tipo->preco_tipo }}

                        </option>

                        @endforeach

                    </select>
                </div>


                {{-- LOCAL --}}
                <div class="mt-6">

                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Local
                    </label>

                    <select name="cust_local"
                        id="cust_local"
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                        <option value="">Selecione</option>

                        <option value="Ombro"
                            {{ old('cust_local',$customizacao->cust_local) == 'Ombro' ? 'selected' : '' }}>
                            Ombro
                        </option>

                        <option value="Frente"
                            {{ old('cust_local',$customizacao->cust_local) == 'Frente' ? 'selected' : '' }}>
                            Frente
                        </option>

                        <option value="Costa"
                            {{ old('cust_local',$customizacao->cust_local) == 'Costa' ? 'selected' : '' }}>
                            Costa
                        </option>

                    </select>

                </div>

            </div>


            {{-- COLUNA 2 --}}
            <div>

                {{-- POSIÇÃO --}}
                <div>

                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Posição
                    </label>

                    <select name="cust_posicao"
                        id="cust_posicao"
                        class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                        <option value="">Selecione</option>

                        <option value="Direito"
                            {{ old('cust_posicao',$customizacao->cust_posicao) == 'Direito' ? 'selected' : '' }}>
                            Direito
                        </option>

                        <option value="Esquerdo"
                            {{ old('cust_posicao',$customizacao->cust_posicao) == 'Esquerdo' ? 'selected' : '' }}>
                            Esquerdo
                        </option>

                        <option value="Centro"
                            {{ old('cust_posicao',$customizacao->cust_posicao) == 'Centro' ? 'selected' : '' }}>
                            Centro
                        </option>

                    </select>

                </div>

                {{-- Tamanho --}}
                <div class="mt-6">

                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Tamanho
                    </label>

                    <select id="cust_tamanho_select_part"
                        class="block w-full px-4 py-2 h-10 bg-white border border-gray-300 rounded-md">
                        <option value="">Selecione...</option>
                    </select>

                    {{-- Medidas --}}
                    <div id="medidas_container" class="hidden mt-2">
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">
                            Medidas
                        </label>
                        <div class="flex space-x-4">

                            <input type="number"
                                step="0.1"
                                name="cust_largura"
                                id="cust_tamanho_numeric_part_x"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md"
                                placeholder="Largura">
                            <input type="number"
                                step="0.1"
                                name="cust_altura"
                                id="cust_tamanho_numeric_part_y"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md"
                                placeholder="Altura">
                        </div>
                    </div>
                    <input type="hidden"
                        name="cust_tamanho"
                        id="cust_tamanho_final_value"
                        value="{{ old('cust_tamanho',$customizacao->cust_tamanho) }}">
                </div>
            </div>
        </div>


        {{-- FORMATAÇÃO --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

            <div>

                <label class="block text-sm font-medium text-custom-dark-text mb-1">
                    Formatação
                </label>

                <select name="cust_formatacao"
                    id="cust_formatacao"
                    class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">

                    <option value="">Selecione</option>

                    <option value="Imagem"
                        {{ old('cust_formatacao',$customizacao->cust_formatacao) == 'Imagem' ? 'selected' : '' }}>
                        Imagem
                    </option>

                    <option value="Escrita"
                        {{ old('cust_formatacao',$customizacao->cust_formatacao) == 'Escrita' ? 'selected' : '' }}>
                        Escrita
                    </option>

                </select>

            </div>


            {{-- VALOR --}}
            <div>

                <label class="block text-sm font-medium text-custom-dark-text mb-1">
                    Valor
                </label>

                <input type="text"
                    name="cust_valor"
                    id="cust_valor"
                    class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md"
                    value="{{ old('cust_valor',$customizacao->cust_valor) }}"
                    readonly>

            </div>

        </div>


        {{-- DESCRIÇÃO --}}
        <div class="mt-6">

            <label class="block text-sm font-medium text-custom-dark-text mb-1">
                Descrição
            </label>

            <textarea name="cust_descricao"
                rows="3"
                class="block w-full px-4 py-2 bg-white border border-gray-300 rounded-md">{{ old('cust_descricao',$customizacao->cust_descricao) }}</textarea>

        </div>


        {{-- IMAGEM --}}
        <div class="mt-6">

            <label class="block text-sm font-medium text-custom-dark-text mb-2">
                Imagem da Customização
            </label>

            @if($customizacao->cust_imagem)
            <img
                src="data:image/jpeg;base64,{{ base64_encode($customizacao->cust_imagem) }}"
                class="w-16 h-16 object-cover rounded shadow">
            @else
            <span class="text-gray-500">Sem imagem</span>

            @endif

            <input type="file"
                name="cust_imagem"
                class="block w-full text-sm bg-white border border-gray-300 rounded-md">

        </div>


        <!-- Botão de Envio -->
        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                ATUALIZAR
            </button>
        </div>
        <div class="flex justify-center">
            <a href="{{ route('customizacao.index', ['id_det' => $detalhe->id_det]) }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>
    </form>

</div>
<div id="detalhe-data"
    data-prod-cod="{{ $detalhe->produto->prod_cod }}"
    data-prod-categoria="{{ $detalhe->produto->prod_categoria }}"
    data-prod-cor="{{ $detalhe->produto->prod_cor }}"
    data-tamanho="{{ $detalhe->det_tamanho }}"
    data-caract="{{ $detalhe->det_caract }}">
</div>

<div id="precos-data"
    data-precos='@json($precos)'>
</div>

<div id="customizacao-data"
    data-tipo="{{ $customizacao->cust_tipo }}"
    data-local="{{ $customizacao->cust_local }}"
    data-posicao="{{ $customizacao->cust_posicao }}"
    data-tamanho="{{ $customizacao->cust_tamanho }}"
    data-formatacao="{{ $customizacao->cust_formatacao }}">
</div>


@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(document).ready(function() {

        const $custTipo = $('#cust_tipo');
        const $custLocal = $('#cust_local');
        const $custPosicao = $('#cust_posicao');
        const $custTamanhoSelect = $('#cust_tamanho_select_part');
        const $custFormatacao = $('#cust_formatacao');
        const $custDescricao = $('textarea[name="cust_descricao"]');
        const $custImagem = $('input[name="cust_imagem"]');

        const $medidasContainer = $('#medidas_container');
        const $custTamanhoX = $('#cust_tamanho_numeric_part_x');
        const $custTamanhoY = $('#cust_tamanho_numeric_part_y');

        const precosData = JSON.parse(document.getElementById('precos-data').dataset.precos);
        const customizacaoData = document.getElementById('customizacao-data').dataset;
        const customizacaoData = document.getElementById('customizacao-data').dataset;

        // preencher campos existentes
        $custTipo.val(customizacaoData.tipo);
        $custLocal.val(customizacaoData.local);
        $custPosicao.val(customizacaoData.posicao);
        $custFormatacao.val(customizacaoData.formatacao);

        // ativar campos porque já existem dados
        $custTipo.prop('disabled', false);
        $custLocal.prop('disabled', false);
        $custPosicao.prop('disabled', false);
        $custTamanhoSelect.prop('disabled', false);
        $custFormatacao.prop('disabled', false);
        $custDescricao.prop('disabled', false);
        $custImagem.prop('disabled', false);

        // carregar tamanhos disponíveis
        const tamanhosUnicos = precosData
            .filter(preco => preco.preco_tipo === customizacaoData.tipo)
            .map(preco => preco.preco_tamanho)
            .filter((v, i, self) => self.indexOf(v) === i);

        tamanhosUnicos.forEach(tamanho => {
            $custTamanhoSelect.append(`<option value="${tamanho}">${tamanho}</option>`);
        });

        // selecionar tamanho existente
        let tamanhoBase = customizacaoData.tamanho.split(':')[0];

        $custTamanhoSelect.val(tamanhoBase);

        // se tiver medidas
        if (customizacaoData.tamanho.includes(':')) {

            let medidas = customizacaoData.tamanho.split(':')[1]
                .replace('cm', '')
                .split('x');

            $medidasContainer.removeClass('hidden');

            $custTamanhoX.val(medidas[0].replace(',', '.'));
            $custTamanhoY.val(medidas[1].replace(',', '.'));

        }

        // recalcular valor
        updateCustomizacaoValor();

    });
</script>
@endsection