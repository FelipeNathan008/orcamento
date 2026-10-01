@extends('layouts.app_financeiro')

@section('title', 'Editar Notificação')

@section('content')

<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Editar Notificação
    </h1>

    <x-alert-flash />

    <form id="notificacaoForm"
        action="{{ route('notificacao.update', $notificacao->id_notificacao) }}"
        method="POST"
        class="space-y-6">

        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Informações da parcela --}}
            <div class="md:col-span-2">

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 shadow-sm">

                    <h2 class="text-lg font-bold text-orange-700 mb-4">
                        Informações da Parcela
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

                        <div>
                            <div class="grid grid-cols-3 gap-4">

                                <div>
                                    <p class="text-gray-600">ID Orçamento</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $notificacao->detalheFormaPag?->formaPagamento?->financeiro?->orcamento?->id_orcamento ?? 'N/D' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-gray-600">Cód. Interno</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $notificacao->detalheFormaPag?->formaPagamento?->financeiro?->orcamento?->orc_cod_interno ?: 'N/D' }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-gray-600">Cód. Fábrica</p>
                                    <p class="font-semibold text-gray-900">
                                        {{ $notificacao->detalheFormaPag?->formaPagamento?->financeiro?->orcamento?->orc_cod_fabrica ?: 'N/D' }}
                                    </p>
                                </div>

                            </div>
                        </div>

                        <div>
                            <p class="text-gray-600">Cliente</p>
                            <p class="font-semibold text-gray-900">
                                {{ $notificacao->detalheFormaPag?->formaPagamento?->financeiro?->fin_nome_cliente ?? 'Não informado' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-600">Valor</p>
                            <p class="font-semibold text-gray-900">
                                R$ {{ number_format($notificacao->detalheFormaPag?->det_forma_valor_parcela ?? 0, 2, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-600">Vencimento</p>
                            <p class="font-semibold text-gray-900">
                                {{ $notificacao->detalheFormaPag?->det_forma_data_venc
                                ? \Carbon\Carbon::parse($notificacao->detalheFormaPag->det_forma_data_venc)->format('d/m/Y')
                                : 'Não informado' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-600">Status</p>
                            <p class="font-semibold text-gray-900">
                                {{ $notificacao->detalheFormaPag?->det_situacao ?? 'Não informado' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-600">Tipo de Pagamento</p>
                            <p class="font-semibold text-gray-900">
                                {{ $notificacao->detalheFormaPag?->formaPagamento?->tipoPagamento?->tipo_plano_fin ?? 'Não informado' }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>

            {{-- Tipo da notificação --}}
            <div class="md:col-span-2">

                <label for="not_tipo"
                    class="block text-sm font-medium text-custom-dark-text mb-1">
                    Tipo da Notificação
                </label>

                <select name="not_tipo"
                    id="not_tipo"
                    required
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">

                    <option value="1" {{ old('not_tipo', $notificacao->not_tipo) == 1 ? 'selected' : '' }}>
                        Aviso Bancário
                    </option>

                    <option value="2" {{ old('not_tipo', $notificacao->not_tipo) == 2 ? 'selected' : '' }}>
                        E-mail / Telefone
                    </option>

                    <option value="3" {{ old('not_tipo', $notificacao->not_tipo) == 3 ? 'selected' : '' }}>
                        Carta Registrada
                    </option>

                    <option value="4" {{ old('not_tipo', $notificacao->not_tipo) == 4 ? 'selected' : '' }}>
                        Protesto
                    </option>

                </select>

                @error('not_tipo')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>

            {{-- Descrição --}}
            <div class="md:col-span-2">

                <label for="not_descricao"
                    class="block text-sm font-medium text-custom-dark-text mb-1">
                    Descrição da Notificação
                </label>

                <textarea name="not_descricao"
                    id="not_descricao"
                    rows="4"
                    required
                    placeholder="Descreva a notificação..."
                    class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">{{ old('not_descricao', $notificacao->not_descricao) }}</textarea>

                @error('not_descricao')
                <p class="mt-2 text-sm text-red-600">
                    {{ $message }}
                </p>
                @enderror

            </div>

        </div>

        {{-- Aviso antes de atualizar --}}
        <div class="mb-6 bg-yellow-50 border border-yellow-300 rounded-lg p-4">

            <div class="flex items-start">

                <div class="flex-shrink-0">

                    <svg class="w-5 h-5 text-yellow-600 mt-0.5"
                        fill="currentColor"
                        viewBox="0 0 20 20">

                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.763-1.36 2.723-1.36 3.486 0l5.58 9.968c.75 1.34-.213 2.983-1.743 2.983H4.42c-1.53 0-2.493-1.643-1.743-2.983l5.58-9.968zM10 7a1 1 0 01.993.883L11 8a1 1 0 11-2 0V8a1 1 0 011-1zm0 6a1 1 0 100-2 1 1 0 000 2z"
                            clip-rule="evenodd" />

                    </svg>

                </div>

                <div class="ml-3">

                    <h3 class="text-sm font-semibold text-yellow-800">
                        Atenção antes de atualizar
                    </h3>

                    <p class="mt-1 text-sm text-yellow-700">
                        Confira cuidadosamente o tipo e a descrição antes de
                        confirmar a atualização desta notificação.
                    </p>

                </div>

            </div>

        </div>

        <div class="flex justify-center mt-8">
            <button type="submit" id="btnAtualizarNotificacao"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                ATUALIZAR
            </button>
        </div>

        <div class="flex justify-center mb-8">
            <a href="{{ route('notificacao.show', $notificacao->detalheFormaPag?->id_det_forma) }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>


    </form>
</div>

<x-modal-confirmacao
    id="modalAtualizarNotificacao"
    titulo="Confirmar atualização"
    mensagem="A notificação será atualizada. Confira o tipo e a descrição antes de continuar. Deseja realmente atualizar esta notificação?"
    textoConfirmar="Atualizar" />
<script>
    const form = document.getElementById('notificacaoForm');
    const btnAtualizar = document.getElementById('btnAtualizarNotificacao');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        abrirModal('modalAtualizarNotificacao', function() {
            btnAtualizar.disabled = true;
            btnAtualizar.innerText = 'ATUALIZANDO...';
            btnAtualizar.classList.add('opacity-70', 'cursor-not-allowed');

            form.submit();
        });
    });
</script>

@endsection