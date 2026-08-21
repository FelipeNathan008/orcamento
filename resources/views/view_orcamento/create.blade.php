{{-- resources/views/view_orcamento/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Criar Novo Orçamento')

@section('content')

<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Criar Novo Orçamento
    </h1>

    @if(isset($clienteSelecionado))

    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">
        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Cliente
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-600">Nome</p>
                <p class="font-semibold text-gray-900">
                    {{ $clienteSelecionado->clie_orc_nome }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">E-mail</p>
                <p class="font-semibold text-gray-900">
                    {{ $clienteSelecionado->clie_orc_email }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Celular</p>
                <p class="font-semibold text-gray-900">
                    {{ preg_replace(
                            '/(\d{2})(\d{5})(\d{4})/',
                            '($1) $2-$3',
                            preg_replace('/\D/', '', $clienteSelecionado->clie_orc_celular)
                        ) }}
                </p>
            </div>
        </div>
    </div>

    <x-alert-flash />

    <form
        id="orcamentoForm"
        action="{{ route('orcamento.store') }}"
        method="POST"
        class="space-y-6">
        @csrf

        <input
            type="hidden"
            name="cliente_orcamento_id_co"
            value="{{ $clienteSelecionado->id_co }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
            <div class="space-y-6">
                <div>
                    <label
                        for="orc_data_inicio"
                        class="block text-sm font-medium text-custom-dark-text mb-1">
                        Data Início
                    </label>

                    <input
                        type="date"
                        name="orc_data_inicio"
                        id="orc_data_inicio"
                        value="{{ old('orc_data_inicio') }}"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        required>

                    @error('orc_data_inicio')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-custom-dark-text mb-1">
                        Anotação Específica
                    </label>

                    <div class="space-y-2">
                        <textarea
                            name="anotacoes[]"
                            rows="3"
                            maxlength="1000"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300"
                            placeholder="ANOTAÇÕES">{{ old('anotacoes.0') }}</textarea>

                        <textarea
                            name="anotacoes[]"
                            rows="3"
                            maxlength="1000"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300"
                            placeholder="OBSERVAÇÕES">{{ old('anotacoes.1') }}</textarea>

                        <textarea
                            name="anotacoes[]"
                            rows="3"
                            maxlength="1000"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300"
                            placeholder="IMPORTANTE">{{ old('anotacoes.2') }}</textarea>
                    </div>

                    @error('anotacoes')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror

                    @error('anotacoes.*')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label
                        for="orc_data_fim"
                        class="block text-sm font-medium text-custom-dark-text mb-1">
                        Data Fim
                    </label>

                    <input
                        type="date"
                        name="orc_data_fim"
                        id="orc_data_fim"
                        value="{{ old('orc_data_fim') }}"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        required>

                    @error('orc_data_fim')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label
                        for="orc_status"
                        class="block text-sm font-medium text-custom-dark-text mb-1">
                        Status
                    </label>

                    <select
                        name="orc_status"
                        id="orc_status"
                        class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        required>
                        <option value="">Selecione um Status</option>

                        <option
                            value="pendente"
                            {{ old('orc_status') == 'pendente' ? 'selected' : '' }}>
                            Pendente
                        </option>

                        <option
                            value="para aprovacao"
                            {{ old('orc_status') == 'para aprovacao' ? 'selected' : '' }}>
                            Para Aprovação
                        </option>

                        <option
                            value="rejeitado"
                            {{ old('orc_status') == 'rejeitado' ? 'selected' : '' }}>
                            Rejeitado
                        </option>
                    </select>

                    @error('orc_status')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label
                            for="orc_cod_interno"
                            class="block text-sm font-medium text-custom-dark-text mb-1">
                            Código Interno
                        </label>

                        <input
                            type="text"
                            name="orc_cod_interno"
                            id="orc_cod_interno"
                            maxlength="60"
                            placeholder="Código interno"
                            value="{{ old('orc_cod_interno') }}"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @error('orc_cod_interno')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="orc_cod_fabrica"
                            class="block text-sm font-medium text-custom-dark-text mb-1">
                            Código da Fábrica
                        </label>

                        <input
                            type="text"
                            name="orc_cod_fabrica"
                            id="orc_cod_fabrica"
                            maxlength="60"
                            placeholder="Código da fábrica"
                            value="{{ old('orc_cod_fabrica') }}"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        @error('orc_cod_fabrica')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label
                        for="orc_anotacao_geral"
                        class="block text-sm font-medium text-custom-dark-text mb-1">
                        Anotação Geral
                    </label>

                    <textarea
                        name="orc_anotacao_geral"
                        id="orc_anotacao_geral"
                        rows="4"
                        maxlength="1000"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Digite uma anotação geral">{{ old('orc_anotacao_geral') }}</textarea>

                    @error('orc_anotacao_geral')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <div id="motivoRejeicaoContainer" class="hidden">
                    <label
                        for="orc_motivo_rejeicao"
                        class="block text-sm font-medium text-custom-dark-text mb-1">
                        Motivo da Rejeição
                    </label>

                    <textarea
                        name="orc_motivo_rejeicao"
                        id="orc_motivo_rejeicao"
                        rows="4"
                        maxlength="1000"
                        class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300"
                        placeholder="Descreva o motivo da rejeição...">{{ old('orc_motivo_rejeicao') }}</textarea>

                    @error('orc_motivo_rejeicao')
                    <p class="mt-2 text-sm text-red-600">
                        {{ $message }}
                    </p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex justify-center mt-8">
            <button
                type="submit"
                id="BtnSalvarOrcamento"
                class="py-3 px-8 rounded-md text-white bg-button-save-bg hover:bg-button-save-hover transition duration-150 ease-in-out">
                SALVAR
            </button>
        </div>

        <div class="flex justify-center mb-8">
            <a
                href="{{ route('orcamento.index', [
                        'cliente_orcamento_id' => $clienteSelecionado->id_co
                    ]) }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>
    </form>

    @endif

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('orcamentoForm');
        const btnSalvar = document.getElementById('BtnSalvarOrcamento');
        const dataInicioInput = document.getElementById('orc_data_inicio');
        const dataFimInput = document.getElementById('orc_data_fim');
        const statusSelect = document.getElementById('orc_status');
        const motivoContainer = document.getElementById('motivoRejeicaoContainer');
        const motivoInput = document.getElementById('orc_motivo_rejeicao');

        function configurarDataFim(preencherAutomaticamente) {
            if (!dataInicioInput.value) {
                dataFimInput.value = '';
                dataFimInput.removeAttribute('min');
                return;
            }

            const dataInicio = new Date(`${dataInicioInput.value}T00:00:00`);
            const dataMinimaFim = new Date(dataInicio);

            dataMinimaFim.setDate(dataMinimaFim.getDate() + 1);

            const dataMinima = [
                dataMinimaFim.getFullYear(),
                String(dataMinimaFim.getMonth() + 1).padStart(2, '0'),
                String(dataMinimaFim.getDate()).padStart(2, '0')
            ].join('-');

            dataFimInput.min = dataMinima;

            if (preencherAutomaticamente) {
                const dataFim = new Date(dataInicio);

                dataFim.setDate(dataFim.getDate() + 15);

                dataFimInput.value = [
                    dataFim.getFullYear(),
                    String(dataFim.getMonth() + 1).padStart(2, '0'),
                    String(dataFim.getDate()).padStart(2, '0')
                ].join('-');

                return;
            }

            if (dataFimInput.value && dataFimInput.value < dataMinima) {
                dataFimInput.value = '';
            }
        }

        function toggleMotivoRejeicao() {
            const rejeitado = statusSelect.value === 'rejeitado';

            motivoContainer.classList.toggle('hidden', !rejeitado);

            if (rejeitado) {
                motivoInput.setAttribute('required', 'required');
            } else {
                motivoInput.removeAttribute('required');
                motivoInput.value = '';
            }
        }

        dataInicioInput.addEventListener('change', function() {
            configurarDataFim(true);
        });

        dataFimInput.addEventListener('change', function() {
            if (!dataInicioInput.value || !this.value) {
                return;
            }

            const dataInicio = new Date(`${dataInicioInput.value}T00:00:00`);
            const dataFim = new Date(`${this.value}T00:00:00`);

            if (dataFim <= dataInicio) {
                alert('A Data Fim deve ser maior que a Data Início.');
                this.value = '';
                this.focus();
            }
        });

        if (dataInicioInput.value) {
            configurarDataFim(false);
        }

        toggleMotivoRejeicao();

        statusSelect.addEventListener('change', toggleMotivoRejeicao);

        form.addEventListener('submit', function(e) {
            if (dataInicioInput.value && dataFimInput.value) {
                const dataInicio = new Date(`${dataInicioInput.value}T00:00:00`);
                const dataFim = new Date(`${dataFimInput.value}T00:00:00`);

                if (dataFim <= dataInicio) {
                    alert('A Data Fim deve ser maior que a Data Início.');
                    e.preventDefault();
                    dataFimInput.focus();
                    return;
                }
            }

            if (statusSelect.value === 'rejeitado' && !motivoInput.value.trim()) {
                alert('Informe o motivo da rejeição.');
                e.preventDefault();
                motivoInput.focus();
                return;
            }

            btnSalvar.disabled = true;
            btnSalvar.textContent = 'SALVANDO...';
            btnSalvar.classList.remove('hover:bg-button-save-hover');
            btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
        });
    });
</script>
@endpush