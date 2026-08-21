<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Orçamento # {{ $orcamento->id_orcamento}}</title>

    <style>
        * {
            box-sizing: border-box;
        }

        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            font-size: 13px;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        .container {
            width: 95%;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }

        h1 {
            text-align: center;
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .logo-img {
            width: 300px;
            height: 80px;
            object-fit: contain;
            display: block;
            margin: 0 auto 20px auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 10px;
            border: 1px solid #dfe6e9;
            border-radius: 8px;
            overflow: hidden;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
            border-bottom: 1px solid #dfe6e9;
        }

        th {
            background-color: #34495e;
            color: #ffffff;
            font-weight: 700;
            font-size: 14px;
            padding: 5px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:nth-child(even) {
            background-color: #f9fbfd;
        }

        .section-title {
            background-color: #2c3e50;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
        }

        .total-row {
            background-color: #f0f3f5;
            padding: 8px;
        }

        .total {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #EA792D;
            padding-right: 12px;
        }

        .anotacao {
            margin-top: 10px;
            font-size: 12px;
            color: #34495e;
            line-height: 1.6;
        }

        .anotacao strong {
            font-weight: bold;
        }

        .spacer {
            height: 5px;
            background-color: transparent;
        }

        .align-right {
            text-align: right;
        }

        .customizacao {
            font-size: 11px;
            line-height: 1.5;
        }

        .subtotal-detalhe {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="logo-img">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}"
                style="width: 300px; height: 100px; object-fit: contain;">
        </div>

        <h1 style="text-transform: uppercase;">
            ALPHAMEGA STORE - UNIFORMES PROFISSIONAIS
        </h1>

        <table>
            <thead>
                <tr>
                    <th colspan="2" class="section-title">
                        Dados da Empresa
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Nome:</strong></td>
                    <td>Alphamega Soluções Integradas em Engenharia</td>
                </tr>
                <tr>
                    <td><strong>Contato:</strong></td>
                    <td>(12) 99144-7771 | contato@alphamega.net.br</td>
                </tr>
                <tr>
                    <td><strong>CNPJ:</strong></td>
                    <td>55.483.263/0001-90</td>
                </tr>
            </tbody>
        </table>

        <div class="spacer"></div>

        @php
        $quantidadeTotalItens = $orcamento->detalhesOrcamento->sum(function ($detalhe) {
        return (int) ($detalhe->det_quantidade ?? 0);
        });
        @endphp

        <table>
            <thead>
                <tr>
                    <th colspan="2" class="section-title">
                        Dados do Cliente
                    </th>

                    <th colspan="2" class="section-title">
                        Dados do Orçamento
                    </th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td><strong>Nome:</strong></td>
                    <td>{{ $clienteOrcamento->clie_orc_nome }}</td>

                    <td><strong>Data de Início:</strong></td>
                    <td>{{ $orcamento->orc_data_inicio->format('d/m/Y') }}</td>
                </tr>

                <tr>
                    <td><strong>Contato:</strong></td>
                    <td>
                        {{ $clienteOrcamento->clie_orc_telefone ?? $clienteOrcamento->clie_orc_celular }}
                        |
                        {{ $clienteOrcamento->clie_orc_email }}
                    </td>

                    <td><strong>Validade da Proposta:</strong></td>
                    <td>{{ $orcamento->orc_data_fim->format('d/m/Y') }}</td>
                </tr>

                @if ($clienteOrcamento->clie_orc_tipo_doc == 'CPF')

                <tr>
                    <td><strong>CPF:</strong></td>
                    <td>{{ $clienteOrcamento->clie_orc_cpf }}</td>

                    <td><strong>Qtd. Itens:</strong></td>
                    <td>{{ $quantidadeTotalItens }}</td>
                </tr>

                @elseif ($clienteOrcamento->clie_orc_tipo_doc == 'CNPJ')

                <tr>
                    <td><strong>CNPJ:</strong></td>
                    <td>{{ $clienteOrcamento->clie_orc_cnpj }}</td>

                    <td><strong>Qtd. Itens:</strong></td>
                    <td>{{ $quantidadeTotalItens }}</td>
                </tr>

                @endif

                <tr>
                    <td><strong>Endereço:</strong></td>

                    <td colspan="3">
                        {{ $clienteOrcamento->clie_orc_logradouro }},
                        {{ $clienteOrcamento->clie_orc_bairro }} -
                        {{ $clienteOrcamento->clie_orc_cidade }}/{{ $clienteOrcamento->clie_orc_uf }},
                        CEP {{ $clienteOrcamento->clie_orc_cep }}
                    </td>
                </tr>

            </tbody>
        </table>
        <div class="spacer"></div>

        @php
        $totalBrutoCalculado = 0;
        @endphp

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qtd.</th>
                    <th>Valor Unitário</th>
                    <th>Customizações</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($orcamento->detalhesOrcamento as $detalhe)
                @php
                $quantidade = (int) ($detalhe->det_quantidade ?? 0);
                $valorUnitario = (float) ($detalhe->det_valor_unit ?? 0);
                $totalProduto = $quantidade * $valorUnitario;

                $totalCustomizacoesDetalhe = 0;
                $customizacoesTexto = '';

                foreach ($detalhe->customizacoes as $customizacao) {
                $valorCustomizacao = (float) ($customizacao->cust_valor ?? 0);
                $totalCustomizacao = $quantidade * $valorCustomizacao;

                $totalCustomizacoesDetalhe += $totalCustomizacao;

                $customizacoesTexto .=
                '<div class="customizacao">' .
                    '<strong>Tipo:</strong> ' . e($customizacao->cust_tipo) .
                    '<br>' .
                    '<strong>Local:</strong> ' . e($customizacao->cust_local) .
                    '<br>' .
                    '<strong>Posição:</strong> ' . e($customizacao->cust_posicao) .
                    '<br>' .
                    '<strong>Valor unitário:</strong> R$ ' .
                    number_format($valorCustomizacao, 2, ',', '.') .
                    '<br>' .
                    '<strong>' . $quantidade . ' produto(s):</strong> R$ ' .
                    number_format($totalCustomizacao, 2, ',', '.') .
                    '</div><br>';
                }

                $subtotalDetalhe = $totalProduto + $totalCustomizacoesDetalhe;
                $totalBrutoCalculado += $subtotalDetalhe;
                @endphp

                <tr>
                    <td>
                        {{ $detalhe->det_cod }} -
                        {{ $detalhe->det_categoria }} -
                        {{ $detalhe->det_modelo }} -
                        {{ $detalhe->det_cor }} -
                        {{ $detalhe->det_tamanho }} -
                        {{ $detalhe->det_genero }} -
                        {{ $detalhe->det_caract }}
                    </td>

                    <td class="align-right">
                        {{ $quantidade }}
                    </td>

                    <td class="align-right">
                        R$ {{ number_format($valorUnitario, 2, ',', '.') }}
                    </td>

                    <td>
                        {!! $customizacoesTexto ?: 'Nenhuma' !!}
                    </td>

                    <td class="align-right subtotal-detalhe">
                        R$ {{ number_format($subtotalDetalhe, 2, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>

            @php
            $valorDescontoCalculado = 0;

            if ($orcamento->orc_desconto_tipo === 'percentual') {
            $valorDescontoCalculado =
            $totalBrutoCalculado *
            ((float) ($orcamento->orc_desconto_valor ?? 0) / 100);
            } elseif ($orcamento->orc_desconto_tipo === 'valor') {
            $valorDescontoCalculado = (float) ($orcamento->orc_desconto_valor ?? 0);
            }

            $valorDescontoCalculado = min(
            $valorDescontoCalculado,
            $totalBrutoCalculado
            );

            $totalComDescontoCalculado =
            $totalBrutoCalculado - $valorDescontoCalculado;
            @endphp

            <tfoot>
                <tr class="total-row">
                    <td colspan="5" style="text-align: right; padding-right: 12px;">
                        Subtotal:
                        R$ {{ number_format($totalBrutoCalculado, 2, ',', '.') }}
                    </td>
                </tr>

                @if ($valorDescontoCalculado > 0)
                <tr class="total-row">
                    <td colspan="5" style="text-align: right; padding-right: 12px; color: #c0392b;">
                        Desconto

                        @if ($orcamento->orc_desconto_tipo === 'percentual')
                        (
                        {{ rtrim(
                                    rtrim(
                                        number_format(
                                            $orcamento->orc_desconto_valor,
                                            2,
                                            ',',
                                            '.'
                                        ),
                                        '0'
                                    ),
                                    ','
                                ) }}%)
                        @endif

                        :
                        - R$ {{ number_format($valorDescontoCalculado, 2, ',', '.') }}
                    </td>
                </tr>
                @endif

                <tr class="total-row">
                    <td colspan="5" class="total">
                        Total Geral:
                        R$ {{ number_format($totalComDescontoCalculado, 2, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <div class="anotacao">
            @if ($orcamento->orc_anotacao_geral)
            <p>
                <strong>Anotação Geral:</strong>
                {{ $orcamento->orc_anotacao_geral }}
            </p>
            @endif

            @if ($orcamento->orc_anotacao_espec)
            <p>
                <strong>Anotação Específica:</strong>
                {{ $orcamento->orc_anotacao_espec }}
            </p>
            @endif
        </div>

    </div>
</body>

</html>