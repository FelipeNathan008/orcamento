@extends('layouts.app_financeiro')

@php
use App\Helpers\CryptHelper;

$idOrcamentoFracionado = CryptHelper::encrypt($orcamentoFracionado->id_orcamento_fracionado);
@endphp

@section('title', 'Adicionar Itens ao Orçamento Fracionado')
@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header
            title="Adicionar Itens ao Fracionado #{{ $orcamentoFracionado->orc_fracao }}"
            :back-url="route('detalhes_orcamento_fracionado.index', $idOrcamentoFracionado)">
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        @if(isset($orcamentoFracionado))
        <div class="px-6 sm:px-8 pt-4">
            <x-info-card
                title="Orçamento fracionado selecionado"
                :name="'Fração #' . $orcamentoFracionado->orc_fracao"
                type="Orçamento Fracionado"
                :fields="[
                    [
                        'label' => 'ID',
                        'value' => $orcamentoFracionado->id_orcamento_fracionado,
                        'bold' => true,
                    ],
                    [
                        'label' => 'Cliente',
                        'value' => $orcamentoFracionado->clienteOrcamento->clie_orc_nome ?? 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Cód. Fábrica',
                        'value' => $orcamentoFracionado->orc_cod_fabrica ?: 'Não informado',
                    ],
                    [
                        'label' => 'Cód. Interno',
                        'value' => $orcamentoFracionado->orc_cod_interno ?: 'Não informado',
                    ],
                    [
                        'label' => 'Status',
                        'value' => ucfirst($orcamentoFracionado->orc_status),
                    ],
                ]" />
        </div>
        @endif

        @if ($errors->any())
        <div class="px-6 sm:px-8 pt-4">
            <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                <p class="text-red-700 font-semibold mb-2">Corrija os itens abaixo:</p>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="px-6 sm:px-8 pt-5">
            <div class="bg-orange-50 border border-orange-100 rounded-xl px-5 py-4">
                <p class="text-sm text-orange-800 leading-relaxed">
                    Selecione os itens do orçamento original que farão parte deste fracionado.
                    É obrigatório informar a quantidade de cada item selecionado e nunca será
                    possível incluir uma quantidade maior que a disponível. Itens já totalmente
                    alocados em outros fracionados não aparecem nesta lista. As customizações dos
                    itens selecionados serão copiadas automaticamente.
                </p>
            </div>
        </div>

        <div class="px-6 sm:px-8 pt-5">
            <div class="px-6 sm:px-8 pt-5">
                <form method="GET" action="{{ route('detalhes_orcamento_fracionado.create', $idOrcamentoFracionado) }}">
                    <x-filter-card>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                            <div>
                                <label for="produto" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                    Produto
                                </label>
                                <input
                                    type="text"
                                    id="produto"
                                    name="produto"
                                    value="{{ request('produto') }}"
                                    placeholder="Nome do produto..."
                                    class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            </div>

                            <div>
                                <label for="categoria" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                    Categoria
                                </label>
                                <input
                                    type="text"
                                    id="categoria"
                                    name="categoria"
                                    value="{{ request('categoria') }}"
                                    placeholder="Categoria..."
                                    class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            </div>

                            <div>
                                <label for="cod_ref" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                    Código Ref.
                                </label>
                                <input
                                    type="text"
                                    id="cod_ref"
                                    name="cod_ref"
                                    value="{{ request('cod_ref') }}"
                                    placeholder="Código de referência..."
                                    class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            </div>

                            <div>
                                <label for="familia" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                    Família
                                </label>
                                <select
                                    id="familia"
                                    name="familia"
                                    class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                    <option value="">Todas</option>
                                    @foreach($familias as $familia)
                                    <option value="{{ $familia }}" {{ request('familia') == $familia ? 'selected' : '' }}>
                                        {{ $familia }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex items-end">
                                <x-primary-button class="w-full h-11">
                                    <x-icons.search />
                                    Buscar
                                </x-primary-button>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                            <a
                                href="{{ route('detalhes_orcamento_fracionado.create', $idOrcamentoFracionado) }}"
                                class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                                <x-icons.reset />
                                Limpar filtros
                            </a>
                        </div>
                    </x-filter-card>
                </form>
            </div>
        </div>

        <div class="px-6 sm:px-8 pt-6 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">
                    Itens disponíveis
                </h2>

                @if(!$detalhesOrcamento->isEmpty())
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $detalhesOrcamento->total() }} item(ns)
                    </span>
                </div>
                @endif
            </div>

            @if ($detalhesOrcamento->isEmpty())

            @if(request('produto') || request('categoria') || request('cod_ref') || request('familia'))
            <x-empty-state
                title="Nenhum item encontrado"
                message="Não existem itens correspondentes aos filtros informados."
                route="detalhes_orcamento_fracionado.create"
                :route-params="['id' => $idOrcamentoFracionado]"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhum item encontrado"
                message="Não existem itens correspondentes aos filtros informados."
                route="detalhes_orcamento_fracionado.create"
                :route-params="['id' => $idOrcamentoFracionado]"
                button-text="Limpar filtros" />
            @endif

            @else

            <form
                action="{{ route('detalhes_orcamento_fracionado.store', $idOrcamentoFracionado) }}"
                method="POST"
                id="formAdicionarItens">
                @csrf

                <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto divide-y divide-gray-200">
                            <thead style="background-color:#343A40;">
                                <tr>
                                    <th class="px-3 py-3 text-center text-[10px] font-bold text-white uppercase tracking-wider w-10">
                                        <input type="checkbox" id="selecionarTodos" class="rounded">
                                    </th>
                                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">
                                        Produto
                                    </th>
                                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">
                                        Cód.
                                    </th>
                                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">
                                        Categoria
                                    </th>
                                    <th class="px-3 py-3 text-left text-[10px] font-bold text-white uppercase tracking-wider">
                                        Tam.
                                    </th>
                                    <th class="px-3 py-3 text-right text-[10px] font-bold text-white uppercase tracking-wider">
                                        Qtd.
                                    </th>
                                    <th class="px-3 py-3 text-right text-[10px] font-bold text-white uppercase tracking-wider">
                                        Alocado
                                    </th>
                                    <th class="px-3 py-3 text-right text-[10px] font-bold text-white uppercase tracking-wider">
                                        Disp.
                                    </th>
                                    <th class="px-3 py-3 text-right text-[10px] font-bold text-white uppercase tracking-wider">
                                        Valor Unit.
                                    </th>
                                    <th class="px-3 py-3 text-center text-[10px] font-bold text-white uppercase tracking-wider">
                                        Qtd. Incluir
                                    </th>
                                    <th class="px-3 py-3 text-center text-[10px] font-bold text-white uppercase tracking-wider">
                                        Customizações
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="bg-white divide-y divide-gray-100">
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

                                <tr class="group hover:bg-orange-50/40 transition {{ $qtdAlocada > 0 ? 'bg-yellow-50/40' : '' }}">
                                    <td class="px-3 py-4 text-center">
                                        <input
                                            type="checkbox"
                                            name="itens[]"
                                            value="{{ $detalhe->id_det }}"
                                            class="rounded item-checkbox"
                                            {{ $checkedOld ? 'checked' : '' }}>
                                    </td>

                                    <td class="px-3 py-4">
                                        <div class="min-w-0">
                                            <span class="text-sm font-semibold text-gray-800 break-words">
                                                {{ $detalhe->det_nome ?? 'N/A' }}
                                            </span>

                                            @if($qtdAlocada > 0)
                                            <div class="mt-1">
                                                <span class="inline-flex px-2 py-0.5 text-[10px] font-semibold rounded-full bg-yellow-100 text-yellow-700 whitespace-nowrap">
                                                    Parcialmente alocado
                                                </span>
                                            </div>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-3 py-4">
                                        <span class="text-xs font-semibold text-gray-700 break-all">
                                            {{ $detalhe->det_cod ?: 'N/D' }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4">
                                        <span class="text-xs text-gray-700 break-words">
                                            {{ $detalhe->det_categoria ?: 'N/D' }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4">
                                        <span class="text-xs text-gray-700">
                                            {{ $detalhe->det_tamanho ?? 'N/D' }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4 text-right">
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ $detalhe->det_quantidade }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4 text-right">
                                        <span class="text-sm font-semibold {{ $qtdAlocada > 0 ? 'text-yellow-700' : 'text-gray-400' }}">
                                            {{ $qtdAlocada }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4 text-right">
                                        <span class="text-sm font-bold text-green-700">
                                            {{ $restante }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4 text-right whitespace-nowrap">
                                        <span class="text-xs font-bold text-gray-800">
                                            R$ {{ number_format($detalhe->det_valor_unit, 2, ',', '.') }}
                                        </span>
                                    </td>

                                    <td class="px-3 py-4 text-center">
                                        <input
                                            type="number"
                                            name="quantidades[{{ $detalhe->id_det }}]"
                                            min="1"
                                            max="{{ $restante }}"
                                            data-max="{{ $restante }}"
                                            value="{{ $checkedOld ? $qtdOld : '' }}"
                                            placeholder="Máx. {{ $restante }}"
                                            class="w-20 h-9 px-1 text-xs border rounded-lg text-center outline-none transition qtd-input {{ $erroQtd ? 'border-red-500 focus:ring-2 focus:ring-red-100' : 'border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-100' }}"
                                            {{ $checkedOld ? '' : 'disabled' }}>

                                        @if($erroQtd)
                                        <p class="text-red-600 text-[10px] mt-1">{{ $erroQtd }}</p>
                                        @endif
                                    </td>

                                    <td class="px-3 py-4 text-center">
                                        @if($detalhe->customizacoes->count() > 0)
                                        <button
                                            type="button"
                                            class="btn-ver-customizacoes inline-flex items-center justify-center px-2.5 py-1.5 text-[11px] font-semibold rounded-lg bg-blue-50 text-blue-700 border border-blue-100 hover:bg-blue-100 transition whitespace-nowrap"
                                            data-produto="{{ $detalhe->det_nome ?? 'Item' }}"
                                            data-customizacoes="{{ $customizacoesJson->toJson() }}">
                                            {{ $detalhe->customizacoes->count() }} customização(ões)
                                        </button>
                                        @else
                                        <span class="text-xs text-gray-400">
                                            Nenhuma
                                        </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-5">
                    <x-pagination-compact :paginator="$detalhesOrcamento" />
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-3 mt-6">
                    <button
                        type="submit"
                        id="btnSalvarItens"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 border border-transparent text-sm font-semibold rounded-lg text-white hover:brightness-90 transition"
                        style="background-color:#EA792D;">
                        Adicionar Itens Selecionados
                    </button>

                    <a
                        href="{{ route('detalhes_orcamento_fracionado.index', $idOrcamentoFracionado) }}"
                        class="inline-flex items-center justify-center px-6 py-3 text-sm font-semibold rounded-lg text-gray-700 bg-gray-200 hover:bg-gray-300 transition">
                        Cancelar
                    </a>
                </div>
            </form>

            @endif
        </div>
    </div>
</div>

<div id="modalCustomizacoes" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl border border-gray-100 max-h-[85vh] overflow-hidden">
        <div class="flex justify-between items-center px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-bold text-gray-800">
                Customizações — <span id="modalCustomizacoesProduto"></span>
            </h2>

            <button
                type="button"
                id="fecharModalCustomizacoes"
                class="flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-red-600 transition text-xl">
                &times;
            </button>
        </div>

        <div id="modalCustomizacoesConteudo" class="p-6 space-y-3 overflow-y-auto max-h-[70vh]"></div>
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
                        const valorFormatado = parseFloat(c.valor || 0).toLocaleString('pt-BR', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                        const bloco = document.createElement('div');
                        bloco.className = 'border border-gray-200 rounded-xl p-4 bg-gray-50';

                        bloco.innerHTML = `
                        <p class="text-sm font-bold text-gray-800 mb-3">Customização ${index + 1}</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-gray-700">
                            <p><span class="text-gray-500">Tipo:</span> ${c.tipo ?? 'N/A'}</p>
                            <p><span class="text-gray-500">Local:</span> ${c.local ?? 'N/A'}</p>
                            <p><span class="text-gray-500">Posição:</span> ${c.posicao ?? 'N/A'}</p>
                            <p><span class="text-gray-500">Tamanho:</span> ${c.tamanho ?? 'N/A'}</p>
                            <p><span class="text-gray-500">Formatação:</span> ${c.formatacao ?? 'N/A'}</p>
                            <p><span class="text-gray-500">Valor:</span> R$ ${valorFormatado}</p>
                        </div>
                        ${c.descricao ? `<p class="text-sm text-gray-700 mt-3"><span class="text-gray-500">Descrição:</span> ${c.descricao}</p>` : ''}
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