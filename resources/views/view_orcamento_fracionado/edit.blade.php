@extends('layouts.app_financeiro')

@section('title', 'Editar Orçamento Fracionado')

@section('content')

@php
$statusAtual = old('orc_status', $orcamentoFracionado->orc_status);
$statusBloqueado = in_array(
$orcamentoFracionado->orc_status,
['finalizado', 'rejeitado']
);
@endphp

<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Editar Orçamento Fracionado #{{ $orcamentoFracionado->orc_fracao }}
    </h1>
    <x-alert-flash />

    @if(isset($orcamentoFracionado))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Orçamento Fracionado
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">

            <div>
                <div class="grid grid-cols-3 gap-4">

                    <div>
                        <p class="text-gray-600">ID</p>
                        <p class="font-semibold">
                            {{ $orcamentoFracionado->id_orcamento_fracionado }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Cód. Fábrica</p>
                        <p class="font-semibold">
                            {{ $orcamentoFracionado->orc_cod_fabrica ?: 'N/D' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Cód. Interno</p>
                        <p class="font-semibold">
                            {{ $orcamentoFracionado->orc_cod_interno ?: 'N/D' }}
                        </p>
                    </div>

                </div>
            </div>

            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamentoFracionado->clienteOrcamento->clie_orc_nome ?? 'N/A' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Fração</p>
                <p class="font-semibold text-gray-900">
                    #{{ $orcamentoFracionado->orc_fracao }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Status</p>
                <p class="font-semibold text-gray-900">
                    {{ ucfirst($orcamentoFracionado->orc_status) }}
                </p>
            </div>

        </div>
    </div>
    @endif

    <form
        action="{{ route('orcamento.fracionado.update', $orcamentoFracionado->id_orcamento_fracionado) }}"
        method="POST"
        class="space-y-6"
        data-status-anterior="{{ $orcamentoFracionado->orc_status }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

            <div class="space-y-6">

                <div>
                    <label for="orc_data_inicio" class="block text-sm font-medium mb-1">
                        Data Início
                    </label>

                    <input
                        type="date"
                        name="orc_data_inicio"
                        id="orc_data_inicio"
                        value="{{ old('orc_data_inicio', $orcamentoFracionado->orc_data_inicio ? \Carbon\Carbon::parse($orcamentoFracionado->orc_data_inicio)->format('Y-m-d') : '') }}"
                        class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                        required>

                    @error('orc_data_inicio')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="orc_cod_interno" class="block text-sm font-medium mb-1">
                        Código Interno
                    </label>

                    <input
                        type="text"
                        name="orc_cod_interno"
                        id="orc_cod_interno"
                        maxlength="60"
                        placeholder="Código Interno"
                        value="{{ old('orc_cod_interno', $orcamentoFracionado->orc_cod_interno) }}"
                        class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300">

                    @error('orc_cod_interno')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="orc_cod_fabrica" class="block text-sm font-medium mb-1">
                        Código da Fábrica
                    </label>

                    <input
                        type="text"
                        name="orc_cod_fabrica"
                        id="orc_cod_fabrica"
                        maxlength="60"
                        placeholder="Código Fábrica"
                        value="{{ old('orc_cod_fabrica', $orcamentoFracionado->orc_cod_fabrica) }}"
                        class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300">

                    @error('orc_cod_fabrica')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="space-y-6">

                <div>
                    <label for="orc_data_fim" class="block text-sm font-medium mb-1">
                        Data Fim
                    </label>

                    <input
                        type="date"
                        name="orc_data_fim"
                        id="orc_data_fim"
                        value="{{ old('orc_data_fim', $orcamentoFracionado->orc_data_fim ? \Carbon\Carbon::parse($orcamentoFracionado->orc_data_fim)->format('Y-m-d') : '') }}"
                        class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                        required>

                    @error('orc_data_fim')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="orc_status" class="block text-sm font-medium mb-1">
                        Status
                    </label>

                    <select
                        name="orc_status"
                        id="orc_status"
                        class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300 {{ $statusBloqueado ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                        {{ $statusBloqueado ? 'disabled' : '' }}
                        required>
                        @if($orcamentoFracionado->orc_status === 'finalizado')
                        <option value="finalizado" selected>Finalizado</option>

                        @elseif($orcamentoFracionado->orc_status === 'rejeitado')
                        <option value="rejeitado" selected>Rejeitado</option>

                        @elseif($orcamentoFracionado->orc_status === 'aprovado')
                        <option value="aprovado" selected>Aprovado</option>
                        <option value="finalizado">Finalizado</option>
                        <option value="rejeitado">Rejeitado</option>

                        @else
                        <option value="">Selecione</option>
                        <option value="pendente" {{ $statusAtual === 'pendente' ? 'selected' : '' }}>
                            Pendente
                        </option>
                        <option value="para aprovacao" {{ $statusAtual === 'para aprovacao' ? 'selected' : '' }}>
                            Para Aprovação
                        </option>
                        <option value="aprovado" {{ $statusAtual === 'aprovado' ? 'selected' : '' }}>
                            Aprovado
                        </option>
                        <option value="rejeitado" {{ $statusAtual === 'rejeitado' ? 'selected' : '' }}>
                            Rejeitado
                        </option>
                        <option value="finalizado" {{ $statusAtual === 'finalizado' ? 'selected' : '' }}>
                            Finalizado
                        </option>
                        @endif
                    </select>

                    @if($statusBloqueado)
                    <input
                        type="hidden"
                        name="orc_status"
                        value="{{ $orcamento->orc_status }}">
                    @endif

                    @if($orcamento->orc_status === 'finalizado')
                    <p class="mt-2 text-sm text-gray-500">
                        Este orçamento fracionado está finalizado e seu status não pode mais ser alterado.
                    </p>
                    @elseif($orcamento->orc_status === 'rejeitado')
                    <p class="mt-2 text-sm text-gray-500">
                        Este orçamento fracionado foi rejeitado e seu status não pode mais ser alterado.
                    </p>
                    @elseif($orcamento->orc_status === 'aprovado')
                    <p class="mt-2 text-sm text-orange-600">
                        Um orçamento aprovado só pode ser finalizado ou rejeitado.
                    </p>
                    @endif

                    @error('orc_status')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div
                    id="motivoRejeicaoContainer"
                    class="{{ $statusAtual === 'rejeitado' ? '' : 'hidden' }}">
                    <label for="orc_motivo_rejeicao" class="block text-sm font-medium mb-1">
                        Motivo da Rejeição
                    </label>

                    <textarea
                        name="orc_motivo_rejeicao"
                        id="orc_motivo_rejeicao"
                        rows="4"
                        class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                        placeholder="Descreva o motivo da rejeição...">{{ old('orc_motivo_rejeicao', $orcamento->orc_motivo_rejeicao) }}</textarea>

                    @error('orc_motivo_rejeicao')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        <div>
            <label for="orc_anotacao_geral" class="block text-sm font-medium mb-1">
                Anotação Geral
            </label>

            <textarea
                name="orc_anotacao_geral"
                id="orc_anotacao_geral"
                rows="4"
                class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                placeholder="Digite uma anotação geral">{{ old('orc_anotacao_geral', $orcamento->orc_anotacao_geral) }}</textarea>

            @error('orc_anotacao_geral')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-center gap-3 mt-8">
            <button
                type="submit"
                class="inline-flex justify-center py-2 px-6 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover transition">
                ATUALIZAR
            </button>

            <a
                href="{{ route('orcamento.fracionado.index', $orcamento->id_orcamento) }}"
                class="inline-flex justify-center py-2 px-6 shadow-sm text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition">
                VOLTAR
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.querySelector('form[data-status-anterior]');
        const dataInicio = document.getElementById('orc_data_inicio');
        const dataFim = document.getElementById('orc_data_fim');
        const status = document.getElementById('orc_status');
        const motivoContainer = document.getElementById('motivoRejeicaoContainer');
        const motivo = document.getElementById('orc_motivo_rejeicao');

        if (!form) return;

        function validarDatas() {
            if (!dataInicio || !dataFim) return;

            if (!dataInicio.value) {
                dataFim.removeAttribute('min');
                return;
            }

            const inicio = new Date(`${dataInicio.value}T00:00:00`);
            inicio.setDate(inicio.getDate() + 1);

            const minimo = [
                inicio.getFullYear(),
                String(inicio.getMonth() + 1).padStart(2, '0'),
                String(inicio.getDate()).padStart(2, '0')
            ].join('-');

            dataFim.min = minimo;

            if (dataFim.value && dataFim.value < minimo) {
                dataFim.value = '';
            }
        }

        function atualizarMotivo() {
            if (!status || !motivoContainer) return;

            const rejeitado = status.value === 'rejeitado';

            motivoContainer.classList.toggle('hidden', !rejeitado);

            if (!motivo) return;

            if (rejeitado) {
                motivo.required = true;
            } else {
                motivo.required = false;
                motivo.value = '';
            }
        }

        validarDatas();
        atualizarMotivo();

        dataInicio?.addEventListener('change', validarDatas);
        status?.addEventListener('change', atualizarMotivo);

        form.addEventListener('submit', (e) => {
            const statusAnterior = form.dataset.statusAnterior;
            const novoStatus = status?.value || statusAnterior;

            if (
                dataInicio?.value &&
                dataFim?.value &&
                dataFim.value <= dataInicio.value
            ) {
                alert('A Data Fim deve ser maior que a Data Início.');
                e.preventDefault();
                dataFim.focus();
                return;
            }

            if (
                novoStatus === 'rejeitado' &&
                motivo &&
                !motivo.value.trim()
            ) {
                alert('Informe o motivo da rejeição.');
                e.preventDefault();
                motivo.focus();
                return;
            }

            if (
                novoStatus === 'aprovado' &&
                statusAnterior !== 'aprovado' &&
                !confirm('Você deseja colocar este orçamento fracionado como APROVADO?')
            ) {
                e.preventDefault();
                return;
            }

            if (
                novoStatus === 'finalizado' &&
                statusAnterior !== 'finalizado' &&
                !confirm('Você deseja colocar este orçamento fracionado como FINALIZADO?')
            ) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush

@endsection