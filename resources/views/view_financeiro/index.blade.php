@extends('layouts.app_financeiro')

@section('title', 'Financeiro')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Registros Financeiros
        </h1>

        {{-- Botão para tela de Cobranças --}}
        <a href="{{ route('cobranca.index') }}"
            class="px-4 py-2 text-sm font-medium rounded-md text-white shadow-sm"
            style="background-color: #EA792D;"> Cobranças
        </a>
    </div>

    {{-- Sucesso --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- FILTROS --}}
    <form action="{{ route('financeiro.index') }}" method="GET" class="w-full mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 relative">

            {{-- Nome --}}
            <div class="relative">
                <input type="text" name="search_nome" placeholder="Pesquisar por Nome..."
                    value="{{ request('search_nome') }}"
                    class="w-full h-9 pl-10 pr-3 text-sm bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus">
                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4" viewBox="0 0 20 20">
                    <path d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414L11.05 12.89A6 6 0 012 8z" />
                </svg>
            </div>

            {{-- STATUS CORRIGIDOS --}}
            <div class="relative">
                <select name="search_status"
                    class="w-full h-9 pl-3 pr-3 text-sm bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus">

                    <option value="">Status...</option>

                    @php
                    $statusLista = [
                    'Aguardando pagamento',
                    'Pagamento realizado',
                    'Análise pedido',
                    'Pedido fábrica',
                    'Transportadora',
                    'Entregue'
                    ];
                    @endphp

                    @foreach ($statusLista as $st)
                    <option value="{{ $st }}" {{ request('search_status') == $st ? 'selected' : '' }}>
                        {{ $st }}
                    </option>
                    @endforeach

                </select>
            </div>

            {{-- Valor --}}
            <div class="relative">
                <input type="text" name="search_valor" placeholder="Pesquisar por Valor..."
                    value="{{ request('search_valor') }}"
                    class="w-full h-9 pl-10 pr-3 text-sm bg-white border border-custom-border-light rounded-md outline-none hover:border-custom-border-hover focus:border-custom-border-focus">

                <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4" viewBox="0 0 20 20">
                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16z" />
                </svg>
            </div>

            {{-- Botão limpar --}}
            @if(request()->filled('search_nome') || request()->filled('search_status') || request()->filled('search_valor'))
            <a href="{{ route('financeiro.index') }}"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 hover:text-gray-700">
                Limpar
            </a>
            @endif
        </div>
    </form>


    {{-- SE ESTIVER VAZIO --}}
    @if ($financeiro->isEmpty())
    <p class="text-gray-600 text-center py-8">Nenhum registro encontrado.</p>

    @else

    {{-- TABELA --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        ORÇAMENTO
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Cliente
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Valor Total
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                        Ações
                    </th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

                @foreach ($financeiro as $fin)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $fin->orcamento_id_orcamento }}
                    </td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $fin->fin_nome_cliente }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700">
                        R$ {{ number_format($fin->fin_valor_total, 2, ',', '.') }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $fin->fin_status }}
                    </td>

                    <td class="px-6 py-4 text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">

                            {{-- Botão Status --}}
                            <button
                                type="button"
                                class="status-btn px-2 py-1 text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700"
                                data-id="{{ $fin->id_fin }}">
                                Status
                            </button>

                            {{-- Forma Pagamento --}}
                            @if($fin->fin_status === 'Aguardando pagamento' || $fin->fin_status === 'Pagamento realizado')
                            <a href="{{ url('/forma_pagamento?' . $fin->id_fin) }}"
                                class="px-2 py-1 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                Forma Pagamento
                            </a>
                            @endif


                            @php
                            // Verifica se tem algum log com log_situacao = 0
                            $temStatusPendente = \App\Models\LogStatus::where('log_id_orcamento', $fin->orcamento_id_orcamento)
                            ->where('log_situacao', 0)
                            ->exists();
                            @endphp

                            {{-- Prosseguir Status --}}
                            @if($fin->fin_status !== 'Aguardando pagamento')
                            <form action="{{ route('financeiro.prosseguir', $fin->id_fin) }}"
                                method="POST"
                                onsubmit="return confirm('Tem certeza que deseja prosseguir o status?');">
                                @csrf

                                <button type="submit"
                                    class="px-2 py-1 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700"
                                    id="prosseguirBtn-{{ $fin->id_fin }}"
                                    data-pendente="{{ $temStatusPendente ? '1' : '0' }}">
                                    Prosseguir Status
                                </button>
                            </form>
                            @endif




                            {{-- Ver 
                            <a href="{{ route('financeiro.show', $fin->id_fin) }}"
                            class="px-2 py-1 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Ver
                            </a>

                            {{-- Excluir
                            <form action="{{ route('financeiro.destroy', $fin->id_fin) }}" method="POST"
                            onsubmit="return confirm('Tem certeza que deseja excluir este registro?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-2 py-1 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                Excluir
                            </button>
                            </form> --}}

                        </div>
                    </td>
                </tr>

                {{-- LINHA OCULTA STATUS --}}
                <tr id="status-{{ $fin->id_fin }}" class="hidden">
                    <td colspan="5" class="px-6 py-4 text-sm text-gray-700">

                        @if($fin->logs->isEmpty())
                        <p class="text-center text-gray-500">Nenhum status encontrado.</p>
                        @else
                        <div class="grid grid-cols-6 gap-4">
                            @foreach ($fin->logs as $log)
                            @php
                            $status = $log->status_mercadoria_id_status;
                            $situacao = $log->log_situacao;
                            $img = $status . $situacao . '.png';
                            @endphp

                            <div class="flex flex-col items-center">
                                <img src="/imagens_status/{{ $img }}" class="w-16 h-16 object-contain">
                                <p class="text-sm text-gray-700 mt-2">
                                    {{$log->log_nome_status}}
                                </p>
                            </div>
                            @endforeach
                        </div>
                        @endif

                    </td>
                </tr>

                @endforeach

            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.status-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = btn.dataset.id;

                    document.querySelectorAll('[id^="status-"]').forEach(function(row) {
                        if (row.id !== 'status-' + id) {
                            row.classList.add('hidden');
                        }
                    });

                    const row = document.getElementById('status-' + id);
                    if (row) row.classList.toggle('hidden');
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');

            document.querySelector('input[name="search_nome"]').addEventListener('input', function() {
                form.submit();
            });

            document.querySelector('select[name="search_status"]').addEventListener('change', function() {
                form.submit();
            });

            document.querySelector('input[name="search_valor"]').addEventListener('input', function() {
                form.submit();
            });
        });

        //validar se há outro status para desabilitar button
        document.addEventListener("DOMContentLoaded", function() {
            const botoes = document.querySelectorAll("button[id^='prosseguirBtn-']");

            botoes.forEach(btn => {
                const pendente = btn.getAttribute("data-pendente");

                if (pendente === "0") {
                    btn.disabled = true;
                    btn.classList.remove("bg-red-600", "hover:bg-red-700");
                    btn.classList.add("bg-gray-400", "cursor-not-allowed");
                }
            });
        });
    </script>


    @endif

</div>

@endsection