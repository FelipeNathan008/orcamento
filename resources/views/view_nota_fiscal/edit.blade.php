@extends('layouts.app')

@section('title', 'Editar Nota Fiscal')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Editar Nota Fiscal" :back-url="$urlVoltar" />
        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="notaForm"
            action="{{ route('nota_fiscal.update', CryptHelper::encrypt($nota->id_nota_fiscal)) }}"
            method="POST"
            class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            @method('PUT')
            <input type="hidden" name="return_url" value="{{ $urlVoltar }}">

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados da nota fiscal</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Atualize as informações da nota fiscal.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Orçamento</label>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="text" id="orcamento_nome" value="#{{ $nota->orcamento->id_orcamento }} - {{ $nota->orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" readonly>
                            <button type="button" id="btnBuscarOrcamento" class="inline-flex items-center justify-center h-11 px-5 text-sm font-semibold text-white bg-[#EA792D] rounded-lg hover:bg-[#d96b25] transition whitespace-nowrap">
                                Buscar orçamento
                            </button>
                        </div>
                        <input type="hidden" name="orcamento_id_orcamento" id="orcamento_id" value="{{ old('orcamento_id_orcamento', $nota->orcamento_id_orcamento) }}" required>
                    </div>

                    <div>
                        <label for="dataHoje" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Data</label>
                        <input type="date" name="nota_data" id="dataHoje" value="{{ old('nota_data', \Carbon\Carbon::parse($nota->nota_data)->format('Y-m-d')) }}" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                    </div>

                    <div>
                        <label for="nota_numero" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Número da Nota</label>
                        <input type="text" name="nota_numero" id="nota_numero" value="{{ old('nota_numero', $nota->nota_numero) }}" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                    </div>

                    <div>
                        <label for="categoria_tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Categoria</label>
                        <select name="categoria_tipo" id="categoria_tipo" required class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            <option value="">Selecione</option>
                            <option value="fixa" {{ old('categoria_tipo', $categoriaNota) === 'fixa' ? 'selected' : '' }}>Fixa</option>
                            <option value="variavel" {{ old('categoria_tipo', $categoriaNota) === 'variavel' ? 'selected' : '' }}>Variável</option>
                            <option value="caixa" {{ old('categoria_tipo', $categoriaNota) === 'caixa' ? 'selected' : '' }}>Caixa</option>
                        </select>
                    </div>

                    <div>
                        <label for="selectTipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tipo</label>
                        <select name="nota_id_tipo" id="selectTipo" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                            <option value="">Selecione</option>
                            @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo_fluxo }}" {{ old('nota_id_tipo', $nota->nota_id_tipo) == $tipo->id_tipo_fluxo ? 'selected' : '' }}>
                                {{ $tipo->tipo_flu_nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="movimentacao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Movimentação</label>
                        <select name="nota_id_movimentacao" id="movimentacao" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                            <option value="">Selecione</option>
                            @foreach($movimentacoes as $mov)
                            <option value="{{ $mov->id_movimentacao }}" {{ old('nota_id_movimentacao', $nota->nota_id_movimentacao) == $mov->id_movimentacao ? 'selected' : '' }}>
                                {{ $mov->mov_nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="valorMask" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Valor</label>
                        <input type="text" id="valorMask" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                        <input type="hidden" name="nota_valor" id="valorReal" value="{{ old('nota_valor', $nota->nota_valor) }}">
                    </div>

                    <div>
                        <label for="nota_desc" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Descrição</label>
                        <textarea name="nota_desc" id="nota_desc" rows="3" maxlength="255" class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none" required>{{ old('nota_desc', $nota->nota_desc) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">Voltar para a lista</x-secondary-button>
                <x-primary-button type="submit" id="btnAtualizar" class="px-6">
                    <span id="textoAtualizar">Atualizar nota fiscal</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<div id="modalOrcamentos" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white w-full max-w-6xl max-h-[90vh] rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-5 border-b border-gray-200 bg-gray-50">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[0.16em] text-[#EA792D]">Orçamentos</p>
                <h2 class="text-xl font-bold text-gray-800 mt-1">Selecionar Orçamento</h2>
            </div>
            <button type="button" id="fecharModalOrc" class="flex h-9 w-9 items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-200 transition">×</button>
        </div>

        <div class="p-6">
            <input type="text" id="buscarOrcamentoInput" placeholder="Digite ID, código, cliente..." class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 mb-4">

            <div id="carregandoOrcamentos" class="hidden text-center py-8 text-sm text-gray-500">
                Carregando orçamentos...
            </div>

            <div id="avisoOrcamentos" class="hidden text-center py-8 text-sm text-gray-500"></div>

            <div class="overflow-auto max-h-[55vh] border border-gray-200 rounded-xl">
                <table class="w-full text-sm">
                    <thead class="bg-gray-800 text-white sticky top-0">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold">ID</th>
                            <th class="px-4 py-3 text-left font-semibold">Cód. Interno/Fábrica</th>
                            <th class="px-4 py-3 text-left font-semibold">Cliente</th>
                            <th class="px-4 py-3 text-left font-semibold">Data</th>
                            <th class="px-4 py-3 text-right"></th>
                        </tr>
                    </thead>
                    <tbody id="tabelaOrcamentos" class="divide-y divide-gray-100"></tbody>
                </table>
            </div>

            <div id="paginacaoOrcamentos" class="flex items-center justify-center gap-2 mt-4"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<div id="tiposNotaData" data-tipos='@json($tipos)' data-movimentacoes='@json($movimentacoes)'></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tipos = JSON.parse(document.getElementById('tiposNotaData').dataset.tipos);
        const form = document.getElementById('notaForm');
        const btnAtualizar = document.getElementById('btnAtualizar');
        const textoAtualizar = document.getElementById('textoAtualizar');
        const categoriaTipo = document.getElementById('categoria_tipo');
        const selectTipo = document.getElementById('selectTipo');
        const movimentacao = document.getElementById('movimentacao');
        const valorInput = document.getElementById('valorMask');
        const valorReal = document.getElementById('valorReal');
        const tipoOld = "{{ old('nota_id_tipo', $nota->nota_id_tipo) }}";
        const movimentacaoOld = "{{ old('nota_id_movimentacao', $nota->nota_id_movimentacao) }}";

        form.addEventListener('submit', function(e) {
            if (btnAtualizar.disabled) {
                e.preventDefault();
                return;
            }

            valorReal.value = valorInput.value.replace(/\D/g, '');
            valorReal.value = (parseInt(valorReal.value || 0) / 100).toFixed(2);

            btnAtualizar.disabled = true;
            textoAtualizar.textContent = 'Atualizando...';
            btnAtualizar.classList.add('opacity-70', 'cursor-not-allowed');
        });

        valorInput.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');

            if (!value) {
                this.value = '';
                valorReal.value = '';
                return;
            }

            value = (value / 100).toFixed(2).replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            this.value = 'R$ ' + value;
            valorReal.value = value.replace(/\./g, '').replace(',', '.');
        });

        if (valorReal.value) {
            let value = parseFloat(valorReal.value).toFixed(2).replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            valorInput.value = 'R$ ' + value;
        }

        function carregarTipos() {
            const categoria = categoriaTipo.value;
            selectTipo.innerHTML = '<option value="">Selecione</option>';

            if (!categoria) return;

            if (categoria === 'caixa') {
                const tipoCaixa = tipos.find(tipo =>
                    tipo.tipo_flu_nome &&
                    tipo.tipo_flu_nome.toLowerCase() === 'caixa'
                );

                if (tipoCaixa) {
                    selectTipo.innerHTML = `
                    <option value="${tipoCaixa.id_tipo_fluxo}">
                        ${tipoCaixa.tipo_flu_nome}
                    </option>
                `;
                    selectTipo.value = tipoCaixa.id_tipo_fluxo;
                }

                return;
            }

            tipos.forEach(tipo => {
                if (tipo.tipo_despesa && tipo.tipo_despesa.toLowerCase() === categoria) {
                    const option = document.createElement('option');
                    option.value = tipo.id_tipo_fluxo;
                    option.textContent = tipo.tipo_flu_nome;

                    if (String(tipo.id_tipo_fluxo) === String(tipoOld)) {
                        option.selected = true;
                    }

                    selectTipo.appendChild(option);
                }
            });

            if (tipoOld) selectTipo.value = tipoOld;
        }

        categoriaTipo.addEventListener('change', carregarTipos);
        carregarTipos();

        if (movimentacaoOld) {
            movimentacao.value = movimentacaoOld;
        }

        const modal = document.getElementById('modalOrcamentos');
        const btnBuscar = document.getElementById('btnBuscarOrcamento');
        const fechar = document.getElementById('fecharModalOrc');
        const busca = document.getElementById('buscarOrcamentoInput');
        const tabelaOrcamentos = document.getElementById('tabelaOrcamentos');
        const paginacaoOrcamentos = document.getElementById('paginacaoOrcamentos');
        const carregandoOrcamentos = document.getElementById('carregandoOrcamentos');
        const avisoOrcamentos = document.getElementById('avisoOrcamentos');
        const nomeOrc = document.getElementById('orcamento_nome');
        const idOrc = document.getElementById('orcamento_id');

        function carregarOrcamentos(pagina = 1) {
            tabelaOrcamentos.innerHTML = '';
            paginacaoOrcamentos.innerHTML = '';
            avisoOrcamentos.classList.add('hidden');
            carregandoOrcamentos.classList.remove('hidden');

            const termo = busca.value.trim();

            fetch(`{{ route('nota_fiscal.buscar_orcamentos') }}?busca=${encodeURIComponent(termo)}&page=${pagina}`)
                .then(response => {
                    if (!response.ok) throw new Error();
                    return response.json();
                })
                .then(data => {
                    carregandoOrcamentos.classList.add('hidden');

                    if (!data.orcamentos.length) {
                        avisoOrcamentos.textContent = termo ?
                            'Nenhum orçamento encontrado para a busca informada.' :
                            'Nenhum orçamento cadastrado.';
                        avisoOrcamentos.classList.remove('hidden');
                        return;
                    }

                    data.orcamentos.forEach(orcamento => {
                        const linha = document.createElement('tr');

                        linha.className = 'border-t border-gray-100 hover:bg-orange-50/40';

                        linha.innerHTML = `
                        <td class="px-4 py-3">#${orcamento.id}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-col">
                                <span>${orcamento.cod_interno ?? 'N/A'}</span>
                                <span class="text-xs text-gray-500">${orcamento.cod_fabrica ?? 'N/A'}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3">${orcamento.cliente ?? 'N/A'}</td>
                        <td class="px-4 py-3">${orcamento.data ?? 'N/A'}</td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" class="selecionarOrcamento inline-flex items-center px-3 py-1.5 rounded-lg bg-[#EA792D] text-white text-xs font-semibold hover:bg-[#d96b25] transition">
                                Selecionar
                            </button>
                        </td>
                    `;

                        linha.querySelector('.selecionarOrcamento').addEventListener('click', function() {
                            idOrc.value = orcamento.id;
                            nomeOrc.value = `#${orcamento.id} - ${orcamento.cliente ?? 'N/A'} (${orcamento.data ?? 'N/A'})`;
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        });

                        tabelaOrcamentos.appendChild(linha);
                    });

                    criarPaginacaoOrcamentos(data.current_page, data.last_page);
                })
                .catch(() => {
                    carregandoOrcamentos.classList.add('hidden');
                    avisoOrcamentos.textContent = 'Não foi possível carregar os orçamentos.';
                    avisoOrcamentos.classList.remove('hidden');
                });
        }

        function criarPaginacaoOrcamentos(atual, ultima) {
            paginacaoOrcamentos.innerHTML = '';

            if (ultima <= 1) return;

            const anterior = document.createElement('button');
            anterior.type = 'button';
            anterior.textContent = 'Anterior';
            anterior.disabled = atual === 1;
            anterior.className = 'px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-medium text-gray-700 disabled:opacity-40';

            anterior.addEventListener('click', () => {
                if (atual > 1) carregarOrcamentos(atual - 1);
            });

            paginacaoOrcamentos.appendChild(anterior);

            const info = document.createElement('span');
            info.className = 'px-3 py-1.5 text-xs text-gray-500';
            info.textContent = `Página ${atual} de ${ultima}`;
            paginacaoOrcamentos.appendChild(info);

            const proxima = document.createElement('button');
            proxima.type = 'button';
            proxima.textContent = 'Próxima';
            proxima.disabled = atual === ultima;
            proxima.className = 'px-3 py-1.5 rounded-lg border border-gray-300 bg-white text-xs font-medium text-gray-700 disabled:opacity-40';

            proxima.addEventListener('click', () => {
                if (atual < ultima) carregarOrcamentos(atual + 1);
            });

            paginacaoOrcamentos.appendChild(proxima);
        }

        btnBuscar.addEventListener('click', function() {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            busca.value = '';
            carregarOrcamentos(1);
            setTimeout(() => busca.focus(), 100);
        });

        fechar.addEventListener('click', function() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        let timeoutBusca;

        busca.addEventListener('input', function() {
            clearTimeout(timeoutBusca);
            timeoutBusca = setTimeout(() => carregarOrcamentos(1), 300);
        });
    });
</script>
@endpush