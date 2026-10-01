@extends('layouts.app_financeiro')

@section('title', 'Editar Orçamento Fracionado')

@section('content')

@php
$statusAtual = old('orc_status', $orcamentoFracionado->orc_status);
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
        class="space-y-6">

        @csrf
        @method('PUT')


        <div class="space-y-6">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

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
                        @if($orcamentoFracionado->orc_status !== 'pendente')
                        <span class="text-red-600">*</span>
                        @endif
                    </label>

                    <input
                        type="text"
                        name="orc_cod_fabrica"
                        id="orc_cod_fabrica"
                        maxlength="60"
                        placeholder="Código Fábrica"
                        value="{{ old('orc_cod_fabrica', $orcamentoFracionado->orc_cod_fabrica) }}"
                        class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                        @if($orcamentoFracionado->orc_status !== 'pendente') required @endif>

                    @error('orc_cod_fabrica')
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
                placeholder="Digite uma anotação geral">{{ old('orc_anotacao_geral', $orcamentoFracionado->orc_anotacao_geral) }}</textarea>

            @error('orc_anotacao_geral')
            <p class="mt-2 text-sm text-red-600">
                {{ $message }}
            </p>
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
        const status = document.getElementById('orc_status');

        if (!form) return;

        form.addEventListener('submit', (e) => {

            const statusAnterior = form.dataset.statusAnterior;
            const novoStatus = status?.value || statusAnterior;

            if (
                novoStatus === 'aprovado' &&
                statusAnterior !== 'aprovado' &&
                !confirm(
                    'Você deseja colocar este orçamento fracionado como APROVADO?'
                )
            ) {
                e.preventDefault();
                return;
            }

            if (
                novoStatus === 'finalizado' &&
                statusAnterior !== 'finalizado' &&
                !confirm(
                    'Você deseja colocar este orçamento fracionado como FINALIZADO?'
                )
            ) {
                e.preventDefault();
            }
        });

    });
</script>
@endpush

@endsection