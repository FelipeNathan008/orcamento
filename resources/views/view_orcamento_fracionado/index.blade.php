@extends('layouts.app_financeiro')

@section('title', 'Orçamento Fracionado')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- CABEÇALHO --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Orçamento Fracionado
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

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <p class="text-gray-600">ID</p>
                    <p class="font-semibold">
                        {{ $orcamento->id_orcamento }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-600">Cód. Interno</p>
                    <p class="font-semibold">
                        {{ $orcamento->orc_cod_interno ?: 'N/D' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-600">Cód. Fábrica</p>
                    <p class="font-semibold">
                        {{ $orcamento->orc_cod_fabrica ?: 'N/D' }}
                    </p>
                </div>
            </div>

            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/D' }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Status</p>
                <p class="font-semibold text-gray-900">
                    {{ $orcamento->orc_status }}
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

    <div id="image-warning-message" class="mt-2 text-sm text-yellow-600">
        <i class="fas fa-exclamation-triangle mr-1"></i>Não é possível Prosseguir Status do Financeiro enquanto os valores não conferirem.
    </div>

    @if($orcamento->fracionados->isNotEmpty())

    @php
    // Valor final do orçamento original
    $valorOriginal = (float) $orcamento->total_com_desconto;

    // Soma dos valores finais de todos os fracionados
    $valorFracionado = $orcamento->fracionados->sum(function ($fracionado) {
    return (float) $fracionado->total_com_desconto;
    });

    // Diferença entre o orçamento original e os fracionados
    $diferenca = $valorOriginal - $valorFracionado;

    // Considera fechado quando a diferença for menor que 1 centavo
    $valoresConferem = abs($diferenca) < 0.01;
        @endphp

        {{-- VALORES --}}
        <div class="mb-6 p-4 bg-gray-100 rounded-lg flex flex-col md:flex-row justify-around items-center text-center gap-4">

        <div>
            <span class="font-bold text-lg text-yellow-600">
                Valor Original
            </span>

            <span class="block text-gray-900 text-lg">
                R$ {{ number_format($valorOriginal, 2, ',', '.') }}
            </span>
        </div>

        <div>
            <span class="font-bold text-lg text-blue-600">
                Total Fracionado
            </span>

            <span class="block text-gray-900 text-lg">
                R$ {{ number_format($valorFracionado, 2, ',', '.') }}
            </span>
        </div>

        <div>
            <span class="font-bold text-lg {{ $valoresConferem ? 'text-green-600' : 'text-red-600' }}">
                Diferença
            </span>

            <span class="block text-gray-900 text-lg">
                R$ {{ number_format(abs($diferenca), 2, ',', '.') }}
            </span>
        </div>

</div>

@endif


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
                <th class="px-4 py-3 text-right text-xs font-medium text-white uppercase">Valor Total Atual</th>
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
                    {{ $fracionado->orc_cod_interno ?? 'N/D' }}
                </td>

                <td class="px-4 py-4 text-sm">
                    {{ $fracionado->orc_cod_fabrica ?? 'N/D' }}
                </td>

                <td class="px-4 py-4 text-sm">

                    @php
                    $statusClass = match($fracionado->orc_status) {
                    'entregue' => 'bg-green-400',
                    'pendente' => 'bg-yellow-400',
                    'enviado' => 'bg-blue-400',
                    default => 'bg-blue-400'
                    };
                    @endphp

                    <span class="relative inline-block px-3 py-1 font-semibold text-gray-900">
                        <span class="absolute inset-0 opacity-50 rounded-full {{ $statusClass }}"></span>
                        <span class="relative">{{ ucfirst($fracionado->orc_status) }}</span>
                    </span>

                </td>

                {{-- VALOR TOTAL --}}
                <td class="px-4 py-4 text-sm text-right font-semibold text-gray-900 whitespace-nowrap">
                    R$ {{ number_format($fracionado->total_com_desconto, 2, ',', '.') }}
                </td>


                <td class="px-2 py-4 text-center">

                    <div class="flex justify-center gap-2 flex-wrap">

                        <a href="{{ route('detalhes_orcamento_fracionado.index', $fracionado->id_orcamento_fracionado) }}"
                            class="relative inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">

                            @if($fracionado->detalhes_orcamento_fracionado_count > 0)
                            <span class="absolute -top-2 -right-2 inline-flex items-center justify-center w-4 h-4 text-xs font-bold text-white bg-orange-500 rounded-full">
                                {{ $fracionado->detalhes_orcamento_fracionado_count }}
                            </span>
                            @endif

                            Detalhes
                        </a>
                        <a href="{{ route('orcamento.fracionado.visualizar', $fracionado->id_orcamento_fracionado) }}"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600">
                            Gerar Orçamento
                        </a>

                        <a href="{{ route('orcamento.fracionado.edit',$fracionado->id_orcamento_fracionado) }}"
                            class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover">
                            Editar
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