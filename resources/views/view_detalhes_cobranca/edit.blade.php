@extends('layouts.app_financeiro')

@section('title', 'Realizar Acordo da Parcela')

@section('content')
<div class="max-w-6xl mx-auto p-8 mb-10 font-poppins">
    <h1 class="text-3xl font-bold text-custom-dark-text mb-3 text-center">Realizar Acordo da Parcela</h1>

    @if($detalhe)
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">
        <h2 class="text-lg font-bold text-orange-700 mb-4">Informações da Cobrança</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-600">Data de Vencimento</p>
                <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($detalhe->det_cobr_data_venc)->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-gray-600">Valor da Parcela</p>
                <p class="font-semibold text-gray-900">R$ {{ number_format($detalhe->det_cobr_valor_parcela, 2, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-gray-600">Status</p>
                <p class="font-semibold text-gray-900">{{ $detalhe->det_cobr_status }}</p>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('detalhes_cobranca.update', $detalhe->id_det_cobranca) }}" method="POST" id="formAcordo">
        @csrf
        @method('PUT')
        <input type="hidden" name="desconto_multa" id="inputDescontoMultaHidden" value="">
        <input type="hidden" name="desconto_juros" id="inputDescontoJurosHidden" value="">
        <input type="hidden" name="det_cobr_valor_parcela" id="inputValorParcela" value="{{ $detalhe->det_cobr_valor_parcela }}">
        <input type="hidden" name="det_cobr_status" id="inputStatus" value="{{ $detalhe->det_cobr_status }}">
        <input type="hidden" name="data_original" value="{{ $detalhe->det_cobr_data_venc }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label for="det_cobr_data_venc" class="block text-sm font-medium text-custom-dark-text mb-1">Nova Data de Vencimento</label>
                <input type="date" name="det_cobr_data_venc" id="det_cobr_data_venc" class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500" min="{{ $detalhe->det_cobr_data_venc }}" value="{{ old('det_cobr_data_venc', $detalhe->det_cobr_data_venc) }}" required>
            </div>
        </div>

        <div class="mt-10">
            <h2 class="text-xl font-bold mb-4 text-center">Resumo da Atualização</h2>
            <div class="overflow-x-auto">
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
                            <td id="valorOriginal">R$ {{ number_format($detalhe->det_cobr_valor_parcela, 2, ',', '.') }}</td>
                            <td id="dataVencOriginal">{{ \Carbon\Carbon::parse($detalhe->det_cobr_data_venc)->format('d/m/Y') }}</td>
                            <td id="novaDataVenc">{{ \Carbon\Carbon::parse($detalhe->det_cobr_data_venc)->format('d/m/Y') }}</td>
                            <td id="diasAtraso">0</td>
                            <td id="multa">R$ 0,00</td>
                            <td id="juros">R$ 0,00</td>
                            <td id="valorTotal" class="font-bold text-red-600">R$ {{ number_format($detalhe->det_cobr_valor_parcela, 2, ',', '.') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Desconto de Juros e Multa --}}
        <div class="mt-8 bg-gray-50 border border-gray-200 rounded-lg p-6">

            <div class="mb-5">
                <h2 class="text-lg font-bold text-gray-800">
                    Desconto de Juros e Multa
                </h2>

                <p class="text-sm text-gray-600 mt-1">
                    Informe, se desejar, o valor que será descontado da multa e dos juros.
                    Os campos são opcionais.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Desconto da Multa --}}
                <div>
                    <label for="inputDescontoMulta"
                        class="block text-sm font-medium text-gray-700 mb-1">
                        Desconto na Multa
                    </label>

                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">
                            R$
                        </span>

                        <input type="number"
                            id="inputDescontoMulta"
                            step="0.01"
                            min="0"
                            value=""
                            placeholder="0,00"
                            class="block w-full pl-10 pr-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500">

                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        Desconto máximo:
                        <strong id="descontoMultaMaximo">R$ 0,00</strong>
                    </p>
                </div>


                {{-- Desconto dos Juros --}}
                <div>
                    <label for="inputDescontoJuros"
                        class="block text-sm font-medium text-gray-700 mb-1">
                        Desconto nos Juros
                    </label>

                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-500">
                            R$
                        </span>

                        <input type="number"
                            id="inputDescontoJuros"
                            step="0.01"
                            min="0"
                            value=""
                            placeholder="0,00"
                            class="block w-full pl-10 pr-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <p class="text-xs text-gray-500 mt-1">
                        Desconto máximo:
                        <strong id="descontoJurosMaximo">R$ 0,00</strong>
                    </p>
                </div>

            </div>
        </div>

        <div class="flex justify-center mt-8">
            <button id="btnAcordo" type="button" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">REALIZAR ACORDO DA PARCELA</button>
        </div>

        <div class="flex justify-center mb-8 mt-4">
            <a href="{{ route('cobranca.index') }}" class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">VOLTAR PARA A LISTA</a>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataInput = document.getElementById('det_cobr_data_venc'),
            valorOriginal = parseFloat("{{ $detalhe->det_cobr_valor_parcela }}"),
            indiceMulta = parseFloat("{{ $jurosMulta->indice_multa ?? 2 }}"),
            indiceJuros = parseFloat("{{ $jurosMulta->indice_juros ?? 1 }}"),
            dataVencOriginal = new Date("{{ $detalhe->det_cobr_data_venc }}"),
            inputValorParcela = document.getElementById('inputValorParcela'),
            inputStatus = document.getElementById('inputStatus'),
            inputDescontoMulta = document.getElementById('inputDescontoMulta'),
            inputDescontoJuros = document.getElementById('inputDescontoJuros'),
            inputDescontoMultaHidden = document.getElementById('inputDescontoMultaHidden'),
            inputDescontoJurosHidden = document.getElementById('inputDescontoJurosHidden'),
            btnAcordo = document.getElementById('btnAcordo'),
            formAcordo = document.getElementById('formAcordo');

        function formatarMoeda(valor) {
            return 'R$ ' + valor.toFixed(2).replace('.', ',');
        }

        function calcularDiasAtraso(d1, d2) {
            const utc1 = Date.UTC(d1.getFullYear(), d1.getMonth(), d1.getDate()),
                utc2 = Date.UTC(d2.getFullYear(), d2.getMonth(), d2.getDate());
            return Math.max(0, Math.floor((utc2 - utc1) / (1000 * 60 * 60 * 24)));
        }

        function atualizarTabela() {
            const novaDataStr = dataInput.value;
            if (!novaDataStr) return;

            const partes = novaDataStr.split('-'),
                novaData = new Date(Date.UTC(partes[0], partes[1] - 1, partes[2])),
                diasAtraso = calcularDiasAtraso(dataVencOriginal, novaData);
            const multaCalculada = valorOriginal * (indiceMulta / 100),
                jurosCalculado = valorOriginal * (indiceJuros / 100 / 30 * diasAtraso);
            let descontoMulta = Math.max(0, parseFloat(inputDescontoMulta.value) || 0),
                descontoJuros = Math.max(0, parseFloat(inputDescontoJuros.value) || 0);

            if (descontoMulta > multaCalculada) {
                descontoMulta = multaCalculada;
                inputDescontoMulta.value = descontoMulta.toFixed(2);
            }
            if (descontoJuros > jurosCalculado) {
                descontoJuros = jurosCalculado;
                inputDescontoJuros.value = descontoJuros.toFixed(2);
            }

            const multaFinal = Math.max(0, multaCalculada - descontoMulta),
                jurosFinal = Math.max(0, jurosCalculado - descontoJuros),
                valorTotal = valorOriginal + multaFinal + jurosFinal;

            document.getElementById('descontoMultaMaximo').innerText = formatarMoeda(multaCalculada);
            document.getElementById('descontoJurosMaximo').innerText = formatarMoeda(jurosCalculado);
            document.getElementById('novaDataVenc').innerText = `${partes[2]}/${partes[1]}/${partes[0]}`;
            document.getElementById('diasAtraso').innerText = diasAtraso;
            document.getElementById('multa').innerText = formatarMoeda(multaFinal);
            document.getElementById('juros').innerText = formatarMoeda(jurosFinal);
            document.getElementById('valorTotal').innerText = formatarMoeda(valorTotal);

            return {
                valorTotal,
                novaDataStr,
                multaCalculada,
                jurosCalculado,
                descontoMulta,
                descontoJuros,
                multaFinal,
                jurosFinal
            };
        }

        inputDescontoMulta.addEventListener('input', atualizarTabela);
        inputDescontoJuros.addEventListener('input', atualizarTabela);
        dataInput.addEventListener('change', atualizarTabela);

        inputDescontoMulta.value = '';
        inputDescontoJuros.value = '';
        atualizarTabela();

        btnAcordo.addEventListener('click', function() {

            const resultado = atualizarTabela();

            if (!resultado) {
                return;
            }

            inputValorParcela.value =
                resultado.valorTotal.toFixed(2);

            inputStatus.value = 'Acordo';

            inputDescontoMultaHidden.value =
                resultado.descontoMulta.toFixed(2);

            inputDescontoJurosHidden.value =
                resultado.descontoJuros.toFixed(2);

            formAcordo.submit();
        });
    });
</script>

@endsection