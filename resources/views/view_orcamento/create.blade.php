@extends('layouts.app')

@section('title', 'Cadastrar Novo Orçamento')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Cadastrar Novo Orçamento" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        @php
        $celularCliente = preg_replace('/\D/', '', $clienteSelecionado->clie_orc_celular ?? '');
        if (strlen($celularCliente) === 11) {
        $celularClienteFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celularCliente);
        } elseif (strlen($celularCliente) === 10) {
        $celularClienteFormatado = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $celularCliente);
        } else {
        $celularClienteFormatado = $clienteSelecionado->clie_orc_celular ?: 'Não informado';
        }
        @endphp

        <form id="orcamentoForm" action="{{ route('orcamento.store') }}" method="POST" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf

            <x-info-card
                title="Cliente selecionado"
                :name="$clienteSelecionado->clie_orc_nome"
                type="Cliente"
                :fields="[
                    [
                        'label' => 'Código interno',
                        'value' => $clienteSelecionado->clie_orc_cod_interno ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'E-mail',
                        'value' => $clienteSelecionado->clie_orc_email ?: 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Celular',
                        'value' => $celularClienteFormatado,
                    ],
                ]" />

            <input type="hidden" name="cliente_orcamento_id_co" value="{{ $clienteSelecionado->id_co }}">

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 mt-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do orçamento</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Preencha as informações para cadastrar o orçamento.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="orc_data_inicio" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Data Início</label>
                        <input type="date" name="orc_data_inicio" id="orc_data_inicio" value="{{ old('orc_data_inicio') }}" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                    </div>

                    <div>
                        <label for="orc_data_fim" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Data Fim</label>
                        <input type="date" name="orc_data_fim" id="orc_data_fim" value="{{ old('orc_data_fim') }}" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                    </div>

                    <div>
                        <label for="orc_status" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Status</label>
                        <select name="orc_status" id="orc_status" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100" required>
                            <option value="">Selecione</option>
                            <option value="pendente" {{ old('orc_status') === 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="para aprovacao" {{ old('orc_status') === 'para aprovacao' ? 'selected' : '' }}>Para Aprovação</option>
                            <option value="rejeitado" {{ old('orc_status') === 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="orc_cod_interno" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Código Interno</label>
                            <input type="text" name="orc_cod_interno" id="orc_cod_interno" maxlength="60" placeholder="Código interno" value="{{ old('orc_cod_interno') }}" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <div>
                            <label for="orc_cod_fabrica" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Código da Fábrica</label>
                            <input type="text" name="orc_cod_fabrica" id="orc_cod_fabrica" maxlength="60" placeholder="Código da fábrica" value="{{ old('orc_cod_fabrica') }}" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Anotações Específicas</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <textarea name="anotacoes[]" rows="3" maxlength="1000" class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none" placeholder="ANOTAÇÕES">{{ old('anotacoes.0') }}</textarea>
                            <textarea name="anotacoes[]" rows="3" maxlength="1000" class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none" placeholder="OBSERVAÇÕES">{{ old('anotacoes.1') }}</textarea>
                            <textarea name="anotacoes[]" rows="3" maxlength="1000" class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none" placeholder="IMPORTANTE">{{ old('anotacoes.2') }}</textarea>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="orc_anotacao_geral" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Anotação Geral</label>
                        <textarea name="orc_anotacao_geral" id="orc_anotacao_geral" rows="4" maxlength="1000" class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none" placeholder="Digite uma anotação geral">{{ old('orc_anotacao_geral') }}</textarea>
                    </div>

                    <div id="motivoRejeicaoContainer" class="md:col-span-2 hidden">
                        <label for="orc_motivo_rejeicao" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Motivo da Rejeição</label>
                        <textarea name="orc_motivo_rejeicao" id="orc_motivo_rejeicao" rows="4" maxlength="1000" class="w-full px-3 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 resize-none" placeholder="Descreva o motivo da rejeição...">{{ old('orc_motivo_rejeicao') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="BtnSalvarOrcamento" class="px-6">
                    <span id="textoSalvar">Salvar orçamento</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('orcamentoForm');
        const btnSalvar = document.getElementById('BtnSalvarOrcamento');
        const textoSalvar = document.getElementById('textoSalvar');
        const dataInicio = document.getElementById('orc_data_inicio');
        const dataFim = document.getElementById('orc_data_fim');
        const status = document.getElementById('orc_status');
        const motivoContainer = document.getElementById('motivoRejeicaoContainer');
        const motivo = document.getElementById('orc_motivo_rejeicao');

        function configurarDataFim(preencher = false) {
            if (!dataInicio.value) {
                dataFim.value = '';
                dataFim.removeAttribute('min');
                return;
            }
            const data = new Date(`${dataInicio.value}T00:00:00`);
            data.setDate(data.getDate() + 1);
            const minima = data.toISOString().split('T')[0];
            dataFim.min = minima;
            if (preencher) {
                data.setDate(data.getDate() + 14);
                dataFim.value = data.toISOString().split('T')[0];
            } else if (dataFim.value && dataFim.value < minima) {
                dataFim.value = '';
            }
        }

        function toggleMotivo() {
            const rejeitado = status.value === 'rejeitado';
            motivoContainer.classList.toggle('hidden', !rejeitado);
            motivo.toggleAttribute('required', rejeitado);
            if (!rejeitado) motivo.value = '';
        }

        dataInicio.addEventListener('change', () => configurarDataFim(true));

        dataFim.addEventListener('change', function() {
            if (dataInicio.value && this.value && this.value <= dataInicio.value) {
                alert('A Data Fim deve ser maior que a Data Início.');
                this.value = '';
                this.focus();
            }
        });

        status.addEventListener('change', toggleMotivo);
        configurarDataFim(false);
        toggleMotivo();

        form.addEventListener('submit', function(e) {
            if (dataFim.value <= dataInicio.value) {
                alert('A Data Fim deve ser maior que a Data Início.');
                e.preventDefault();
                dataFim.focus();
                return;
            }
            if (status.value === 'rejeitado' && !motivo.value.trim()) {
                alert('Informe o motivo da rejeição.');
                e.preventDefault();
                motivo.focus();
                return;
            }
            btnSalvar.disabled = true;
            textoSalvar.textContent = 'Salvando...';
            btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
        });
    });
</script>
@endpush
@endsection