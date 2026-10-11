@extends('layouts.app_financeiro')

@section('title', 'Realizar Acordo da Parcela')

@section('content')

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header
            title="Realizar Acordo da Parcela"
            :back-url="route('cobranca.index', $filtros)" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        @if($detalhe)
        <div class="px-6 sm:px-8 pt-6">
            <x-info-card
                title="Informações da cobrança"
                :name="'Parcela #' . $detalhe->id_det_forma"
                type="Cobrança"
                :fields="[
                    [
                        'label' => 'Data de Vencimento',
                        'value' => \Carbon\Carbon::parse($detalhe->det_forma_data_venc)->format('d/m/Y'),
                    ],
                    [
                        'label' => 'Valor Atual',
                        'value' => 'R$ ' . number_format($detalhe->det_forma_valor_parcela, 2, ',', '.'),
                        'bold' => true,
                    ],
                    [
                        'label' => 'Status',
                        'value' => $detalhe->det_situacao,
                        'break' => true,
                    ],
                ]" />
        </div>
        @endif

        <form action="{{ route('cobranca.update', $detalhe->id_det_forma) }}" method="POST" id="formAcordo" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            @method('PUT')

            @foreach($filtros as $nome => $valor)
            @if($valor !== null && $valor !== '')
            <input type="hidden" name="{{ $nome }}" value="{{ $valor }}">
            @endif
            @endforeach

            <input type="hidden" name="desconto_multa" id="inputDescontoMultaHidden" value="">
            <input type="hidden" name="desconto_juros" id="inputDescontoJurosHidden" value="">
            <input type="hidden" name="det_forma_valor_parcela" id="inputValorParcela" value="{{ $detalhe->det_forma_valor_parcela }}">
            <input type="hidden" name="det_situacao" id="inputStatus" value="{{ $detalhe->det_situacao }}">
            <input type="hidden" name="data_original" value="{{ $detalhe->det_forma_data_venc }}">

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do acordo</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Defina a nova data de vencimento da parcela.</p>
                    </div>
                </div>

                <div>
                    <label for="det_forma_data_venc" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                        Nova Data de Vencimento
                    </label>
                    <input type="date" name="det_forma_data_venc" id="det_forma_data_venc"
                        class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                        min="{{ $detalhe->det_forma_data_venc }}"
                        value="{{ old('det_forma_data_venc', $detalhe->det_forma_data_venc) }}"
                        required>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mt-6">
                <div class="px-5 py-3 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-sm font-semibold text-gray-700">Resumo da atualização</h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Valor Original</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Vencimento Original</th>
                                <th class="px-4 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Novo Vencimento</th>
                                <th class="px-4 py-3 text-center text-[10px] font-bold text-gray-500 uppercase tracking-wider">Dias de Atraso</th>
                                <th class="px-4 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Multa</th>
                                <th class="px-4 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Juros</th>
                                <th class="px-4 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Valor Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            <tr>
                                <td id="valorOriginal" class="px-4 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    R$ {{ number_format($detalhe->det_forma_valor_original, 2, ',', '.') }}
                                </td>
                                <td id="dataVencOriginal" class="px-4 py-4 text-sm text-gray-700 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($detalhe->det_forma_data_venc)->format('d/m/Y') }}
                                </td>
                                <td id="novaDataVenc" class="px-4 py-4 text-sm font-semibold text-gray-800 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($detalhe->det_forma_data_venc)->format('d/m/Y') }}
                                </td>
                                <td id="diasAtraso" class="px-4 py-4 text-sm text-gray-700 text-center whitespace-nowrap">0</td>
                                <td id="multa" class="px-4 py-4 text-sm text-gray-700 text-right whitespace-nowrap">R$ 0,00</td>
                                <td id="juros" class="px-4 py-4 text-sm text-gray-700 text-right whitespace-nowrap">R$ 0,00</td>
                                <td id="valorTotal" class="px-4 py-4 text-sm font-bold text-red-600 text-right whitespace-nowrap">
                                    R$ {{ number_format($detalhe->det_forma_valor_original, 2, ',', '.') }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6 mt-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.763-1.36 2.723-1.36 3.486 0l5.58 9.968c.75 1.34-.213 2.983-1.743 2.983H4.42c-1.53 0-2.493-1.643-1.743-2.983l5.58-9.968zM10 7a1 1 0 01.993.883L11 8v2a1 1 0 11-2 0V8a1 1 0 011-1zm0 6a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Desconto de juros e multa</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Os descontos são opcionais e limitados ao valor calculado.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="inputDescontoMulta" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Desconto na Multa
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">R$</span>
                            <input type="number" id="inputDescontoMulta" step="0.01" min="0" value="" placeholder="0,00"
                                class="w-full h-11 pl-10 pr-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <p class="text-xs text-gray-500 mt-1.5">
                            Desconto máximo: <strong id="descontoMultaMaximo">R$ 0,00</strong>
                        </p>
                    </div>

                    <div>
                        <label for="inputDescontoJuros" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Desconto nos Juros
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500">R$</span>
                            <input type="number" id="inputDescontoJuros" step="0.01" min="0" value="" placeholder="0,00"
                                class="w-full h-11 pl-10 pr-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <p class="text-xs text-gray-500 mt-1.5">
                            Desconto máximo: <strong id="descontoJurosMaximo">R$ 0,00</strong>
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="route('cobranca.index', $filtros)">
                    Voltar para a lista
                </x-secondary-button>

                <x-primary-button type="button" id="btnAcordo" class="px-6">
                    Realizar acordo da parcela
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

<x-modal-confirmacao
    id="modalRealizarAcordo"
    titulo="Confirmar acordo"
    mensagem="Deseja realmente realizar o acordo desta parcela?"
    textoConfirmar="Realizar acordo" />

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dataInput = document.getElementById('det_forma_data_venc');
        const valorOriginal = parseFloat("{{ $detalhe->det_forma_valor_original }}");
        const indiceMulta = parseFloat("{{ $jurosMulta->indice_multa ?? 2 }}");
        const indiceJuros = parseFloat("{{ $jurosMulta->indice_juros ?? 1 }}");
        const dataVencOriginal = new Date("{{ $detalhe->det_forma_data_venc }}");
        const inputValorParcela = document.getElementById('inputValorParcela');
        const inputStatus = document.getElementById('inputStatus');
        const inputDescontoMulta = document.getElementById('inputDescontoMulta');
        const inputDescontoJuros = document.getElementById('inputDescontoJuros');
        const inputDescontoMultaHidden = document.getElementById('inputDescontoMultaHidden');
        const inputDescontoJurosHidden = document.getElementById('inputDescontoJurosHidden');
        const btnAcordo = document.getElementById('btnAcordo');
        const formAcordo = document.getElementById('formAcordo');
        let enviando = false;

        function formatarMoeda(valor) {
            return 'R$ ' + valor.toFixed(2).replace('.', ',');
        }

        function formatarData(data) {
            const partes = data.split('-');
            return `${partes[2]}/${partes[1]}/${partes[0]}`;
        }

        function calcularDiasAtraso(d1, d2) {
            const utc1 = Date.UTC(d1.getFullYear(), d1.getMonth(), d1.getDate());
            const utc2 = Date.UTC(d2.getFullYear(), d2.getMonth(), d2.getDate());
            return Math.max(0, Math.floor((utc2 - utc1) / (1000 * 60 * 60 * 24)));
        }

        function atualizarTabela() {
            const novaDataStr = dataInput.value;
            if (!novaDataStr) return;

            const partes = novaDataStr.split('-');
            const novaData = new Date(Date.UTC(partes[0], partes[1] - 1, partes[2]));
            const diasAtraso = calcularDiasAtraso(dataVencOriginal, novaData);
            const multaCalculada = valorOriginal * (indiceMulta / 100);
            const jurosCalculado = valorOriginal * (indiceJuros / 100 / 30 * diasAtraso);
            let descontoMulta = Math.max(0, parseFloat(inputDescontoMulta.value) || 0);
            let descontoJuros = Math.max(0, parseFloat(inputDescontoJuros.value) || 0);

            if (descontoMulta > multaCalculada) {
                descontoMulta = multaCalculada;
                inputDescontoMulta.value = descontoMulta.toFixed(2);
            }

            if (descontoJuros > jurosCalculado) {
                descontoJuros = jurosCalculado;
                inputDescontoJuros.value = descontoJuros.toFixed(2);
            }

            const multaFinal = Math.max(0, multaCalculada - descontoMulta);
            const jurosFinal = Math.max(0, jurosCalculado - descontoJuros);
            const valorTotal = valorOriginal + multaFinal + jurosFinal;

            document.getElementById('descontoMultaMaximo').innerText = formatarMoeda(multaCalculada);
            document.getElementById('descontoJurosMaximo').innerText = formatarMoeda(jurosCalculado);
            document.getElementById('novaDataVenc').innerText = formatarData(novaDataStr);
            document.getElementById('diasAtraso').innerText = diasAtraso;
            document.getElementById('multa').innerText = formatarMoeda(multaFinal);
            document.getElementById('juros').innerText = formatarMoeda(jurosFinal);
            document.getElementById('valorTotal').innerText = formatarMoeda(valorTotal);

            return {
                valorTotal,
                novaDataStr,
                diasAtraso,
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
            if (enviando) return;

            const resultado = atualizarTabela();
            if (!resultado) return;

            const modal = document.getElementById('modalRealizarAcordo');
            const mensagem = modal.querySelector('p');

            mensagem.innerHTML =
                'Nova data: <strong>' + formatarData(resultado.novaDataStr) + '</strong><br>' +
                'Dias de atraso: <strong>' + resultado.diasAtraso + '</strong><br>' +
                'Valor original: <strong>' + formatarMoeda(valorOriginal) + '</strong><br>' +
                'Valor final: <strong>' + formatarMoeda(resultado.valorTotal) + '</strong>' +
                '<br><br>Deseja realmente realizar o acordo?';

            abrirModal('modalRealizarAcordo', function() {
                enviando = true;
                inputValorParcela.value = resultado.valorTotal.toFixed(2);
                inputStatus.value = 'Acordo';
                inputDescontoMultaHidden.value = resultado.descontoMulta.toFixed(2);
                inputDescontoJurosHidden.value = resultado.descontoJuros.toFixed(2);
                btnAcordo.disabled = true;
                btnAcordo.innerText = 'Realizando acordo...';
                btnAcordo.classList.add('opacity-70', 'cursor-not-allowed');
                formAcordo.submit();
            });
        });
    });
</script>
@endpush

@endsection