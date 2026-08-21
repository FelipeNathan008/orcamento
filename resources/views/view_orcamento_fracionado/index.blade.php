@extends('layouts.app_financeiro')

@section('title', 'Orçamento Fracionado')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- CABEÇALHO --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Orçamento Fracionado #{{ $orcamento->orc_cod_interno }}
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('financeiro.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition">
                VOLTAR
            </a>

            {{-- CRIAR ORÇAMENTO FRACIONADO (cria apenas o "casco" do fracionado) --}}
            <form action="{{ route('orcamento.fracionado.store', ['orcamento' => $orcamento->id_orcamento]) }}"
                method="POST">
                @csrf
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white hover:brightness-90 transition"
                    style="background-color:#EA792D;">
                    Criar Orçamento Fracionado
                </button>
            </form>

        </div>

    </div>

    <x-alert-flash />

    {{-- INFORMAÇÕES DO ORÇAMENTO --}}
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Orçamento Principal
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">

            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/D' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Código Fábrica</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->orc_cod_fabrica ?? 'N/D'}}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Código Interno</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->orc_cod_interno ?? 'N/D'}}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Fracionados Criados</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->fracionados->count() }}
                </p>
            </div>

        </div>

    </div>

    {{-- LISTAGEM --}}
    <h2 class="text-2xl font-bold text-gray-800 mb-5">
        Orçamentos Fracionados
    </h2>

    @if($orcamento->fracionados->isEmpty())

    <p class="text-gray-600 text-center py-8">
        Nenhum orçamento fracionado criado.
    </p>

    @else

    <div class="w-full rounded-lg shadow-table-shadow-image overflow-x-auto">

        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Fração</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Cód. Interno</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Cód. Fábrica</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Status</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase">Ações</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

                @foreach($orcamento->fracionados as $fracionado)

                <tr class="hover:bg-gray-50 transition">

                    <td class="px-4 py-4 text-sm font-medium">
                        #{{ $fracionado->orc_fracao }}
                    </td>

                    <td class="px-4 py-4 text-sm">
                        {{ $fracionado->orc_cod_interno ?? 'Adicionar o cod na tabela fracionado' }}
                    </td>

                    <td class="px-4 py-4 text-sm">
                        {{ $fracionado->orc_cod_interno ?? 'N/D' }}
                    </td>

                    <td class="px-4 py-4 text-sm">

                        @php
                        $statusClass = match($fracionado->orc_status) {
                        'aprovado' => 'bg-green-400',
                        'pendente' => 'bg-yellow-400',
                        'finalizado' => 'bg-gray-400',
                        default => 'bg-blue-400'
                        };
                        @endphp

                        <span class="relative inline-block px-3 py-1 font-semibold text-gray-900">
                            <span class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}"></span>
                            <span class="relative">{{ ucfirst($fracionado->orc_status) }}</span>
                        </span>

                    </td>

                    <td class="px-2 py-4 text-center">

                        <div class="flex justify-center gap-2 flex-wrap">

                            {{-- AGORA LEVA PARA O DetalhesOrcamentoFracionadoController --}}
                            <a href="{{ route('detalhes_orcamento_fracionado.create', $fracionado->id_orcamento_fracionado) }}"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Cadastrar Detalhes
                            </a>

                            <a href="{{ route('detalhes_orcamento_fracionado.index', $fracionado->id_orcamento_fracionado) }}"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-teal-600 hover:bg-teal-700">
                                Ver Detalhes
                            </a>

                            <a href="{{ route('orcamento.fracionado.visualizar', $fracionado->id_orcamento_fracionado) }}"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600">
                                Visualizar
                            </a>

                            <form action="{{ route('orcamento.fracionado.destroy', $fracionado->id_orcamento_fracionado) }}"
                                method="POST"
                                onsubmit="return confirm('Tem certeza que deseja excluir este orçamento fracionado?');"
                                class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                    Excluir
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection