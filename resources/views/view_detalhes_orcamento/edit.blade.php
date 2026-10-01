@extends('layouts.app')

@section('title', 'Editar Detalhe de Orçamento')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Editar Detalhe de Orçamento" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        @if(isset($orcamento))
        <div class="px-6 sm:px-8 pt-6">
            <x-info-card
                title="Informações do Orçamento"
                :name="'Orçamento #' . $orcamento->id_orcamento"
                type="Orçamento"
                :fields="[
                    [
                        'label' => 'Cód. Interno',
                        'value' => $orcamento->orc_cod_interno ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $orcamento->orc_cod_fabrica ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cliente',
                        'value' => $orcamento->clienteOrcamento->clie_orc_nome ?? 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Data de início',
                        'value' => $orcamento->orc_data_inicio ? $orcamento->orc_data_inicio->format('d/m/Y') : 'Não informado',
                    ],
                    [
                        'label' => 'Status',
                        'value' => ucfirst($orcamento->orc_status),
                    ],
                ]" />
        </div>
        @endif

        <form id="detalhesOrcamentoForm" action="{{ route('detalhes_orcamento.update', CryptHelper::encrypt($detalheOrcamento->id_det)) }}" method="POST" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            @method('PUT')
            <input type="hidden" name="orcamento_id_orcamento" value="{{ $detalheOrcamento->orcamento_id_orcamento }}">
            <input type="hidden" name="return_url" value="{{ $urlVoltar }}">

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 mt-2">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do detalhe</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Atualize as informações do produto e do detalhe.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label for="produto_selecionado_nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Produto</label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="text" id="produto_selecionado_nome" value="{{ $detalheOrcamento->det_cod }} - {{ $detalheOrcamento->det_modelo }}" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" readonly>
                            <button type="button" id="btnBuscarProduto" class="inline-flex items-center justify-center h-11 px-5 text-sm font-semibold text-white bg-[#EA792D] rounded-lg hover:bg-[#d96b25] transition whitespace-nowrap">Buscar produto</button>
                        </div>
                        <input type="hidden" name="produto_id_produto" id="produto_id_produto" value="{{ $detalheOrcamento->produto_id_produto }}" required>
                    </div>

                    <div>
                        <label for="det_nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Nome</label>
                        <input type="text" name="det_nome" id="det_nome" value="{{ $detalheOrcamento->det_nome }}" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" readonly required>
                    </div>

                    <div>
                        <label for="det_familia" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Família</label>
                        <input type="text" name="det_familia" id="det_familia" value="{{ $detalheOrcamento->det_familia }}" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" readonly required>
                    </div>

                    <div>
                        <label for="det_material" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Material</label>
                        <input type="text" name="det_material" id="det_material" value="{{ $detalheOrcamento->det_material }}" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" readonly>
                    </div>

                    <div>
                        <label for="det_cod" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Código</label>
                        <input type="text" name="det_cod" id="det_cod" value="{{ $detalheOrcamento->det_cod }}" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" readonly required>
                    </div>

                    <div>
                        <label for="det_categoria" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Categoria</label>
                        <input type="text" name="det_categoria" id="det_categoria" value="{{ $detalheOrcamento->det_categoria }}" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" readonly required>
                    </div>

                    <div>
                        <label for="det_modelo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Modelo</label>
                        <input type="text" name="det_modelo" id="det_modelo" value="{{ $detalheOrcamento->det_modelo }}" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" readonly required>
                    </div>

                    <div>
                        <label for="det_cor" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Cor</label>
                        <input type="text" name="det_cor" id="det_cor" value="{{ $detalheOrcamento->det_cor }}" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" readonly required>
                    </div>

                    <div>
                        <label for="det_valor_unit" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Valor Unitário</label>
                        <input type="text" name="det_valor_unit" id="det_valor_unit" data-preco-original="{{ $detalheOrcamento->produto->prod_preco ?? 0 }}" value="{{ 'R$ ' . number_format($detalheOrcamento->det_valor_unit, 2, ',', '.') }}" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" readonly required>
                    </div>

                    <div>
                        <label for="det_genero" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Gênero</label>
                        <input type="text" name="det_genero" id="det_genero" value="{{ $detalheOrcamento->det_genero }}" class="w-full h-11 px-3 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg outline-none" readonly required>
                    </div>

                    <div>
                        <label for="det_tamanho" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tamanho</label>
                        <select name="det_tamanho" id="det_tamanho" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                            <option value="">Selecione um tamanho</option>
                        </select>
                        <p id="mensagem_acrescimo" class="text-orange-600 text-xs mt-2 font-semibold hidden">Tamanhos G3 e G5 possuem acréscimo de 15% no valor.</p>
                    </div>

                    <div>
                        <label for="det_quantidade" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Quantidade</label>
                        <input type="number" name="det_quantidade" id="det_quantidade" min="1" placeholder="Digite a quantidade desejada" value="{{ $detalheOrcamento->det_quantidade }}" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                        <p id="valor_total_texto" class="text-orange-600 text-xs mt-2 font-semibold hidden">Total: R$ 0,00</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Características</label>
                        <div id="caracteristicas_container" class="space-y-4"></div>
                        <input type="hidden" name="det_caract" id="det_caract" value="{{ $detalheOrcamento->det_caract }}">
                    </div>

                    <div class="md:col-span-2">
                        <label for="det_observacao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Observação</label>
                        <textarea name="det_observacao" id="det_observacao" rows="3" maxlength="1000" placeholder="Digite alguma observação adicional sobre o produto" class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none">{{ old('det_observacao', $detalheOrcamento->det_observacao) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label for="det_anotacao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Anotação</label>
                        <textarea name="det_anotacao" id="det_anotacao" rows="3" maxlength="1000" placeholder="Digite alguma anotação interna sobre o produto" class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none">{{ old('det_anotacao', $detalheOrcamento->det_anotacao) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="btnAtualizarDetalhesOrcamento" class="px-6">
                    <span id="textoAtualizarDetalhes">Atualizar detalhe</span>
                </x-primary-button>
            </div>
        </form>
    </div>

