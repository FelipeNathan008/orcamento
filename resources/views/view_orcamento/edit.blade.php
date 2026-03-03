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

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Opa!</strong>
        <span class="block sm:inline">Existem alguns problemas com seus dados.</span>
        <ul class="mt-3 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div>
        <form action="{{ route('orcamento.update', $orcamento->id_orcamento) }}" method="POST" class="space-y-6">
            @csrf
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
                        <select name="orc_status" id="orc_status"
                            class="block w-full px-4 py-2 bg-white rounded-md border border-gray-300"
                            required>
                            <option value="">Selecione</option>
                            <option value="pendente" {{ old('orc_status', $orcamento->orc_status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="para aprovacao" {{ old('orc_status', $orcamento->orc_status) == 'para aprovacao' ? 'selected' : '' }}>Para Aprovação</option>
                            <option value="aprovado" {{ old('orc_status', $orcamento->orc_status) == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                            <option value="rejeitado" {{ old('orc_status', $orcamento->orc_status) == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                            @if (!$financeiroPendente)
                            <option value="finalizado" {{ old('orc_status', $orcamento->orc_status) == 'finalizado' ? 'selected' : '' }}>
                                Finalizado
                            </option>
                            @endif
                        </select>
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

        const statusSelect = document.getElementById('orc_status');
        const motivoContainer = document.getElementById('motivoRejeicaoContainer');
        const motivoInput = document.getElementById('orc_motivo_rejeicao');

        function toggleMotivoRejeicao() {
            if (!statusSelect) return;

            if (statusSelect.value === 'rejeitado') {
                motivoContainer.classList.remove('hidden');
                motivoInput.setAttribute('required', 'required');
            } else {
                motivoContainer.classList.add('hidden');
                motivoInput.removeAttribute('required');
                motivoInput.value = '';
            }
        }

        toggleMotivoRejeicao();

        if (statusSelect) {
            statusSelect.addEventListener('change', toggleMotivoRejeicao);
        }

        const form = document.querySelector('form[action*="orcamento"]');

        if (form) {
            form.addEventListener('submit', function(e) {

                if (!statusSelect) return;

                const status = statusSelect.value;

                if (status === 'aprovado') {
                    const confirmar = confirm(
                        "Você deseja colocar como APROVADO e ir para o módulo Financeiro?"
                    );

                    if (!confirmar) {
                        e.preventDefault();
                    }
                }
            });
        }

    });
</script>
@endpush
@endsection