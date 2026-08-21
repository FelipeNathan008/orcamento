{{-- resources/views/view_orcamento/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Orçamento: ' . $orcamento->id_orcamento)

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">


    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Editar Orçamento:
        #{{ $orcamento->id_orcamento }}</h1>

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
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $clienteSelecionado->clie_orc_celular)) }}
                </p>
            </div>

        </div>
    </div>
    @endif
    <x-alert-flash />


    <div>
        <form
            action="{{ route('orcamento.update', $orcamento->id_orcamento) }}" method="POST" class="space-y-6"
            data-status-anterior="{{ $orcamento->orc_status }}"> @csrf
            @method('PUT')

            <input type="hidden" name="cliente_orcamento_id_co"
                value="{{ $clienteSelecionado->id_co }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                {{-- COLUNA 1 --}}
                <div class="space-y-6">

                    {{-- Data Início --}}
                    <div>
                        <label for="orc_data_inicio" class="block text-sm font-medium mb-1">
                            Data Início
                        </label>
                        <input type="date" name="orc_data_inicio" id="orc_data_inicio"
                            class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                            value="{{ old('orc_data_inicio', $orcamento->orc_data_inicio ? $orcamento->orc_data_inicio->format('Y-m-d') : '') }}"
                            required>
                    </div>

                    {{-- Anotações Específicas --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Anotações Específicas
                        </label>

                        @php
                        $anotacoes = old('anotacoes',
                        $orcamento->orc_anotacao_espec
                        ? explode("\n", $orcamento->orc_anotacao_espec)
                        : ['', '', '']
                        );
                        @endphp

                        <div class="space-y-2">
                            <textarea name="anotacoes[]" rows="3"
                                class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                                placeholder="ANOTAÇÕES">{{ $anotacoes[0] ?? '' }}</textarea>

                            <textarea name="anotacoes[]" rows="3"
                                class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                                placeholder="OBSERVAÇÕES">{{ $anotacoes[1] ?? '' }}</textarea>

                            <textarea name="anotacoes[]" rows="3"
                                class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                                placeholder="IMPORTANTE">{{ $anotacoes[2] ?? '' }}</textarea>
                        </div>
                    </div>

                </div>

                {{-- COLUNA 2 --}}
                <div class="space-y-6">

                    {{-- Data Fim --}}
                    <div>
                        <label for="orc_data_fim" class="block text-sm font-medium mb-1">
                            Data Fim
                        </label>
                        <input type="date" name="orc_data_fim" id="orc_data_fim"
                            class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                            value="{{ old('orc_data_fim', $orcamento->orc_data_fim ? $orcamento->orc_data_fim->format('Y-m-d') : '') }}"
                            required>
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="orc_status" class="block text-sm font-medium mb-1">
                            Status
                        </label>

                        @php
                        $statusAtual = old('orc_status', $orcamento->orc_status);

                        // Valor final do orçamento já considerando desconto
                        $valorOrcamento = (float) ($orcamento->total_com_desconto ?? 0);

                        $statusBloqueado = in_array($orcamento->orc_status, [
                        'finalizado',
                        'rejeitado'
                        ]);
                        @endphp

                        <select
                            name="orc_status"
                            id="orc_status"
                            class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300
        {{ $statusBloqueado ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                            {{ $statusBloqueado ? 'disabled' : '' }}
                            required>

                            @if ($orcamento->orc_status === 'finalizado')

                            <option value="finalizado" selected>
                                Finalizado
                            </option>

                            @elseif ($orcamento->orc_status === 'rejeitado')

                            <option value="rejeitado" selected>
                                Rejeitado
                            </option>

                            @elseif ($orcamento->orc_status === 'aprovado')

                            <option value="aprovado" selected>
                                Aprovado
                            </option>

                            <option value="finalizado">
                                Finalizado
                            </option>

                            <option value="rejeitado">
                                Rejeitado
                            </option>

                            @else

                            <option value="">
                                Selecione
                            </option>

                            <option
                                value="pendente"
                                {{ $statusAtual === 'pendente' ? 'selected' : '' }}>
                                Pendente
                            </option>

                            <option
                                value="para aprovacao"
                                {{ $statusAtual === 'para aprovacao' ? 'selected' : '' }}>
                                Para Aprovação
                            </option>

                            {{-- SÓ MOSTRA APROVADO SE O VALOR FOR MAIOR QUE ZERO --}}
                            @if ($valorOrcamento > 0)
                            <option
                                value="aprovado"
                                {{ $statusAtual === 'aprovado' ? 'selected' : '' }}>
                                Aprovado
                            </option>
                            @endif

                            <option
                                value="rejeitado"
                                {{ $statusAtual === 'rejeitado' ? 'selected' : '' }}>
                                Rejeitado
                            </option>

                            @if (!$financeiroPendente)
                            <option
                                value="finalizado"
                                {{ $statusAtual === 'finalizado' ? 'selected' : '' }}>
                                Finalizado
                            </option>
                            @endif

                            @endif

                        </select>

                        {{-- Mantém o status quando o select está disabled --}}
                        @if ($statusBloqueado)
                        <input
                            type="hidden"
                            name="orc_status"
                            value="{{ $orcamento->orc_status }}">
                        @endif

                        @if ($orcamento->orc_status === 'finalizado')

                        <p class="mt-2 text-sm text-gray-500">
                            Este orçamento está finalizado e seu status não pode mais ser alterado.
                        </p>

                        @elseif ($orcamento->orc_status === 'rejeitado')

                        <p class="mt-2 text-sm text-gray-500">
                            Este orçamento foi rejeitado e seu status não pode mais ser alterado.
                        </p>

                        @elseif ($orcamento->orc_status === 'aprovado')

                        <p class="mt-2 text-sm text-orange-600">
                            Um orçamento aprovado só pode ser finalizado ou rejeitado.
                        </p>

                        @elseif ($valorOrcamento <= 0)

                            <p class="mt-2 text-sm text-orange-600">
                            Este orçamento possui valor total igual a R$ 0,00 e não pode ser aprovado.
                            </p>

                            @endif
                    </div>

                    {{-- Códigos --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        {{-- Código Interno --}}
                        <div>
                            <label for="orc_cod_interno" class="block text-sm font-medium mb-1">
                                Código Interno
                            </label>

                            <input type="text"
                                name="orc_cod_interno"
                                id="orc_cod_interno"
                                maxlength="60" placeholder="Código Interno"
                                value="{{ old('orc_cod_interno', $orcamento->orc_cod_interno) }}"
                                class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300">

                            @error('orc_cod_interno')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>


                        {{-- Código da Fábrica --}}
                        <div>
                            <label for="orc_cod_fabrica" class="block text-sm font-medium mb-1">
                                Código da Fábrica
                            </label>

                            <input type="text"
                                name="orc_cod_fabrica"
                                id="orc_cod_fabrica"
                                maxlength="60"
                                placeholder="Código Fábrica"
                                value="{{ old('orc_cod_fabrica', $orcamento->orc_cod_fabrica) }}"
                                class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300">

                            @error('orc_cod_fabrica')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    {{-- Anotação Geral --}}
                    <div>
                        <label for="orc_anotacao_geral" class="block text-sm font-medium mb-1">
                            Anotação Geral
                        </label>
                        <textarea name="orc_anotacao_geral" id="orc_anotacao_geral"
                            rows="4"
                            class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                            placeholder="Digite uma anotação geral">{{ old('orc_anotacao_geral', $orcamento->orc_anotacao_geral) }}</textarea>
                    </div>

                    {{-- Motivo Rejeição --}}
                    <div id="motivoRejeicaoContainer" class="hidden">
                        <label for="orc_motivo_rejeicao" class="block text-sm font-medium mb-1">
                            Motivo da Rejeição
                        </label>
                        <textarea name="orc_motivo_rejeicao" id="orc_motivo_rejeicao"
                            rows="4"
                            class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                            placeholder="Descreva o motivo da rejeição...">{{ old('orc_motivo_rejeicao', $orcamento->orc_motivo_rejeicao) }}</textarea>
                    </div>

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
                <a href="{{ route('orcamento.index', ['cliente_orcamento_id' => $clienteSelecionado->id_co]) }}"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                    VOLTAR PARA A LISTA
                </a>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[data-status-anterior]');
        const dataInicioInput = document.getElementById('orc_data_inicio');
        const dataFimInput = document.getElementById('orc_data_fim');
        const statusSelect = document.getElementById('orc_status');
        const motivoContainer = document.getElementById('motivoRejeicaoContainer');
        const motivoInput = document.getElementById('orc_motivo_rejeicao');

        if (!form) {
            return;
        }

        function validarDatas() {
            if (!dataInicioInput || !dataFimInput) {
                return;
            }

            if (!dataInicioInput.value) {
                dataFimInput.removeAttribute('min');
                return;
            }

            const dataInicio = new Date(`${dataInicioInput.value}T00:00:00`);

            dataInicio.setDate(dataInicio.getDate() + 1);

            const dataMinima = [
                dataInicio.getFullYear(),
                String(dataInicio.getMonth() + 1).padStart(2, '0'),
                String(dataInicio.getDate()).padStart(2, '0')
            ].join('-');

            dataFimInput.min = dataMinima;

            if (dataFimInput.value && dataFimInput.value < dataMinima) {
                dataFimInput.value = '';
            }
        }

        function toggleMotivoRejeicao() {
            if (!statusSelect || !motivoContainer) {
                return;
            }

            const rejeitado = statusSelect.value === 'rejeitado';

            motivoContainer.classList.toggle('hidden', !rejeitado);

            if (motivoInput) {
                if (rejeitado) {
                    motivoInput.setAttribute('required', 'required');
                } else {
                    motivoInput.removeAttribute('required');
                    motivoInput.value = '';
                }
            }
        }

        validarDatas();
        toggleMotivoRejeicao();

        if (dataInicioInput) {
            dataInicioInput.addEventListener('change', validarDatas);
        }

        if (statusSelect) {
            statusSelect.addEventListener('change', toggleMotivoRejeicao);
        }

        form.addEventListener('submit', function(e) {
            const statusAnterior = form.dataset.statusAnterior;
            const novoStatus = statusSelect ?
                statusSelect.value :
                statusAnterior;

            if (
                dataInicioInput &&
                dataFimInput &&
                dataInicioInput.value &&
                dataFimInput.value
            ) {
                const dataInicio = new Date(`${dataInicioInput.value}T00:00:00`);
                const dataFim = new Date(`${dataFimInput.value}T00:00:00`);

                if (dataFim <= dataInicio) {
                    alert('A Data Fim deve ser maior que a Data Início.');
                    e.preventDefault();
                    dataFimInput.focus();
                    return;
                }
            }

            if (
                novoStatus === 'rejeitado' &&
                motivoInput &&
                !motivoInput.value.trim()
            ) {
                alert('Informe o motivo da rejeição.');
                e.preventDefault();
                motivoInput.focus();
                return;
            }

            if (
                novoStatus === 'aprovado' &&
                statusAnterior !== 'aprovado'
            ) {
                const confirmar = confirm(
                    'Você deseja colocar este orçamento como APROVADO e ir para o módulo Financeiro?'
                );

                if (!confirmar) {
                    e.preventDefault();
                    return;
                }
            }

            if (
                novoStatus === 'finalizado' &&
                statusAnterior !== 'finalizado'
            ) {
                const confirmar = confirm(
                    'Você deseja colocar este orçamento como FINALIZADO?'
                );

                if (!confirmar) {
                    e.preventDefault();
                }
            }
        });
    });
</script>
@endpush
@endsection