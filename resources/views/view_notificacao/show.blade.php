@extends('layouts.app_financeiro')

@section('title', 'Notificações da Parcela')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Notificações da Parcela
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('notificacao.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400">
                VOLTAR
            </a>

            <a href="{{ route('notificacao.create', ['id_det_forma' => $detalheForma->id_det_forma]) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white"
                style="background-color: #EA792D;">
                Cadastrar Notificação
            </a>

        </div>


    </div>

    <x-alert-flash />

    {{-- INFORMAÇÕES DA PARCELA --}}
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 shadow-sm mb-6">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações da Parcela
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-sm">

            <div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <p class="text-gray-600">ID Orcamento</p>
                        <p class="font-semibold">
                            {{ $detalheForma->formaPagamento?->financeiro?->orcamento?->id_orcamento ?? 'N/D' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Cód. Interno</p>
                        <p class="font-semibold">
                            {{ $detalheForma->formaPagamento?->financeiro?->orcamento?->orc_cod_interno ?? 'N/D' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-gray-600">Cód. Fábrica</p>
                        <p class="font-semibold">
                            {{ $detalheForma->formaPagamento?->financeiro?->orcamento?->orc_cod_fabrica ?? 'N/D' }}
                        </p>
                    </div>
                </div>
            </div>

            <div>
                <p class="text-gray-600">ID Financeiro</p>
                <p class="font-semibold text-gray-900">
                    {{ $detalheForma->formaPagamento?->financeiro?->id_fin ?? 'N/D' }}
                </p>
            </div>


            <div>
                <p class="text-gray-600">ID Parcela</p>
                <p class="font-semibold text-gray-900">
                    #{{ $detalheForma->id_det_forma }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold text-gray-900">
                    {{ $detalheForma->formaPagamento?->financeiro?->fin_nome_cliente ?? 'N/D' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Forma de Pagamento</p>
                <p class="font-semibold text-gray-900">
                    {{ $detalheForma->formaPagamento?->tipoPagamento?->tipo_plano_fin ?? 'N/D' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Valor</p>
                <p class="font-semibold text-gray-900">
                    R$ {{ number_format($detalheForma->det_forma_valor_parcela ?? 0, 2, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Vencimento</p>
                <p class="font-semibold text-gray-900">
                    {{ $detalheForma->det_forma_data_venc
                        ? \Carbon\Carbon::parse($detalheForma->det_forma_data_venc)->format('d/m/Y')
                        : 'N/D' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Status</p>
                <p class="font-semibold text-gray-900">
                    {{ $detalheForma->det_situacao ?? 'N/D' }}
                </p>
            </div>

        </div>

    </div>

    {{-- NOTIFICAÇÕES --}}
    <div class="w-full rounded-lg shadow-table-shadow-image overflow-x-auto">

        <div class="px-5 py-4 bg-table-header-bg flex justify-between items-center">

            <h2 class="text-lg font-bold text-white">
                Notificações
            </h2>

            <span class="text-sm text-white">
                {{ $detalheForma->notificacoes->count() }}
                {{ $detalheForma->notificacoes->count() == 1 ? 'notificação' : 'notificações' }}
            </span>

        </div>

        @if ($detalheForma->notificacoes->isEmpty())

        <div class="text-center py-8">
            <p class="text-gray-600">
                Nenhuma notificação cadastrada para esta parcela.
            </p>
        </div>

        @else

        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">
                        Tipo
                    </th>

                    <th class="px-6 py-3 text-left text-xs font-bold uppercase">
                        Descrição
                    </th>

                    <th class="px-4 py-3 text-center text-xs font-bold uppercase">
                        Ações
                    </th>

                </tr>

            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

                @foreach ($detalheForma->notificacoes as $notificacao)

                <tr class="hover:bg-gray-50 transition">

                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $notificacao->tipo_nome }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $notificacao->not_descricao }}
                    </td>

                    <td class="px-4 py-4 text-center">

                        <div class="flex justify-center items-center gap-2">

                            <a
                                href="{{ route('notificacao.edit', $notificacao->id_notificacao) }}"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover">
                                Editar
                            </a>

                            <form id="formExcluirNotificacao{{ $notificacao->id_notificacao }}"
                                action="{{ route('notificacao.destroy', $notificacao->id_notificacao) }}"
                                method="POST"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="abrirModal('modalExcluirNotificacao',
                                    () => document.getElementById('formExcluirNotificacao{{ $notificacao->id_notificacao }}').submit())"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-button-cancel-bg hover:bg-button-cancel-hover">
                                    Excluir

                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirNotificacao"
    titulo="Excluir notificação"
    mensagem="Deseja realmente apagar esta notificação?"
    textoConfirmar="Excluir" />
@endsection