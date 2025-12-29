@extends('layouts.app_financeiro')

@section('title', 'Fazer Acordo na Cobrança')

@section('content')

<div class="max-w-6xl mx-auto p-8 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-3 text-center">
        Fazer Acordo na Parcela
    </h1>

    <form action="{{ route('detalhes_cobranca.update', $detalhe->id_det_cobranca) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <input type="hidden" name="det_cobr_valor_parcela" value="{{ $detalhe->det_cobr_valor_parcela }}">
        <input type="hidden" name="det_cobr_status" value="{{ $detalhe->det_cobr_status }}">
        <input type="hidden" name="data_original" value="{{ $detalhe->det_cobr_data_venc }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-custom-dark-text mb-1">Valor da Parcela</label>
                <input type="text" id="valorOriginalInput" value="{{ $detalhe->det_cobr_valor_parcela }}" disabled
                    class="block w-full px-4 py-2 bg-gray-100 text-gray-600 rounded-md border border-gray-300">
            </div>

            <div>
                <label for="det_cobr_data_venc" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Nova Data de Vencimento
                </label>
                <input type="date" name="det_cobr_data_venc" id="det_cobr_data_venc"
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500"
                    min="{{ $detalhe->det_cobr_data_venc }}"
                    value="{{ old('det_cobr_data_venc', $detalhe->det_cobr_data_venc) }}" required>
            </div>

            <div>
                <label class="block text-sm font-medium text-custom-dark-text mb-1">Status da Parcela</label>
                <input type="text" value="{{ $detalhe->det_cobr_status }}" disabled
                    class="block w-full px-4 py-2 bg-gray-100 text-gray-600 rounded-md border border-gray-300">
            </div>
        </div>


    </form>

    {{-- Tabela de resumo --}}
    <div class="mt-10">
        <h2 class="text-xl font-bold mb-4 text-center">Resumo da Atualização</h2>
        <table class="min-w-full bg-white shadow rounded-md divide-y divide-gray-200" id="resumoTabela">
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
                    <td class="px-4 py-2" id="valorOriginal">R$ {{ number_format($detalhe->det_cobr_valor_parcela, 2, ',', '.') }}</td>
                    <td class="px-4 py-2" id="dataVencOriginal">{{ \Carbon\Carbon::parse($detalhe->det_cobr_data_venc)->format('d/m/Y') }}</td>
                    <td class="px-4 py-2" id="novaDataVenc">{{ \Carbon\Carbon::parse(old('det_cobr_data_venc', $detalhe->det_cobr_data_venc))->format('d/m/Y') }}</td>
                    <td class="px-4 py-2" id="diasAtraso">0</td>
                    <td class="px-4 py-2" id="multa">R$ 0,00</td>
                    <td class="px-4 py-2" id="juros">R$ 0,00</td>
                    <td class="px-4 py-2 font-bold text-red-600" id="valorTotal">R$ {{ number_format($detalhe->det_cobr_valor_parcela, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="flex justify-center mt-8">
        <form action="{{ route('detalhes_cobranca.update', $detalhe->id_det_cobranca) }}" method="POST" class="space-y-6" id="formAcordo">
            @csrf
            @method('PUT')

            <!-- Inputs ocultos para enviar os valores calculados -->
            <input type="hidden" name="det_cobr_valor_parcela" id="inputValorParcela" value="{{ $detalhe->det_cobr_valor_parcela }}">
            <input type="hidden" name="det_cobr_data_venc" id="inputDataVenc" value="{{ $detalhe->det_cobr_data_venc }}">
            <input type="hidden" name="det_cobr_status" id="inputStatus" value="{{ $detalhe->det_cobr_status }}">
        </form>

        <button type="button" id="btnAcordo"
            class="inline-flex justify-center py-2 px-4 shadow-sm text-sm font-medium rounded-md
                   text-white bg-button-edit-bg hover:bg-button-edit-hover transition">
            FAZER ACORDO DA COBRANÇA
        </button>
    </div>


    <div class="flex justify-center mb-8">
        <a href="{{ route('cobranca.index') }}"
            class="inline-flex justify-center py-3 px-8 shadow-sm text-base font-medium rounded-md bg-gray-300
               hover:bg-gray-400 text-custom-dark-text">
            VOLTAR PARA A LISTA
        </a>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataInput = document.getElementById('det_cobr_data_venc');
        const valorOriginal = parseFloat("{{ $detalhe->det_cobr_valor_parcela }}");

        const indiceMulta = parseFloat("{{ $jurosMulta->indice_multa ?? 2 }}");
        const indiceJuros = parseFloat("{{ $jurosMulta->indice_juros ?? 1 }}");

        const dataVencOriginal = new Date("{{ $detalhe->det_cobr_data_venc }}");

        const inputValorParcela = document.getElementById('inputValorParcela');
        const inputDataVenc = document.getElementById('inputDataVenc');
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

            document.getElementById('novaDataVenc').innerText = partes[2] + '/' + partes[1] + '/' + partes[0];
            document.getElementById('diasAtraso').innerText = diasAtraso;
            document.getElementById('multa').innerText = 'R$ ' + multa.toFixed(2).replace('.', ',');
            document.getElementById('juros').innerText = 'R$ ' + juros.toFixed(2).replace('.', ',');
            document.getElementById('valorTotal').innerText = 'R$ ' + valorTotal.toFixed(2).replace('.', ',');

            return {
                valorTotal,
                novaDataStr
            };
        }

        dataInput.addEventListener('change', atualizarTabela);
        atualizarTabela();

        btnAcordo.addEventListener('click', function() {
            const {
                valorTotal,
                novaDataStr
            } = atualizarTabela();

            // Atualiza os inputs do form antes do submit
            inputValorParcela.value = valorTotal.toFixed(2);
            inputDataVenc.value = novaDataStr;
            inputStatus.value = "Acordo";

            formAcordo.submit();
        });
    });
</script>


@endsection