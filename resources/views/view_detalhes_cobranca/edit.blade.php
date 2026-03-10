@extends('layouts.app_financeiro')

@section('title', 'Realizar Acordo da Parcela')

@section('content')

<div class="max-w-6xl mx-auto p-8 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-3 text-center">
        Realizar Acordo da Parcela
    </h1>

    {{-- Card Detalhes da Cobrança --}}
    @if($detalhe)
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações da Cobrança
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

            <div>
                <p class="text-gray-600">Data de Vencimento</p>
                <p class="font-semibold text-gray-900">
                    {{ \Carbon\Carbon::parse($detalhe->det_cobr_data_venc)->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Valor da Parcela</p>
                <p class="font-semibold text-gray-900">
                    R$ {{ number_format($detalhe->det_cobr_valor_parcela, 2, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Status</p>
                <p class="font-semibold text-gray-900">
                    {{ $detalhe->det_cobr_status }}
                </p>
            </div>

        </div>

    </div>
    @endif


    {{-- FORM PRINCIPAL --}}
    <form action="{{ route('detalhes_cobranca.update', $detalhe->id_det_cobranca) }}" method="POST" id="formAcordo">
        @csrf
        @method('PUT')

        {{-- Inputs hidden --}}
        <input type="hidden" name="det_cobr_valor_parcela" id="inputValorParcela" value="{{ $detalhe->det_cobr_valor_parcela }}">
        <input type="hidden" name="det_cobr_status" id="inputStatus" value="{{ $detalhe->det_cobr_status }}">
        <input type="hidden" name="data_original" value="{{ $detalhe->det_cobr_data_venc }}">

        {{-- Nova data --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div class="md:col-span-2">
                <label for="det_cobr_data_venc" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Nova Data de Vencimento
                </label>

                <input type="date"
                    name="det_cobr_data_venc"
                    id="det_cobr_data_venc"
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500"
                    min="{{ $detalhe->det_cobr_data_venc }}"
                    value="{{ old('det_cobr_data_venc', $detalhe->det_cobr_data_venc) }}"
                    required>
            </div>

        </div>


        {{-- Tabela resumo --}}
        <div class="mt-10">
            <h2 class="text-xl font-bold mb-4 text-center">Resumo da Atualização</h2>

            <table class="min-w-full bg-white shadow rounded-md divide-y divide-gray-200">

                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-4 py-2">Valor Original</th>
                        <th class="px-4 py-2">Data de Vencimento Original</th>
                        <th class="px-4 py-2">Nova Data de Vencimento</th>
                        <th class="px-4 py-2">Dias de Atraso</th>
                        <th class="px-4 py-2">Multa</th>
                        <th class="px-4 py-2">Juros</th>
                        <th class="px-4 py-2">Valor Total</th>
                    </tr>
                </thead>

                <tbody>
                    <tr class="text-center">

                        <td id="valorOriginal">
                            R$ {{ number_format($detalhe->det_cobr_valor_parcela, 2, ',', '.') }}
                        </td>

                        <td id="dataVencOriginal">
                            {{ \Carbon\Carbon::parse($detalhe->det_cobr_data_venc)->format('d/m/Y') }}
                        </td>

                        <td id="novaDataVenc">
                            {{ \Carbon\Carbon::parse($detalhe->det_cobr_data_venc)->format('d/m/Y') }}
                        </td>

                        <td id="diasAtraso">0</td>

                        <td id="multa">R$ 0,00</td>

                        <td id="juros">R$ 0,00</td>

                        <td id="valorTotal" class="font-bold text-red-600">
                            R$ {{ number_format($detalhe->det_cobr_valor_parcela, 2, ',', '.') }}
                        </td>

                    </tr>
                </tbody>

            </table>
        </div>


        {{-- Botões --}}
        <div class="flex justify-center mt-8">
            <button id="btnAcordo"
                type="button"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                REALIZAR ACORDO DA PARCELA
            </button>

        </div>
        <div class="flex justify-center mb-8 mt-4">
            <a href="{{ route('cobranca.index') }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>
    </form>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        const dataInput = document.getElementById('det_cobr_data_venc');
        const valorOriginal = parseFloat("{{ $detalhe->det_cobr_valor_parcela }}");

        const indiceMulta = parseFloat("{{ $jurosMulta->indice_multa ?? 2 }}");
        const indiceJuros = parseFloat("{{ $jurosMulta->indice_juros ?? 1 }}");

        const dataVencOriginal = new Date("{{ $detalhe->det_cobr_data_venc }}");

        const inputValorParcela = document.getElementById('inputValorParcela');
        const inputStatus = document.getElementById('inputStatus');
        const btnAcordo = document.getElementById('btnAcordo');
        const formAcordo = document.getElementById('formAcordo');



        function calcularDiasAtraso(d1, d2) {

            const utc1 = Date.UTC(d1.getFullYear(), d1.getMonth(), d1.getDate());
            const utc2 = Date.UTC(d2.getFullYear(), d2.getMonth(), d2.getDate());

            let diffDias = Math.floor((utc2 - utc1) / (1000 * 60 * 60 * 24));

            if (diffDias < 0) diffDias = 0;

            return diffDias;

        }
        function atualizarTabela() {

            const novaDataStr = dataInput.value;

            if (!novaDataStr) return;

            const partes = novaDataStr.split('-');
            const novaData = new Date(Date.UTC(partes[0], partes[1] - 1, partes[2]));
            const diasAtraso = calcularDiasAtraso(dataVencOriginal, novaData);
            const multa = valorOriginal * (indiceMulta / 100);
            const juros = valorOriginal * (indiceJuros / 100 / 30 * diasAtraso);
            const valorTotal = valorOriginal + multa + juros;


            document.getElementById('novaDataVenc').innerText =
                partes[2] + '/' + partes[1] + '/' + partes[0];

            document.getElementById('diasAtraso').innerText = diasAtraso;

            document.getElementById('multa').innerText =
                'R$ ' + multa.toFixed(2).replace('.', ',');

            document.getElementById('juros').innerText =
                'R$ ' + juros.toFixed(2).replace('.', ',');

            document.getElementById('valorTotal').innerText =
                'R$ ' + valorTotal.toFixed(2).replace('.', ',');


            return {
                valorTotal,
                novaDataStr
            };

        }

        dataInput.addEventListener('change', atualizarTabela);
        atualizarTabela();

        btnAcordo.addEventListener('click', function() {
            const {
                valorTotal
            } = atualizarTabela();
            inputValorParcela.value = valorTotal.toFixed(2);
            inputStatus.value = "Acordo";
            formAcordo.submit();
        });

    });
</script>
@endsection