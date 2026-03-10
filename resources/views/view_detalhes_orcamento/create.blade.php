@extends('layouts.app')

@section('title', 'Adicionar Novo Detalhe de Orçamento')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Cadastro de Detalhe de Orçamento
    </h1>

    {{-- ALERTAS --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

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

    <form action="{{ route('detalhes_orcamento.store') }}" method="POST" class="space-y-6">
        @csrf

        <input type="hidden" name="orcamento_id_orcamento" value="{{ $selectedOrcamentoId }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- PRODUTO --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Produto</label>

                <div class="flex gap-3">
                    <input type="text" id="produto_selecionado_nome"
                        placeholder="Nenhum produto selecionado"
                        class="block w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100"
                        readonly>

                    <button type="button" id="btnBuscarProduto"
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">
                        🔎 Buscar
                    </button>
                </div>

                <input type="hidden" name="produto_id_produto" id="produto_id_produto" required>
            </div>

            {{-- CÓDIGO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Código</label>
                <input type="text" name="det_cod" id="det_cod"
                    placeholder="Selecione um produto"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    readonly required>
            </div>

            {{-- CATEGORIA --}}
            <div>
                <label class="block text-sm font-medium mb-1">Categoria</label>
                <input type="text" name="det_categoria" id="det_categoria"
                    placeholder="Será preenchido automaticamente"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    readonly required>
            </div>

            {{-- MODELO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Modelo</label>
                <input type="text" name="det_modelo" id="det_modelo"
                    placeholder="Será preenchido automaticamente"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    readonly required>
            </div>

            {{-- COR --}}
            <div>
                <label class="block text-sm font-medium mb-1">Cor</label>
                <input type="text" name="det_cor" id="det_cor"
                    placeholder="Será preenchido automaticamente"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    readonly required>
            </div>

            {{-- VALOR UNITÁRIO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Valor Unitário</label>
                <input type="text" name="det_valor_unit" id="det_valor_unit"
                    placeholder="R$ 0,00"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    readonly required>
            </div>

            {{-- GÊNERO --}}
            <div>
                <label class="block text-sm font-medium mb-1">Gênero</label>
                <input type="text" name="det_genero" id="det_genero"
                    placeholder="Será preenchido automaticamente"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
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
                <p id="mensagem_acrescimo" class="text-orange-600 text-sm mt-2 font-semibold hidden">
                    ⚠ Tamanhos G3 e G5 possuem acréscimo de 15% no valor.
                </p>
            </div>

            {{-- QUANTIDADE --}}
            <div>
                <label class="block text-sm font-medium mb-1">Quantidade</label>
                <input type="number" name="det_quantidade" id="det_quantidade"
                    min="1"
                    placeholder="Digite a quantidade desejada"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    required>

                {{-- TOTAL DINÂMICO --}}
                <p id="valor_total_texto" class="text-red-600 text-sm mt-2 font-semibold hidden">
                    Total: R$ 0,00
                </p>
            </div>

            {{-- CARACTERÍSTICA --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-3">Características</label>
                <div id="caracteristicas_container" class="space-y-4"></div>
                <input type="hidden" name="det_caract" id="det_caract">
            </div>

            {{-- OBSERVAÇÃO --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Observação</label>
                <textarea name="det_observacao"
                    placeholder="Digite alguma observação adicional sobre o produto..."
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    rows="3"></textarea>
            </div>

            {{-- ANOTAÇÃO --}}
            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Anotação</label>
                <textarea name="det_anotacao"
                    placeholder="Digite alguma anotação interna (opcional)..."
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                    rows="3"></textarea>
            </div>

        </div>

        {{-- BOTÕES --}}

        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                SALVAR
            </button>
        </div>

        {{-- Botão Voltar unificado e movido para fora do formulário --}}
        <div class="flex justify-center mb-8">
            <a href="{{ route('detalhes_orcamento.index', ['orcamento_id' => $orcamento->id_orcamento]) }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>

    </form>
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ELEMENTOS
        const produtoIdHidden = document.getElementById('produto_id_produto');
        const nomeSelecionado = document.getElementById('produto_selecionado_nome');

        const detCod = document.getElementById('det_cod');
        const detCategoria = document.getElementById('det_categoria');
        const detModelo = document.getElementById('det_modelo');
        const detCor = document.getElementById('det_cor');
        const detGenero = document.getElementById('det_genero');
        const detValorUnit = document.getElementById('det_valor_unit');

        const selectTamanho = document.getElementById('det_tamanho');
        const inputQuantidade = document.getElementById('det_quantidade');
        const textoTotal = document.getElementById('valor_total_texto');
        const mensagemAcrescimo = document.getElementById('mensagem_acrescimo');

        const container = document.getElementById('caracteristicas_container');
        const hiddenCaract = document.getElementById('det_caract');

        let valorOriginalProduto = 0;
        // FUNÇÃO DE CÁLCULO
        function calcularTotal() {

            let quantidade = parseInt(inputQuantidade.value) || 0;

            const tamanhoSelecionado = selectTamanho.value;

            let valorFinalUnit = valorOriginalProduto;

            if (tamanhoSelecionado === 'G3' || tamanhoSelecionado === 'G5') {
                valorFinalUnit = valorOriginalProduto * 1.15;
                mensagemAcrescimo.classList.remove('hidden');
            } else {
                mensagemAcrescimo.classList.add('hidden');
            }

            // Atualiza o campo com o valor correto (sem acumular)
            if (valorFinalUnit > 0) {
                detValorUnit.value = valorFinalUnit.toFixed(2).replace('.', ',');
            }

            const total = quantidade * valorFinalUnit;

            if (quantidade > 0 && valorFinalUnit > 0) {
                textoTotal.classList.remove('hidden');
                textoTotal.textContent = 'Total: R$ ' + total.toFixed(2).replace('.', ',');
            } else {
                textoTotal.classList.add('hidden');
            }
        }

        // FUNÇÃO PARA CRIAR GRUPOS DE CARACTERÍSTICAS (radios)
        function criarGruposCaracteristicas(caractString) {
            container.innerHTML = '';
            hiddenCaract.value = '';

            if (!caractString) return;

            const lista = caractString.split(',').map(s => s.trim()).filter(s => s.length);

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

                opcoes.forEach((opcao, idx) => {
                    const label = document.createElement('label');
                    label.classList.add('flex', 'items-center', 'space-x-2');

                    const radio = document.createElement('input');
                    radio.type = 'radio';
                    // nome único por grupo
                    radio.name = nome + '_grp';
                    radio.value = opcao;
                    radio.id = nome + '_opt_' + idx;

                    radio.addEventListener('change', function() {
                        atualizarHiddenCaract();
                    });

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

        // POPULA CAMPOS A PARTIR DOS DADOS DO PRODUTO (obj)
        function populateProductData(obj) {
            produtoIdHidden.value = obj.id || '';
            nomeSelecionado.value = (obj.cod ? obj.cod + ' - ' : '') + (obj.nome || '');
            valorOriginalProduto = parseFloat((obj.preco || '0').replace(',', '.'));
            detCod.value = obj.cod || '';
            detCategoria.value = obj.categoria || '';
            detModelo.value = obj.modelo || '';
            detCor.value = obj.cor || '';
            detGenero.value = obj.genero || '';
            detValorUnit.value = obj.preco || '';

            // popula tamanhos
            selectTamanho.innerHTML = '<option value="">Selecione um Tamanho</option>';
            (obj.tamanhos || []).forEach(tam => {
                const option = document.createElement('option');
                option.value = tam;
                option.textContent = tam;
                selectTamanho.appendChild(option);
            });

            // limpa quantidade/total/aviso
            inputQuantidade.value = '';
            textoTotal.classList.add('hidden');
            mensagemAcrescimo.classList.add('hidden');

            // características
            criarGruposCaracteristicas(obj.caract || '');

            // garantir que o cálculo ocorra se já houver valores
            calcularTotal();
        }

        // REGISTRAR LISTENERS GLOBAIS DE CÁLCULO
        inputQuantidade.addEventListener('input', calcularTotal);
        detValorUnit.addEventListener('input', calcularTotal); // usar input para pegar programmatic changes rapidamente
        selectTamanho.addEventListener('change', calcularTotal);

        // ---- Modal / Selecionar produto ----
        // abrir / fechar modal (se existir)
        const modal = document.getElementById('modalProdutos');
        const btnBuscar = document.getElementById('btnBuscarProduto');
        const fecharModal = document.getElementById('fecharModal');
        const buscarInput = document.getElementById('buscarProdutoInput');

        if (btnBuscar && modal) {
            btnBuscar.addEventListener('click', () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        }
        if (fecharModal && modal) {
            fecharModal.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        }

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
                if (aviso) {
                    if (contador === 20) aviso.classList.remove('hidden');
                    else aviso.classList.add('hidden');
                }
            });
        }

        // Anexa listeners aos botões "Selecionar" do modal (garantindo que existem)
        document.querySelectorAll('.selecionarProduto').forEach(botao => {
            botao.addEventListener('click', function() {
                const linha = this.closest('tr');
                if (!linha) return;

                // montar objeto de dados do produto a partir dos data-attributes
                let tamanhos = [];
                try {
                    tamanhos = JSON.parse(linha.dataset.tamanho || '[]');
                } catch (e) {
                    // se for string separada por virgula
                    tamanhos = (linha.dataset.tamanho || '').split(',').map(s => s.trim()).filter(Boolean);
                }

                const produto = {
                    id: linha.dataset.id,
                    nome: linha.dataset.nome,
                    cod: linha.dataset.cod,
                    categoria: linha.dataset.categoria,
                    modelo: linha.dataset.modelo,
                    cor: linha.dataset.cor,
                    genero: linha.dataset.genero,
                    preco: linha.dataset.preco, // formato "12,34"
                    tamanhos: tamanhos,
                    caract: linha.dataset.caract || ''
                };

                populateProductData(produto);

                // fecha modal
                if (modal) modal.classList.add('hidden');
            });
        });

    });
</script>
@endpush

{{-- MODAL DE PRODUTOS --}}
<div id="modalProdutos" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white w-4/5 max-w-5xl rounded-lg shadow-lg p-6">

        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Selecionar Produto</h2>
            <button type="button" id="fecharModal" class="text-red-600 font-bold text-lg">✕</button>
        </div>

        <input type="text" id="buscarProdutoInput"
            placeholder="Digite código, nome, categoria..."
            class="w-full px-4 py-2 border border-gray-300 rounded-md mb-4">

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
                <tbody id="tabelaProdutos">
                    @foreach ($produtos as $produto)
                    <tr class="border-t produto-linha"
                        data-id="{{ $produto->id_produto }}"
                        data-nome="{{ $produto->prod_nome }}"
                        data-cod="{{ $produto->prod_cod }}"
                        data-categoria="{{ $produto->prod_categoria }}"
                        data-modelo="{{ $produto->prod_modelo }}"
                        data-cor="{{ $produto->prod_cor }}"
                        data-genero="{{ $produto->prod_genero }}"
                        data-caract="{{ $produto->prod_caract }}"
                        data-tamanho="{{ json_encode(is_array($produto->prod_tamanho) ? $produto->prod_tamanho : explode(',', $produto->prod_tamanho)) }}"
                        data-preco="{{ number_format($produto->prod_preco, 2, ',', '') }}">

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
@endsection