</div>

<div id="modalProdutos" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-6xl max-h-[90vh] rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200 bg-gray-50">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#EA792D]">Produtos</p>
                <h2 class="text-xl font-bold text-gray-800 mt-1">Selecionar Produto</h2>
            </div>
            <button type="button" id="fecharModal" class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-200 transition">×</button>
        </div>
        <div class="p-6">
            <input type="text" id="buscarProdutoInput" placeholder="Digite código, nome, categoria, modelo ou cor..." class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 mb-4">
            <div id="carregandoProdutos" class="hidden text-center py-8 text-sm text-gray-500">Carregando produtos...</div>
            <div id="avisoProdutos" class="hidden text-center py-8 text-sm text-gray-500"></div>
            <div class="overflow-auto max-h-[55vh] border border-gray-200 rounded-xl">
                <table class="w-full text-sm">
                    <thead class="bg-gray-800 text-white sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">Código</th>
                            <th class="px-4 py-3 text-left font-semibold">Nome</th>
                            <th class="px-4 py-3 text-left font-semibold">Categoria</th>
                            <th class="px-4 py-3 text-left font-semibold">Cor</th>
                            <th class="px-4 py-3 text-left font-semibold">Preço</th>
                            <th class="px-4 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody id="tabelaProdutos" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>
            <div id="paginacaoProdutos" class="flex items-center justify-center gap-2 mt-4"></div>
        </div>
    </div>
</div>

