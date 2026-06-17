<style>
    h1#Titulo {
        text-align: center;
    }
</style>

<h1 id="Titulo">Fluxo de Caixa</h1>
<h2>
    @if($dataInicio && $dataFim)
        Período: {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }}
        até
        {{ \Carbon\Carbon::parse($dataFim)->format('d/m/Y') }}
    @elseif($dataInicio)
        Data: {{ \Carbon\Carbon::parse($dataInicio)->format('d/m/Y') }}
    @endif
</h2>

<table border="1" width="100%" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>Data</th>
            <th>Tipo/Num° doc</th>
            <th>Movimentação</th>
            <th>Valor</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($fluxos as $fluxo)
        <tr>
            <td>{{ \Carbon\Carbon::parse($fluxo->flu_data_despesa)->format('d/m/Y') }}</td>
            <td>{{ ($fluxo->tipo->tipo_flu_nome ?? 'N/A') . ($fluxo->flu_num_doc ? ' - ' . $fluxo->flu_num_doc : '') }}</td>
            <td>{{ $fluxo->movimentacao->mov_nome ?? 'N/A' }}</td>
            <td>R$ {{ number_format($fluxo->flu_valor, 2, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>