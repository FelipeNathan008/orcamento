@extends('layouts.app')

@section('title', 'Editar Detalhe de Orçamento')

@section('content')

<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Edição de Detalhe de Orçamento
    </h1>
    {{-- INFORMAÇÕES DO ORÇAMENTO --}}
    @if(isset($orcamento))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">
        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Orçamento
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-600">ID</p>
                <p class="font-semibold">{{ $orcamento->id_orcamento }}</p>
            </div>

            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold">
                    {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Data</p>
                <p class="font-semibold">
                    {{ $orcamento->orc_data_inicio->format('d/m/Y') }}
                </p>
            </div>
        </div>
    </div>
    @endif



    <form action="{{ route('detalhes_orcamento.update', $detalheOrcamento->id_det) }}" method="POST">

        @csrf
        @method('PUT')

        <input type="hidden" name="orcamento_id_orcamento"
            value="{{ $detalheOrcamento->orcamento_id_orcamento }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- PRODUTO --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Produto</label>

                <div class="flex gap-3">

                    <input type="text"
                        id="produto_selecionado_nome"
                        value="{{ $detalheOrcamento->det_cod }} - {{ $detalheOrcamento->det_modelo }}"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100"
                        readonly>

                    <button type="button"
                        id="btnBuscarProduto"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        🔎 Buscar
                    </button>

                </div>

                <input type="hidden"
                    name="produto_id_produto"
                    id="produto_id_produto"
                    value="{{ $detalheOrcamento->produto_id_produto }}"
                    required>
            </div>

            {{-- CÓDIGO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Código</label>

                <input type="text"
                    name="det_cod"
                    id="det_cod"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    value="{{ $detalheOrcamento->det_cod }}"
                    readonly required>
            </div>

            {{-- CATEGORIA --}}
            <div>
                <label class="block text-sm font-medium mb-1">Categoria</label>

                <input type="text"
                    name="det_categoria"
                    id="det_categoria"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    value="{{ $detalheOrcamento->det_categoria }}"
                    readonly required>
            </div>

            {{-- MODELO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Modelo</label>

                <input type="text"
                    name="det_modelo"
                    id="det_modelo"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    value="{{ $detalheOrcamento->det_modelo }}"
                    readonly required>
            </div>

            {{-- COR --}}
            <div>
                <label class="block text-sm font-medium mb-1">Cor</label>

                <input type="text"
                    name="det_cor"
                    id="det_cor"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    value="{{ $detalheOrcamento->det_cor }}"
                    readonly required>
            </div>

            {{-- VALOR --}}
            <div>
                <label class="block text-sm font-medium mb-1">Valor Unitário</label>

                <input type="text"
                    name="det_valor_unit"
                    id="det_valor_unit"
                    data-preco-original="{{ $detalheOrcamento->produto->prod_preco }}"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    value="{{ number_format($detalheOrcamento->det_valor_unit,2,',','.') }}"
                    readonly required>
            </div>

            {{-- GENERO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Gênero</label>

                <input type="text"
                    name="det_genero"
                    id="det_genero"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    value="{{ $detalheOrcamento->det_genero }}"
                    readonly required>
            </div>

            {{-- TAMANHO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Tamanho</label>

                <select name="det_tamanho" id="det_tamanho"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    required>
                    <option value="">Selecione um Tamanho</option>
                </select>

                <p id="mensagem_acrescimo"
                    class="text-orange-600 text-sm mt-2 font-semibold hidden">

                    ⚠ Tamanhos G3 e G5 possuem acréscimo de 15%.

                </p>

            </div>

            {{-- QUANTIDADE --}}
            <div>

                <label class="block text-sm font-medium mb-1">Quantidade</label>

                <input type="number"
                    name="det_quantidade"
                    id="det_quantidade"
                    min="1"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    value="{{ $detalheOrcamento->det_quantidade }}"
                    required>

                <p id="valor_total_texto"
                    class="text-red-600 text-sm mt-2 font-semibold hidden">

                    Total: R$ 0,00

                </p>

            </div>

            {{-- CARACTERISTICAS --}}
            <div class="md:col-span-2">

                <label class="block text-sm font-medium mb-3">

                    Características

                </label>

                <div id="caracteristicas_container"
                    class="space-y-4"></div>

                <input type="hidden"
                    name="det_caract"
                    id="det_caract"
                    value="{{ $detalheOrcamento->det_caract }}">

            </div>

            {{-- OBSERVAÇÃO --}}
            <div class="md:col-span-2">

                <label class="block text-sm font-medium mb-1">
                    Observação
                </label>

                <textarea
                    name="det_observacao"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    rows="3">

                {{ $detalheOrcamento->det_observacao }}
                </textarea>

            </div>

            {{-- ANOTAÇÃO --}}
            <div class="md:col-span-2">

                <label class="block text-sm font-medium mb-1">

                    Anotação

                </label>

                <textarea
                    name="det_anotacao"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    rows="3">

                {{ $detalheOrcamento->det_anotacao }}

                </textarea>
            </div>
        </div>

        {{-- BOTÕES --}}

        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                ATUALIZAR
            </button>
        </div>

        <div class="flex justify-center mb-8">
            <a href="{{ route('detalhes_orcamento.index',
['orcamento_id'=>$detalheOrcamento->orcamento_id_orcamento]) }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>
    </form>

</div>
<div id="modalProdutos" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">

    <div class="bg-white w-4/5 max-w-5xl rounded-lg shadow-lg p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Selecionar Produto</h2>
            <button type="button" id="fecharModal" class="text-red-600 font-bold text-lg">✕</button>
        </div>
        <input type="text" id="buscarProdutoInput"
            placeholder="Digite código, nome, categoria..."
            class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4">

        <p id="avisoLimite" class="text-red-600 text-sm mb-2 hidden">
            Exibindo apenas os primeiros 20 resultados
        </p>
        <div class="overflow-y-auto max-h-96 border rounded-md">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 text-left">Código</th>
                        <th class="p-2 text-left">Nome</th>
                        <th class="p-2 text-left">Categoria</th>
                        <th class="p-2 text-left">Cor</th>
                        <th class="p-2 text-left">Preço</th>
                        <th class="p-2"></th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($produtos as $produto)

                    <tr class="border-t produto-linha"
                        data-id="{{ $produto->id_produto }}"
                        data-nome="{{ $produto->prod_nome }}"
                        data-cod="{{ $produto->prod_cod }}"
                        data-categoria="{{ $produto->prod_categoria }}"
                        data-modelo="{{ $produto->prod_modelo }}"
                        data-cor="{{ $produto->prod_cor }}"
                        data-genero="{{ $produto->prod_genero }}"
                        data-preco="{{ number_format($produto->prod_preco,2,',','.') }}"
                        data-tamanho='@json($produto->prod_tamanho)'
                        data-caract="{{ $produto->prod_caract }}">

                        <td class="p-2">{{ $produto->prod_cod }}</td>
                        <td class="p-2">{{ $produto->prod_nome }}</td>
                        <td class="p-2">{{ $produto->prod_categoria }}</td>
                        <td class="p-2">{{ $produto->prod_cor }}</td>
                        <td class="p-2">R$ {{ number_format($produto->prod_preco,2,',','.') }}</td>

                        <td class="p-2 text-right">
                            <button type="button"
                                class="selecionarProduto bg-green-600 text-white px-3 py-1 rounded">
                                Selecionar
                            </button>
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const modal = document.getElementById('modalProdutos');
        const btnBuscar = document.getElementById('btnBuscarProduto');
        const fecharModal = document.getElementById('fecharModal');

        const selectTamanho = document.getElementById('det_tamanho');
        const inputQuantidade = document.getElementById('det_quantidade');
        const inputValor = document.getElementById('det_valor_unit');

        const textoTotal = document.getElementById('valor_total_texto');
        const mensagemAcrescimo = document.getElementById('mensagem_acrescimo');

        let valorOriginalProduto = parseFloat(inputValor.dataset.precoOriginal) || 0;
        // -------------------------
        // ABRIR / FECHAR MODAL
        // -------------------------

        btnBuscar.addEventListener('click', function() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        fecharModal.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });


        // -------------------------
        // CALCULAR TOTAL
        // -------------------------

        function calcularTotal() {

            let quantidade = parseInt(inputQuantidade.value) || 0;
            const tamanho = selectTamanho.value;

            let valorFinal = valorOriginalProduto;

            if (tamanho === 'G3' || tamanho === 'G5') {
                valorFinal = valorOriginalProduto * 1.15;
                mensagemAcrescimo.classList.remove('hidden');
            } else {
                mensagemAcrescimo.classList.add('hidden');
            }

            inputValor.value = valorFinal.toFixed(2).replace('.', ',');

            const total = quantidade * valorFinal;

            if (quantidade > 0 && valorFinal > 0) {

                textoTotal.classList.remove('hidden');
                textoTotal.textContent = "Total: R$ " + total.toFixed(2).replace('.', ',');

            } else {

                textoTotal.classList.add('hidden');

            }
        }

        inputQuantidade.addEventListener('input', calcularTotal);
        selectTamanho.addEventListener('change', calcularTotal);

        // -------------------------
        // BUSCAR INPUT
        // -------------------------
        const buscarInput = document.getElementById('buscarProdutoInput');

        if (buscarInput) {

            buscarInput.addEventListener('keyup', function() {

                const termo = this.value.toLowerCase();
                const linhas = document.querySelectorAll('.produto-linha');

                let contador = 0;

                linhas.forEach(linha => {

                    const texto = linha.innerText.toLowerCase();

                    if (texto.includes(termo) && contador < 20) {

                        linha.style.display = '';
                        contador++;

                    } else {

                        linha.style.display = 'none';

                    }

                });

                const aviso = document.getElementById('avisoLimite');

                if (contador === 20) {
                    aviso.classList.remove('hidden');
                } else {
                    aviso.classList.add('hidden');
                }

            });

        }
        // -------------------------
        // SELECIONAR PRODUTO
        // -------------------------

        document.querySelectorAll('.selecionarProduto').forEach(function(botao) {

            botao.addEventListener('click', function() {

                const linha = this.closest('tr');

                const id = linha.dataset.id;
                const cod = linha.dataset.cod;
                const categoria = linha.dataset.categoria;
                const modelo = linha.dataset.modelo;
                const cor = linha.dataset.cor;
                const genero = linha.dataset.genero;
                const preco = linha.dataset.preco;
                const nome = linha.dataset.nome;

                let tamanhos = [];

                try {
                    tamanhos = JSON.parse(linha.dataset.tamanho);
                } catch (e) {
                    tamanhos = (linha.dataset.tamanho || '').split(',');
                }

                // preencher campos

                document.getElementById('produto_id_produto').value = id;
                document.getElementById('det_cod').value = cod;
                document.getElementById('det_categoria').value = categoria;
                document.getElementById('det_modelo').value = modelo;
                document.getElementById('det_cor').value = cor;
                document.getElementById('det_genero').value = genero;
                document.getElementById('det_valor_unit').value = preco;
                let precoRaw = preco.replace(/\./g, '').replace(',', '.');
                valorOriginalProduto = parseFloat(precoRaw) || 0;

                document.getElementById('produto_selecionado_nome').value =
                    cod + " - " + nome;

                // atualizar tamanhos
                selectTamanho.innerHTML =
                    '<option value="">Selecione um Tamanho</option>';

                tamanhos.forEach(function(tam) {

                    tam = tam.trim();

                    const option = document.createElement('option');
                    option.value = tam;
                    option.textContent = tam;

                    selectTamanho.appendChild(option);

                });

                // ADICIONAR ISSO
                const caract = linha.dataset.caract;
                criarGruposCaracteristicas(caract);

                modal.classList.add('hidden');
                modal.classList.remove('flex');

                calcularTotal();

            });

        });

        // -------------------------
        // Carregar Características
        // -------------------------
        const container = document.getElementById('caracteristicas_container');
        const hiddenCaract = document.getElementById('det_caract');

        function criarGruposCaracteristicas(caractString) {

            container.innerHTML = '';

            if (!caractString) return;

            const lista = caractString.split(',')
                .map(s => s.trim())
                .filter(s => s.length);

            const grupoManga = lista.filter(i => i.toLowerCase().includes('manga'));
            const grupoBolso = lista.filter(i => i.toLowerCase().includes('bolso'));

            function criarGrupo(nome, opcoes) {

                if (!opcoes.length) return;

                const div = document.createElement('div');
                div.classList.add('space-y-2');

                const titulo = document.createElement('p');
                titulo.classList.add('font-semibold');
                titulo.textContent = nome.charAt(0).toUpperCase() + nome.slice(1);

                div.appendChild(titulo);

                opcoes.forEach(function(opcao, index) {

                    const label = document.createElement('label');
                    label.classList.add('flex', 'items-center', 'space-x-2');

                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    radio.name = nome + '_grp';
                    radio.value = opcao;

                    // REQUIRED (apenas no primeiro radio do grupo)
                    if (index === 0) {
                        radio.required = true;
                    }

                    // marcar automaticamente o que já estava salvo
                    const valoresSalvos = hiddenCaract.value.split(',').map(v => v.trim());
                    if (valoresSalvos.includes(opcao)) {
                        radio.checked = true;
                    }

                    radio.addEventListener('change', atualizarHiddenCaract);

                    const span = document.createElement('span');
                    span.textContent = opcao;

                    label.appendChild(radio);
                    label.appendChild(span);

                    div.appendChild(label);

                });

                container.appendChild(div);

            }

            if (grupoManga.length) criarGrupo('manga', grupoManga);
            if (grupoBolso.length) criarGrupo('bolso', grupoBolso);

        }

        function atualizarHiddenCaract() {

            const selecionados = [];

            container.querySelectorAll('input[type="radio"]:checked')
                .forEach(r => selecionados.push(r.value));

            hiddenCaract.value = selecionados.join(', ');

        }
        // -------------------------
        // CARREGAR TAMANHO NO EDIT
        // -------------------------

        const tamanhoSalvo = "{{ $detalheOrcamento->det_tamanho }}";
        const produtoId = document.getElementById('produto_id_produto').value;

        const linhaProduto = document.querySelector('tr[data-id="' + produtoId + '"]');
        if (linhaProduto) {
            const caract = linhaProduto.dataset.caract;
            criarGruposCaracteristicas(caract);
        }
        if (linhaProduto) {

            let tamanhos = [];

            try {
                tamanhos = JSON.parse(linhaProduto.dataset.tamanho);
            } catch (e) {
                tamanhos = (linhaProduto.dataset.tamanho || '').split(',');
            }

            selectTamanho.innerHTML =
                '<option value="">Selecione um Tamanho</option>';

            tamanhos.forEach(function(tam) {

                tam = tam.trim();

                const option = document.createElement('option');

                option.value = tam;
                option.textContent = tam;

                if (tam == tamanhoSalvo) {
                    option.selected = true;
                }

                selectTamanho.appendChild(option);

            });

        }

        calcularTotal();
    });
</script>
@endpush
@endsection