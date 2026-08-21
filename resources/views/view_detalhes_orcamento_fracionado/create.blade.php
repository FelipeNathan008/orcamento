@extends('layouts.app_financeiro')

@section('title', 'Adicionar Itens ao Orçamento Fracionado')

@section('content')

{{-- CABEÇALHO --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
    <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree">
        Adicionar Itens ao Fracionado #{{ $orcamentoFracionado->orc_fracao }}
    </h1>
    <a href="{{ route('detalhes_orcamento_fracionado.index', $orcamentoFracionado->id_orcamento_fracionado) }}"
        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition">
        VOLTAR
    </a>
</div>

{{-- INFORMAÇÕES DO ORÇAMENTO --}}
<div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">
    <h2 class="text-lg font-bold text-orange-700 mb-4">
        Informações do Orçamento Principal
    </h2>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
        <div>
            <p class="text-gray-600">Cliente</p>
            <p class="font-semibold text-gray-900">
                {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/D' }}
            </p>
        </div>
        <div>
            <p class="text-gray-600">Código Fábrica</p>
            <p class="font-semibold text-gray-900">
                {{ $orcamento->orc_cod_fabrica }}
            </p>
        </div>
        <div>
            <p class="text-gray-600">Código Interno</p>
            <p class="font-semibold text-gray-900">
                {{ $orcamento->orc_cod_interno }}
            </p>
        </div>
        <div>
            <p class="text-gray-600">Fração</p>
            <p class="font-semibold text-gray-900">
                #{{ $orcamentoFracionado->orc_fracao }}
            </p>
        </div>
    </div>
</div>

<x-alert-flash />

@if ($errors->any())
<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
    <p class="text-red-700 font-semibold mb-2">Corrija os itens abaixo:</p>
    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<p class="text-sm text-gray-600 mb-4">
    Selecione abaixo os itens do orçamento original que farão parte deste fracionado.
    É obrigatório informar a quantidade de cada item selecionado — nunca é possível
    incluir mais do que a quantidade disponível. Itens já totalmente alocados em outros
    fracionados não aparecem nesta lista.
    As customizações de cada item selecionado serão copiadas automaticamente.
</p>

{{-- FILTROS --}}
<form method="GET" action="{{ route('detalhes_orcamento_fracionado.create', $orcamentoFracionado->id_orcamento_fracionado) }}" class="mb-6">
    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Produto
                </label>
                <input
                    type="text"
                    name="produto"
                    value="{{ request('produto') }}"
                    placeholder="Digite o nome..."
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Categoria
                </label>
                <input
                    type="text"
                    name="categoria"
                    value="{{ request('categoria') }}"
                    placeholder="Digite a categoria..."
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Código Ref.
                </label>
                <input
                    type="text"
                    name="cod_ref"
                    value="{{ request('cod_ref') }}"
                    placeholder="Digite o código..."
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Família
                </label>
                <select
                    name="familia"
                    class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
                    <option value="">Todas</option>
                    @foreach($familias as $familia)
                    <option
                        value="{{ $familia }}"
                        {{ request('familia') == $familia ? 'selected' : '' }}>
                        {{ $familia }}
                    </option>
                    @endforeach
                </select>
            </div>
            {{-- Buscar --}}
            <div class="flex items-end">
                <button
                    type="submit"
                    class="w-full h-10 text-white rounded-md"
                    style="background-color:#EA792D;">
                    Buscar
                </button>
            </div>
            {{-- Limpar --}}
            <div class="flex items-end">
                <a
                    href="{{ route('detalhes_orcamento_fracionado.create', $orcamentoFracionado->id_orcamento_fracionado) }}"
                    class="w-full h-10 bg-gray-300 rounded-md text-gray-800 flex items-center justify-center hover:bg-gray-400 transition">
                    Limpar
                </a>
            </div>
        </div>
    </div>
</form>

@if ($detalhesOrcamento->isEmpty())

@if(request('produto') || request('categoria') || request('cod_ref') || request('familia'))
<div class="text-center py-8">
    <p class="text-gray-600">
        Nenhum item encontrado para os filtros informados.
    </p>
    <a href="{{ route('detalhes_orcamento_fracionado.create', $orcamentoFracionado->id_orcamento_fracionado) }}"
        class="inline-block mt-3 text-orange-600 hover:text-orange-700 font-medium">
        Limpar filtros
    </a>
</div>
@else
<p class="text-gray-600 text-center py-8">
    Não há itens disponíveis para incluir neste fracionado — todos já foram
    totalmente alocados ou o orçamento original não possui detalhes cadastrados.
</p>
@endif

@else

<form action="{{ route('detalhes_orcamento_fracionado.store', $orcamentoFracionado->id_orcamento_fracionado) }}"
    method="POST" id="formAdicionarItens">
    @csrf

    <div class="w-full rounded-lg shadow-table-shadow-image overflow-x-auto mb-4">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase w-10">
                        <input type="checkbox" id="selecionarTodos" class="rounded">
                    </th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Produto</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Cód.</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Categoria</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Tam.</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-white uppercase">Qtd. Total</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-white uppercase">Já Alocado</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-white uppercase">Disponível</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-white uppercase">Valor Unit.</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase">Qtd. a Incluir</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase">Customizações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($detalhesOrcamento as $detalhe)
                @php
                $qtdAlocada = (int) ($detalhe->qtd_alocada ?? 0);
                $restante = $detalhe->det_quantidade - $qtdAlocada;

                $customizacoesJson = $detalhe->customizacoes->map(function ($c) {
                return [
                'tipo' => $c->cust_tipo,
                'local' => $c->cust_local,
                'posicao' => $c->cust_posicao,
                'tamanho' => $c->cust_tamanho,
                'formatacao' => $c->cust_formatacao,
                'descricao' => $c->cust_descricao,
                'valor' => $c->cust_valor,
                ];
                });

                $checkedOld = collect(old('itens', []))->contains($detalhe->id_det);
                $qtdOld = old("quantidades.{$detalhe->id_det}");
                $erroQtd = $errors->first("quantidades.{$detalhe->id_det}");
                @endphp
                <tr class="hover:bg-gray-50 transition {{ $qtdAlocada > 0 ? 'bg-yellow-50' : '' }}">
                    <td class="px-4 py-4 text-center">
                        <input type="checkbox"
                            name="itens[]"
                            value="{{ $detalhe->id_det }}"
                            class="rounded item-checkbox"
                            {{ $checkedOld ? 'checked' : '' }}>
                    </td>
                    <td class="px-4 py-4 text-sm">
                        {{ $detalhe->produto->prod_nome ?? 'N/A' }}
                        @if($qtdAlocada > 0)
                        <span class="ml-1 inline-block px-2 py-0.5 text-xs rounded-full bg-yellow-200 text-yellow-800">
                            parcialmente alocado
                        </span>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-sm">{{ $detalhe->det_cod }}</td>
                    <td class="px-4 py-4 text-sm">{{ $detalhe->det_categoria }}</td>
                    <td class="px-4 py-4 text-sm">{{ $detalhe->det_tamanho ?? 'N/A' }}</td>
                    <td class="px-4 py-4 text-sm text-right">{{ $detalhe->det_quantidade }}</td>
                    <td class="px-4 py-4 text-sm text-right {{ $qtdAlocada > 0 ? 'text-yellow-700 font-semibold' : 'text-gray-400' }}">
                        {{ $qtdAlocada }}
                    </td>
                    <td class="px-4 py-4 text-sm text-right font-semibold text-green-700">
                        {{ $restante }}
                    </td>
                    <td class="px-4 py-4 text-sm text-right">
                        R$ {{ number_format($detalhe->det_valor_unit, 2, ',', '.') }}
                    </td>
                    <td class="px-4 py-4 text-center">
                        <input type="number"
                            name="quantidades[{{ $detalhe->id_det }}]"
                            min="1"
                            max="{{ $restante }}"
                            data-max="{{ $restante }}"
                            value="{{ $checkedOld ? $qtdOld : '' }}"
                            placeholder="Máx: {{ $restante }}"
                            class="w-24 px-2 py-1 text-sm border rounded-md text-center qtd-input {{ $erroQtd ? 'border-red-500' : 'border-gray-300' }}"
                            {{ $checkedOld ? '' : 'disabled' }}>
                        @if($erroQtd)
                        <p class="text-red-600 text-xs mt-1">{{ $erroQtd }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-4 text-center text-sm">
                        @if($detalhe->customizacoes->count() > 0)
                        <button type="button"
                            class="btn-ver-customizacoes inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 transition"
                            data-produto="{{ $detalhe->produto->prod_nome ?? 'Item' }}"
                            data-customizacoes="{{ $customizacoesJson->toJson() }}">
                            {{ $detalhe->customizacoes->count() }} customização(ões)
                        </button>
                        @else
                        <span class="text-gray-400 text-xs">nenhuma</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-6">
        <x-pagination-compact :paginator="$detalhesOrcamento" />
    </div>

    <div class="flex justify-center gap-3">
        <button type="submit" id="btnSalvarItens"
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white hover:brightness-90 transition"
            style="background-color:#EA792D;">
            Adicionar Itens Selecionados
        </button>
        <a href="{{ route('detalhes_orcamento_fracionado.index', $orcamentoFracionado->id_orcamento_fracionado) }}"
            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition">
            Cancelar
        </a>
    </div>
</form>

@endif

{{-- MODAL DE CUSTOMIZAÇÕES --}}
<div id="modalCustomizacoes" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg p-6 max-h-[85vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                Customizações — <span id="modalCustomizacoesProduto"></span>
            </h2>
            <button type="button" id="fecharModalCustomizacoes" class="text-red-600 font-bold text-lg leading-none">
                &times;
            </button>
        </div>

        <div id="modalCustomizacoesConteudo" class="space-y-3"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selecionarTodos = document.getElementById('selecionarTodos');
        const checkboxes = document.querySelectorAll('.item-checkbox');
        const form = document.getElementById('formAdicionarItens');
        const btnSalvar = document.getElementById('btnSalvarItens');

        function limparErroVisual(input) {
            input.classList.remove('border-red-500');
            input.classList.add('border-gray-300');
        }

        function toggleQtdInput(checkbox) {
            const linha = checkbox.closest('tr');
            const qtdInput = linha.querySelector('.qtd-input');

            qtdInput.disabled = !checkbox.checked;

            // Sempre que (des)marcar, a quantidade volta vazia -> obriga
            // o usuário a escolher explicitamente um valor.
            qtdInput.value = '';
            limparErroVisual(qtdInput);
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                toggleQtdInput(this);
            });
        });

        if (selecionarTodos) {
            selecionarTodos.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                    toggleQtdInput(checkbox);
                });
            });
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                const checkboxesMarcados = Array.from(checkboxes).filter(c => c.checked);

                if (checkboxesMarcados.length === 0) {
                    e.preventDefault();
                    alert('Selecione ao menos um item para adicionar ao fracionado.');
                    return false;
                }

                let temErro = false;
                let primeiroCampoComErro = null;

                checkboxesMarcados.forEach(checkbox => {
                    const linha = checkbox.closest('tr');
                    const qtdInput = linha.querySelector('.qtd-input');
                    const max = parseInt(qtdInput.dataset.max, 10);
                    const valor = qtdInput.value.trim();
                    const valorNumerico = parseInt(valor, 10);

                    limparErroVisual(qtdInput);

                    const invalido =
                        valor === '' ||
                        isNaN(valorNumerico) ||
                        valorNumerico < 1 ||
                        valorNumerico > max;

                    if (invalido) {
                        temErro = true;
                        qtdInput.classList.remove('border-gray-300');
                        qtdInput.classList.add('border-red-500');

                        if (!primeiroCampoComErro) {
                            primeiroCampoComErro = qtdInput;
                        }
                    }
                });

                if (temErro) {
                    e.preventDefault();
                    alert('Informe uma quantidade válida (maior que zero e dentro do disponível) para todos os itens selecionados.');
                    if (primeiroCampoComErro) {
                        primeiroCampoComErro.focus();
                    }
                    return false;
                }

                btnSalvar.disabled = true;
                btnSalvar.innerText = 'SALVANDO...';
                btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
            });
        }

        // ---- Modal de customizações ----
        const modalCustom = document.getElementById('modalCustomizacoes');
        const modalCustomProduto = document.getElementById('modalCustomizacoesProduto');
        const modalCustomConteudo = document.getElementById('modalCustomizacoesConteudo');
        const fecharModalCustom = document.getElementById('fecharModalCustomizacoes');

        document.querySelectorAll('.btn-ver-customizacoes').forEach(botao => {
            botao.addEventListener('click', function() {
                const produto = this.dataset.produto || 'Item';
                let customizacoes = [];

                try {
                    customizacoes = JSON.parse(this.dataset.customizacoes || '[]');
                } catch (e) {
                    customizacoes = [];
                }

                modalCustomProduto.textContent = produto;
                modalCustomConteudo.innerHTML = '';

                if (customizacoes.length === 0) {
                    modalCustomConteudo.innerHTML = '<p class="text-gray-500 text-sm">Nenhuma customização encontrada.</p>';
                } else {
                    customizacoes.forEach(function(c, index) {
                        const valorFormatado = parseFloat(c.valor || 0)
                            .toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });

                        const bloco = document.createElement('div');
                        bloco.className = 'border border-gray-200 rounded-md p-4 bg-gray-50';

                        bloco.innerHTML = `
                            <p class="text-sm font-semibold text-gray-800 mb-2">Customização ${index + 1}</p>
                            <div class="grid grid-cols-2 gap-2 text-sm text-gray-700">
                                <p><span class="text-gray-500">Tipo:</span> ${c.tipo ?? 'N/A'}</p>
                                <p><span class="text-gray-500">Local:</span> ${c.local ?? 'N/A'}</p>
                                <p><span class="text-gray-500">Posição:</span> ${c.posicao ?? 'N/A'}</p>
                                <p><span class="text-gray-500">Tamanho:</span> ${c.tamanho ?? 'N/A'}</p>
                                <p><span class="text-gray-500">Formatação:</span> ${c.formatacao ?? 'N/A'}</p>
                                <p><span class="text-gray-500">Valor:</span> R$ ${valorFormatado}</p>
                            </div>
                            ${c.descricao ? `<p class="text-sm text-gray-700 mt-2"><span class="text-gray-500">Descrição:</span> ${c.descricao}</p>` : ''}
                        `;

                        modalCustomConteudo.appendChild(bloco);
                    });
                }

                modalCustom.classList.remove('hidden');
                modalCustom.classList.add('flex');
            });
        });

        if (fecharModalCustom) {
            fecharModalCustom.addEventListener('click', function() {
                modalCustom.classList.add('hidden');
                modalCustom.classList.remove('flex');
            });
        }

        if (modalCustom) {
            modalCustom.addEventListener('click', function(e) {
                if (e.target === modalCustom) {
                    modalCustom.classList.add('hidden');
                    modalCustom.classList.remove('flex');
                }
            });
        }
    });
</script>
@endpush