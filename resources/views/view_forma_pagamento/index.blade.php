@extends('layouts.app_financeiro')

@section('title', 'Formas de Pagamento')

@section('content')
@php
$idFin = array_key_first(request()->query());
@endphp
{{-- RESUMO FINANCEIRO --}}
@if($idFin)
@php
$financeiroSelecionado = $financeiros->firstWhere('id_fin', $idFin);
$formasDoFinanceiro = $formasPagamento->where('financeiro_id_fin', $idFin);
$valorPago = $formasDoFinanceiro->sum('forma_valor');
$valorTotal = $financeiroSelecionado->fin_valor_total ?? 0;
$valorFaltante = max($valorTotal - $valorPago, 0);
@endphp
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Formas de Pagamento
        </h1>

        <div class="flex items-center gap-3">

            <a href="{{ route('financeiro.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400">
                VOLTAR
            </a>

            @if($valorFaltante > 0)
            <a href="{{ route('forma_pagamento.create', ['financeiro_id' => $id]) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white"
                style="background-color: #EA792D;">
                Nova Forma
            </a>
            @endif

        </div>

    </div>

    {{-- ALERTA --}}
    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if($idFin && isset($financeiroSelecionado))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-3">
            Informações do Financeiro Selecionado
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

            <div>
                <p class="text-gray-600">ID do Financeiro</p>
                <p class="font-semibold text-gray-900">
                    {{ $financeiroSelecionado->id_fin }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold text-gray-900">
                    {{ $financeiroSelecionado->fin_nome_cliente }}
                </p>
            </div>


            <div>
                <p class="text-gray-600">ID Orçamento</p>
                <p class="font-semibold text-gray-900">
                    {{ $financeiroSelecionado->orcamento_id_orcamento}}
                </p>
            </div>
        </div>

    </div>
    @endif
    <div class="mb-6 p-4 bg-gray-100 rounded-lg flex justify-around text-center">
        <div>
            <span class="font-bold text-lg text-red-600">Valor Total</span>
            <span class="block text-gray-900 text-lg">R$ {{ number_format($valorTotal, 2, ',', '.') }}</span>
        </div>
        <div>
            <span class="font-bold text-lg text-green-600">Valor Pago</span>
            <span class="block text-gray-900 text-lg">R$ {{ number_format($valorPago, 2, ',', '.') }}</span>
        </div>
        <div>
            <span class="font-bold text-lg text-yellow-600">Faltante</span>
            <span class="block text-gray-900 text-lg">R$ {{ number_format($valorFaltante, 2, ',', '.') }}</span>
        </div>
    </div>
    @endif
    {{-- SEM PAGAMENTOS --}}
    @if ($formasPagamento->isEmpty())

    <p class="text-gray-600 text-center py-8">
        Nenhuma forma de pagamento cadastrada.
    </p>

    @else

    {{-- TABELA --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">

        <table class="min-w-full divide-y divide-gray-200">

            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Tipo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Conta Bancária</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Prazo</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Data</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Valor</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase">Ações</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

                @foreach ($formasPagamento as $forma)

                <tr>

                    <td class="px-4 py-4 text-sm">
                        {{ $forma->id_forma_pag }}
                    </td>

                    <td class="px-4 py-4 text-sm">
                        {{ $forma->tipoPagamento?->tipo_plano_fin }}
                    </td>

                    <td class="px-4 py-4 text-sm">
                        @if($forma->conta)
                        {{ $forma->conta->conta_nome_banco }}
                        @else
                        N/A
                        @endif
                    </td>

                    <td class="px-4 py-4 text-sm">
                        {{ $forma->forma_prazo }}
                    </td>

                    <td class="px-4 py-4 text-sm">
                        {{ $forma->forma_data ? \Carbon\Carbon::parse($forma->forma_data)->format('d/m/Y') : 'N/D' }}
                    </td>

                    <td class="px-4 py-4 text-sm">
                        R$ {{ number_format($forma->forma_valor,2,',','.') }}
                    </td>


                    <td class="px-2 py-4 text-center">
                        <div class="flex justify-center items-center gap-2 flex-wrap">

                            @if($forma->forma_prazo === 'Parcelado')
                            <button
                                class="parcelas-btn px-2 py-1 text-xs font-medium rounded-md text-white bg-blue-500 hover:bg-blue-600"
                                data-id="{{ $forma->id_forma_pag }}">
                                Parcelas
                            </button>
                            @endif

                            <a href="{{ route('forma_pagamento.show', $forma->id_forma_pag) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Ver
                            </a>

                        </div>
                    </td>
                </tr>
                {{-- PARCELAS --}}
                <tr id="parcelas-{{ $forma->id_forma_pag }}" class="hidden">

                    <td colspan="9" class="bg-blue-50 p-4">
                        <h3 class="text-lg font-bold text-blue-800 mb-3">
                            Parcelas ({{ $forma->forma_qtd_parcela }})
                        </h3>
                        @if($forma->detalhes->isEmpty())

                        <p class="text-gray-600 text-sm">
                            Nenhuma parcela cadastrada.
                        </p>
                        @else
                        <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-md">

                            <thead class="bg-gray-200">
                                <tr>
                                    <th class="px-4 py-2 text-xs font-bold uppercase">Valor</th>
                                    <th class="px-4 py-2 text-xs font-bold uppercase">Vencimento</th>
                                    <th class="px-4 py-2 text-xs font-bold uppercase">Situação</th>
                                    <th class="px-4 py-2 text-xs font-bold uppercase">Pagamento</th>
                                    <th class="px-4 py-2 text-xs font-bold uppercase">Ações</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach($forma->detalhes as $parcela)

                                <tr>

                                    <td class="px-4 py-2 text-sm">
                                        R$ {{ number_format($parcela->det_forma_valor_parcela,2,',','.') }}
                                    </td>

                                    @php
                                    $vencimento = \Carbon\Carbon::parse($parcela->det_forma_data_venc);
                                    $hoje = \Carbon\Carbon::today();
                                    $diasAtraso = $vencimento->diffInDays($hoje, false);

                                    $classeVencimento = '';

                                    if(!in_array($parcela->det_situacao, ['Pago','Quitado'])){

                                    if($diasAtraso > 3){
                                    $classeVencimento = 'bg-red-200 text-red-800 font-semibold';
                                    }
                                    elseif($diasAtraso > 0){
                                    $classeVencimento = 'bg-yellow-200 text-yellow-800 font-semibold';
                                    }

                                    }
                                    @endphp

                                    <td class="px-4 py-2 text-sm {{ $classeVencimento }}">
                                        {{ $vencimento->format('d/m/Y') }}
                                    </td>
                                    @php
                                    $status = $parcela->det_situacao;

                                    if(
                                    $diasAtraso > 3 &&
                                    $status === 'Acordo' &&
                                    !in_array($status, ['Pago','Quitado'])
                                    ){
                                    $status = 'Inadimplencia';
                                    }
                                    @endphp
                                    @php
                                    $corStatus = match($status) {
                                    'Pago', 'Quitado' => 'bg-green-100 text-green-700',
                                    'Não pago' => 'bg-yellow-100 text-yellow-700',
                                    'Acordo' => 'bg-blue-100 text-blue-700',
                                    'Inadimplencia' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700'
                                    };
                                    @endphp

                                    <td class="px-4 py-2 text-sm">
                                        <span class="px-2 py-1 rounded-md text-xs font-semibold {{ $corStatus }}">
                                            {{ $status }} </span>
                                    </td>

                                    <td class="px-4 py-2 text-sm">
                                        {{ $parcela->det_forma_data_pagamento ? \Carbon\Carbon::parse($parcela->det_forma_data_pagamento)->format('d/m/Y') 
                                    : 'N/D' }}
                                    </td>

                                    <td class="px-4 py-2 text-sm text-center">
                                        <div class="flex justify-center items-center gap-2 flex-wrap">

                                            {{-- DAR BAIXA --}}
                                            @if(in_array($parcela->det_situacao, ['Não pago','Acordo','Inadimplencia']))
                                            <form method="POST">
                                                @csrf
                                                <button
                                                    type="button"
                                                    data-id="{{ $parcela->id_det_forma }}"
                                                    onclick="abrirModal(this)"
                                                    class="px-2 py-1 text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                                    Dar Baixa
                                                </button>
                                            </form>
                                            @endif

                                            {{-- VOLTAR --}}
                                            {{-- @if(in_array($parcela->det_situacao, ['Pago','Quitado']))
                                            <form action="{{ route('parcelas.voltarNaoPago', $parcela->id_det_forma) }}" method="POST"
                                            onsubmit="return confirm('Tem certeza que deseja Voltar Para Não Pago esta parcela?');">
                                            @csrf
                                            <button class="px-2 py-1 text-xs font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                                Voltar Para Não Pago
                                            </button>
                                            </form>
                                            @endif--}}

                                        </div>
                                    </td>

                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

</div>
<!-- MODAL -->
<div id="modalPagamento" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">

        <h2 class="text-lg font-bold mb-4">Data do Pagamento</h2>

        <form id="formBaixa" method="POST">
            @csrf


            <div class="mb-4">
                <label class="block text-sm text-gray-600">Selecione a data:</label>
                <input type="date" name="data_pagamento" id="data_pagamento" required class="w-full border rounded p-2">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="fecharModal()" class="bg-gray-400 text-white px-3 py-1 rounded">
                    Cancelar
                </button>

                <button type="button" onclick="confirmarData()" class="bg-green-600 text-white px-3 py-1 rounded">
                    Confirmar
                </button>
            </div>

        </form>
    </div>
</div>
<script>
    let parcelaId = null;

    function abrirModal(element) {
        const id = element.dataset.id;
        parcelaId = id;

        document.getElementById('modalPagamento').classList.remove('hidden');
    }

    function fecharModal() {
        document.getElementById('modalPagamento').classList.add('hidden');
    }

    function confirmarData() {
        const data = document.getElementById('data_pagamento').value;

        if (!data) {
            alert('Selecione uma data!');
            return;
        }

        if (!confirm('Confirma essa data de pagamento?')) {
            return;
        }

        const form = document.getElementById('formBaixa');
        form.action = `/parcelas/${parcelaId}/dar-baixa`;

        form.submit();
    }
</script>

{{-- SCRIPT --}}
<script>
    document.querySelectorAll('.parcelas-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id
            document.querySelectorAll('[id^="parcelas-"]').forEach(row => {
                if (row.id !== 'parcelas-' + id) {
                    row.classList.add('hidden')
                }
            })
            const row = document.getElementById('parcelas-' + id)
            if (row) {
                row.classList.toggle('hidden')
            }
        })
    })
</script>
@endsection