@push('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('detalhesOrcamentoForm');
        const btnAtualizar = document.getElementById('btnAtualizarDetalhesOrcamento');
        const textoAtualizar = document.getElementById('textoAtualizarDetalhes');

        form.addEventListener('submit', function() {
            if (btnAtualizar.disabled) return false;
            btnAtualizar.disabled = true;
            textoAtualizar.textContent = 'Atualizando...';
            btnAtualizar.classList.add('opacity-70', 'cursor-not-allowed');
        });

        const produtoIdHidden = document.getElementById('produto_id_produto');
        const nomeSelecionado = document.getElementById('produto_selecionado_nome');
        const detNome = document.getElementById('det_nome');
        const detFamilia = document.getElementById('det_familia');
        const detMaterial = document.getElementById('det_material');
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

        let valorOriginalProduto = parseFloat(String(detValorUnit.dataset.precoOriginal || '0').replace(',', '.')) || 0;

        function formatarValorMonetario(valor) {
            return (Number(valor) || 0).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            });
        }

        function calcularTotal() {
            const quantidade = parseInt(inputQuantidade.value) || 0;
            const tamanhoSelecionado = selectTamanho.value;
            let valorFinalUnit = valorOriginalProduto;

            if (tamanhoSelecionado === 'G3' || tamanhoSelecionado === 'G5') {
                valorFinalUnit = valorOriginalProduto * 1.15;
                mensagemAcrescimo.classList.remove('hidden');
            } else {
                mensagemAcrescimo.classList.add('hidden');
            }

            detValorUnit.value = valorFinalUnit > 0 ? formatarValorMonetario(valorFinalUnit) : '';
            const total = quantidade * valorFinalUnit;

            if (quantidade > 0 && valorFinalUnit > 0) {
                textoTotal.classList.remove('hidden');
                textoTotal.textContent = 'Total: ' + formatarValorMonetario(total);
            } else {
                textoTotal.classList.add('hidden');
            }
        }

        function atualizarHiddenCaract() {
            const selecionados = [];
            container.querySelectorAll('select').forEach(select => {
                if (select.value) selecionados.push(select.value);
            });
            hiddenCaract.value = selecionados.join(', ');
        }

        function criarGruposCaracteristicas(caractString) {
            container.innerHTML = '';
            const lista = (caractString || '').split(',').map(s => s.trim()).filter(s => s.length);

            if (!lista.length || (lista.length === 1 && lista[0] === '-')) {
                hiddenCaract.value = '-';
                return;
            }

            const grupoManga = lista.filter(i => i.toLowerCase().includes('manga'));
            const grupoBolso = lista.filter(i => i.toLowerCase().includes('bolso'));
            const usados = new Set([...grupoManga, ...grupoBolso]);
            const grupoOutras = lista.filter(i => !usados.has(i));
            const valoresSalvos = hiddenCaract.value.split(',').map(v => v.trim());

            function criarGrupo(nome, opcoes) {
                if (!opcoes.length) return;
                const div = document.createElement('div');
                div.classList.add('space-y-2');

                const titulo = document.createElement('p');
                titulo.classList.add('text-xs', 'font-bold', 'text-gray-600', 'uppercase', 'tracking-wide');
                titulo.textContent = nome.charAt(0).toUpperCase() + nome.slice(1);
                div.appendChild(titulo);

                const select = document.createElement('select');
                select.name = nome + '_grp';
                select.required = true;
                select.classList.add('w-full', 'h-11', 'px-3', 'text-sm', 'text-gray-700', 'bg-white', 'border', 'border-gray-300', 'rounded-lg', 'outline-none', 'transition', 'focus:border-orange-500', 'focus:ring-2', 'focus:ring-orange-100');

                const optionDefault = document.createElement('option');
                optionDefault.value = '';
                optionDefault.textContent = `Selecione ${nome}`;
                select.appendChild(optionDefault);

                opcoes.forEach(opcao => {
                    const option = document.createElement('option');
                    option.value = opcao;
                    option.textContent = opcao;
                    if (valoresSalvos.includes(opcao)) option.selected = true;
                    select.appendChild(option);
                });

                select.addEventListener('change', atualizarHiddenCaract);
                div.appendChild(select);
                container.appendChild(div);
            }

            if (grupoManga.length) criarGrupo('manga', grupoManga);
            if (grupoBolso.length) criarGrupo('bolso', grupoBolso);
            if (grupoOutras.length) criarGrupo('outras características', grupoOutras);
            atualizarHiddenCaract();
        }

        function formatarPreco(valor) {
            const numero = parseFloat(String(valor).replace(',', '.'));
            return isNaN(numero) ? '0,00' : numero.toFixed(2).replace('.', ',');
        }

        function preencherTamanhos(tamanhos, tamanhoSelecionado = '') {
            selectTamanho.innerHTML = '<option value="">Selecione um tamanho</option>';
            (tamanhos || []).forEach(tamanho => {
                const option = document.createElement('option');
                option.value = tamanho;
                option.textContent = tamanho;
                if (tamanho === tamanhoSelecionado) option.selected = true;
                selectTamanho.appendChild(option);
            });
        }

        function populateProductData(obj, manterTamanho = false) {
            produtoIdHidden.value = obj.id || '';
            nomeSelecionado.value = (obj.cod ? obj.cod + ' - ' : '') + (obj.nome || '');
            valorOriginalProduto = parseFloat(String(obj.preco || '0').replace(',', '.')) || 0;
            detNome.value = obj.nome || '';
            detFamilia.value = obj.familia || '';
            detMaterial.value = obj.material || '';
            detCod.value = obj.cod || '';
            detCategoria.value = obj.categoria || '';
            detModelo.value = obj.modelo || '';
            detCor.value = obj.cor || '';
            detGenero.value = obj.genero || '';
            detValorUnit.value = formatarValorMonetario(valorOriginalProduto);

            const tamanhoAtual = manterTamanho ? "{{ $detalheOrcamento->det_tamanho }}" : '';
            preencherTamanhos(obj.tamanhos || [], tamanhoAtual);

            if (!manterTamanho) {
                inputQuantidade.value = '';
                textoTotal.classList.add('hidden');
                mensagemAcrescimo.classList.add('hidden');
            }

            criarGruposCaracteristicas(obj.caract || '');
            calcularTotal();
        }

        inputQuantidade.addEventListener('input', calcularTotal);
        selectTamanho.addEventListener('change', calcularTotal);

        const modal = document.getElementById('modalProdutos');
        const btnBuscar = document.getElementById('btnBuscarProduto');
        const fecharModal = document.getElementById('fecharModal');
        const buscarInput = document.getElementById('buscarProdutoInput');
        const tabelaProdutos = document.getElementById('tabelaProdutos');
        const paginacaoProdutos = document.getElementById('paginacaoProdutos');
        const carregandoProdutos = document.getElementById('carregandoProdutos');
        const avisoProdutos = document.getElementById('avisoProdutos');
        let paginaAtual = 1;

        function carregarProdutos(pagina = 1) {
            paginaAtual = pagina;
            tabelaProdutos.innerHTML = '';
            paginacaoProdutos.innerHTML = '';
            avisoProdutos.classList.add('hidden');
            carregandoProdutos.classList.remove('hidden');

            const busca = buscarInput.value.trim();

            fetch(`{{ route('detalhes_orcamento.buscar_produtos') }}?busca=${encodeURIComponent(busca)}&page=${pagina}`)
                .then(response => {
                    if (!response.ok) throw new Error('Erro ao buscar produtos.');
                    return response.json();
                })
                .then(data => {
                    carregandoProdutos.classList.add('hidden');

                    if (!data.produtos.length) {
                        avisoProdutos.textContent = busca ? 'Nenhum produto encontrado para a busca informada.' : 'Nenhum produto cadastrado.';
                        avisoProdutos.classList.remove('hidden');
                        return;
                    }

                    data.produtos.forEach(produto => {
                        const linha = document.createElement('tr');
                        linha.className = 'border-t border-gray-100 hover:bg-orange-50/40';
                        linha.innerHTML = `<td class="px-4 py-3">${produto.cod ?? ''}</td><td class="px-4 py-3">${produto.nome ?? ''}</td><td class="px-4 py-3">${produto.categoria ?? ''}</td><td class="px-4 py-3">${produto.cor ?? ''}</td><td class="px-4 py-3">R$ ${formatarPreco(produto.preco)}</td><td class="px-4 py-3 text-right"><button type="button" class="selecionarProduto inline-flex items-center px-3 py-1.5 rounded-lg bg-[#EA792D] text-white text-xs font-semibold hover:bg-[#d96b25] transition">Selecionar</button></td>`;

                        linha.querySelector('.selecionarProduto').addEventListener('click', function() {
                            populateProductData(produto);
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        });

                        tabelaProdutos.appendChild(linha);
                    });

                    criarPaginacao(data.current_page, data.last_page);
                })
                .catch(() => {
                    carregandoProdutos.classList.add('hidden');
                    avisoProdutos.textContent = 'Não foi possível carregar os produtos.';
                    avisoProdutos.classList.remove('hidden');
                });
        }

        function criarPaginacao(atual, ultima) {
            paginacaoProdutos.innerHTML = '';
            if (ultima <= 1) return;

            const anterior = document.createElement('button');
            anterior.type = 'button';
            anterior.textContent = 'Anterior';
            anterior.disabled = atual === 1;
            anterior.className = 'px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-medium text-gray-700 disabled:opacity-40';
            anterior.addEventListener('click', () => {
                if (atual > 1) carregarProdutos(atual - 1);
            });
            paginacaoProdutos.appendChild(anterior);

            const info = document.createElement('span');
            info.className = 'px-3 py-1.5 text-xs text-gray-500';
            info.textContent = `Página ${atual} de ${ultima}`;
            paginacaoProdutos.appendChild(info);

            const proxima = document.createElement('button');
            proxima.type = 'button';
            proxima.textContent = 'Próxima';
            proxima.disabled = atual === ultima;
            proxima.className = 'px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-medium text-gray-700 disabled:opacity-40';
            proxima.addEventListener('click', () => {
                if (atual < ultima) carregarProdutos(atual + 1);
            });
            paginacaoProdutos.appendChild(proxima);
        }

        function carregarProdutoAtual() {
            const produtoId = produtoIdHidden.value;

            if (!produtoId) {
                calcularTotal();
                return;
            }

            fetch(`{{ route('detalhes_orcamento.buscar_produtos') }}?produto_id=${encodeURIComponent(produtoId)}`)
                .then(response => {
                    if (!response.ok) throw new Error('Erro ao carregar produto.');
                    return response.json();
                })
                .then(data => {
                    if (data.produtos.length) {
                        populateProductData(data.produtos[0], true);
                    } else {
                        calcularTotal();
                    }
                })
                .catch(() => calcularTotal());
        }

        btnBuscar.addEventListener('click', function() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            buscarInput.value = '';
            carregarProdutos(1);
            setTimeout(() => buscarInput.focus(), 100);
        });

        fecharModal.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        let timeoutBusca;
        buscarInput.addEventListener('input', function() {
            clearTimeout(timeoutBusca);
            timeoutBusca = setTimeout(() => carregarProdutos(1), 300);
        });

        carregarProdutoAtual();
    });
</script>

@endpush
@endsection