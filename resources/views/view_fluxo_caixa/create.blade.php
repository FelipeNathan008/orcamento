@extends('layouts.app')

@section('title', 'Novo Lançamento')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Novo Lançamento de Fluxo de Caixa" :back-url="route('fluxo_caixa.index')" />
        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>
        <form action="{{ route('fluxo_caixa.store') }}" method="POST" id="fluxoCaixaForm" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do lançamento</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Preencha as informações para registrar a movimentação financeira.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="flu_data_despesa" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Data</label>
                        <input type="date" name="flu_data_despesa" id="flu_data_despesa" value="{{ old('flu_data_despesa') }}" required class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                    </div>
                    <div>
                        <label for="categoria_tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Categoria</label>
                        <select name="categoria_tipo" id="categoria_tipo" required class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            <option value="">Selecione</option>
                            <option value="fixa" {{ old('categoria_tipo') === 'fixa' ? 'selected' : '' }}>Fixa</option>
                            <option value="variavel" {{ old('categoria_tipo') === 'variavel' ? 'selected' : '' }}>Variável</option>
                            <option value="caixa" {{ old('categoria_tipo') === 'caixa' ? 'selected' : '' }}>Caixa</option>
                        </select>
                    </div>
                    <div>
                        <label for="flu_id_tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tipo</label>
                        <select name="flu_id_tipo" id="flu_id_tipo" required class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            <option value="">Selecione</option>
                            @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id_tipo_fluxo }}" {{ old('flu_id_tipo') == $tipo->id_tipo_fluxo ? 'selected' : '' }}>
                                {{ $tipo->tipo_flu_nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="flu_id_movimentacao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Movimentação</label>
                        <select name="flu_id_movimentacao" id="flu_id_movimentacao" required class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            <option value="">Selecione</option>
                            @foreach($movimentacoes as $mov)
                            <option value="{{ $mov->id_movimentacao }}" {{ old('flu_id_movimentacao') == $mov->id_movimentacao ? 'selected' : '' }}>
                                {{ $mov->mov_nome }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="conta_bancaria_id" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Conta Bancária</label>
                        <select name="conta_bancaria_id" id="conta_bancaria_id" required class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            <option value="">Selecione</option>
                            @foreach($contas as $conta)
                            <option value="{{ $conta->id_conta }}" {{ old('conta_bancaria_id') == $conta->id_conta ? 'selected' : '' }}>
                                {{ $conta->conta_nome_banco }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="valorMask" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Valor</label>
                        <input type="text" id="valorMask" value="{{ old('flu_valor') ? 'R$ ' . number_format(old('flu_valor'), 2, ',', '.') : '' }}" placeholder="R$ 0,00" required class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        <input type="hidden" name="flu_valor" id="valorReal" value="{{ old('flu_valor') }}">
                    </div>
                    <div>
                        <label for="flu_tipo_fiscal" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tipo Fiscal</label>
                        <select name="flu_tipo_fiscal" id="flu_tipo_fiscal" required disabled class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                            <option value="">Selecione a movimentação primeiro</option>
                        </select>
                    </div>
                    <div>
                        <label for="flu_num_doc" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Número do Documento (Opcional)</label>
                        <input type="text" name="flu_num_doc" id="flu_num_doc" maxlength="255" value="{{ old('flu_num_doc') }}" placeholder="Número do documento" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                    </div>
                    <div class="md:col-span-2">
                        <label for="flu_desc" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Descrição</label>
                        <textarea name="flu_desc" id="flu_desc" rows="5" maxlength="180" required class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none" placeholder="Descrição da movimentação">{{ old('flu_desc') }}</textarea>

                    </div>
                </div>
            </div>
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="route('fluxo_caixa.index')">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="btnSalvarFluxo" class="px-6">
                    <span id="textoSalvar">Salvar lançamento</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<div id="tiposFluxoData" data-tipos='@json($tipos)'></div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tiposFluxo = JSON.parse(document.getElementById('tiposFluxoData').dataset.tipos);
        const tipoFiscalOld = "{{ old('flu_tipo_fiscal') }}";
        const tipoOld = "{{ old('flu_id_tipo') }}";
        const form = document.getElementById('fluxoCaixaForm');
        const btnSalvar = document.getElementById('btnSalvarFluxo');
        const textoSalvar = document.getElementById('textoSalvar');
        const categoriaTipo = document.getElementById('categoria_tipo');
        const selectTipo = document.getElementById('flu_id_tipo');
        const movimentacao = document.getElementById('flu_id_movimentacao');
        const tipoFiscal = document.getElementById('flu_tipo_fiscal');
        const valorInput = document.getElementById('valorMask');
        const valorReal = document.getElementById('valorReal');

        form.addEventListener('submit', function(e) {
            tipoFiscal.disabled = false;
            if (btnSalvar.disabled) {
                e.preventDefault();
                return;
            }
            btnSalvar.disabled = true;
            textoSalvar.textContent = 'Salvando...';
            btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
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

        function carregarTiposFiscais() {
            const textoSelecionado = movimentacao.options[movimentacao.selectedIndex]?.text?.toLowerCase() || '';
            tipoFiscal.innerHTML = '<option value="">Selecione</option>';
            tipoFiscal.disabled = true;

            if (textoSelecionado.includes('saida') || textoSelecionado.includes('saída')) {
                tipoFiscal.innerHTML += `
                <option value="DG">Despesa Geral (DG)</option>
                <option value="NF">Nota Fiscal (NF)</option>
                <option value="RC">Recibo (RC)</option>
                <option value="CF">Cupom Fiscal (CF)</option>
                <option value="SA">Saque (SA)</option>
                <option value="OUT">Outros</option>
            `;
                tipoFiscal.disabled = false;
            } else if (textoSelecionado.includes('entrada')) {
                tipoFiscal.innerHTML += `
                <option value="OC">Orçamento (OC)</option>
                <option value="AP">Aporte (AP)</option>
            `;
                tipoFiscal.disabled = false;
            }

            if (tipoFiscalOld) {
                tipoFiscal.value = tipoFiscalOld;
            }
        }

        function carregarTipos() {
            const categoria = categoriaTipo.value;
            selectTipo.innerHTML = '<option value="">Selecione</option>';

            if (!categoria) return;

            if (categoria === 'caixa') {
                const tipoCaixa = tiposFluxo.find(tipo =>
                    tipo.tipo_flu_nome &&
                    tipo.tipo_flu_nome.toLowerCase() === 'caixa'
                );

                if (tipoCaixa) {
                    selectTipo.innerHTML = `
                    <option value="${tipoCaixa.id_tipo_fluxo}" selected>
                        ${tipoCaixa.tipo_flu_nome}
                    </option>
                `;
                    selectTipo.value = tipoCaixa.id_tipo_fluxo;
                }
                return;
            }

            tiposFluxo.forEach(tipo => {
                if (tipo.tipo_despesa && tipo.tipo_despesa.toLowerCase() === categoria) {
                    selectTipo.innerHTML += `
                    <option value="${tipo.id_tipo_fluxo}"
                        ${String(tipo.id_tipo_fluxo) === String(tipoOld) ? 'selected' : ''}>
                        ${tipo.tipo_flu_nome}
                    </option>
                `;
                }
            });
        }

        movimentacao.addEventListener('change', carregarTiposFiscais);
        categoriaTipo.addEventListener('change', carregarTipos);
        carregarTipos();
        carregarTiposFiscais();
    });
</script>
@endpush