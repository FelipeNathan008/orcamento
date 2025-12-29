<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Orçamento #{{ $orcamento->id_orcamento }}</title>
    <style>
        /* Adicione esta linha no início do CSS */
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
            border-radius: 0;
            box-shadow: none;
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
            /* Espaçamento interno das células */
            text-align: left;
            border-bottom: 1px solid #dfe6e9;
        }

        th {
            background-color: #34495e;
            color: #ffffff;
            font-weight: 700; /* Linha corrigida para dar consistência */
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
            /* Espaço antes da anotação */
            font-size: 12px;
            color: #34495e;
            line-height: 1.6;
        }

        .anotacao strong {
            font-weight: bold;
        }

        /* Nova classe para o espaçamento em branco */
        .spacer {
            height: 5px;
            /* Altura reduzida para o espaço */
            background-color: transparent;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="logo-img">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}"
                style="width: 300px; height: 100px; object-fit: contain;">
        </div>


        <h1 style="text-transform: uppercase;">ALPHAMEGA STORE - UNIFORMES PROFISSIONAIS</h1>

        <table>
            <thead>
                <tr>
                    <th colspan="2" class="section-title">Dados da Empresa</th>
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

        <table>
            <thead>
                <tr>
                    <th colspan="2" class="section-title">Dados do Cliente</th>
                    <th colspan="2" class="section-title">Dados do Orçamento</th>
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
                    <td>{{ $clienteOrcamento->clie_orc_telefone ?? $clienteOrcamento->clie_orc_celular }} |
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
                        <td>{{ $orcamento->detalhesOrcamento->count() }}</td>
                    </tr>
                @elseif ($clienteOrcamento->clie_orc_tipo_doc == 'CNPJ')
                    <tr>
                        <td><strong>CNPJ:</strong></td>
                        <td>{{ $clienteOrcamento->clie_orc_cnpj }}</td>
                        <td><strong>Qtd. Itens:</strong></td>
                        <td>{{ $orcamento->detalhesOrcamento->count() }}</td>
                    </tr>
                @endif
                <tr>
                    <td><strong>Endereço:</strong></td>
                    <td colspan="3">{{ $clienteOrcamento->clie_orc_logradouro }},
                        {{ $clienteOrcamento->clie_orc_bairro }} -
                        {{ $clienteOrcamento->clie_orc_cidade }}/{{ $clienteOrcamento->clie_orc_uf }}, CEP
                        {{ $clienteOrcamento->clie_orc_cep }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="spacer"></div>

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
                @php $totalGeral = 0; @endphp
                @foreach ($orcamento->detalhesOrcamento as $detalhe)
                    @php
                        $subtotalDetalhe = $detalhe->det_quantidade * $detalhe->det_valor_unit;
                        $customizacoesTexto = '';
                        foreach ($detalhe->customizacoes as $customizacao) {
                            $subtotalDetalhe += $customizacao->cust_valor;
                            $customizacoesTexto .= "Tipo: {$customizacao->cust_tipo} | Valor: R$ " . number_format($customizacao->cust_valor, 2, ',', '.') . "<br>";
                        }
                        $totalGeral += $subtotalDetalhe;
                    @endphp
                    <tr>
                        <td>{{ $detalhe->det_cod }} - {{ $detalhe->det_categoria }} - {{ $detalhe->det_modelo }} -
                            {{ $detalhe->det_cor }} - {{ $detalhe->det_tamanho }} - {{ $detalhe->det_genero }} -
                            {{ $detalhe->det_caract }}
                        </td>
                        <td class="align-right">{{ $detalhe->det_quantidade }}</td>
                        <td class="align-right">R$ {{ number_format($detalhe->det_valor_unit, 2, ',', '.') }}</td>
                        <td>{!! $customizacoesTexto !!}</td>
                        <td class="align-right">R$ {{ number_format($subtotalDetalhe, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="5" class="total">Total Geral: R$ {{ number_format($totalGeral, 2, ',', '.')}}</td>
                    
                </tr>
            </tfoot>
        </table>

        <div class="anotacao">
            <p><strong>Anotação Geral:</strong> {{ $orcamento->orc_anotacao_geral }}</p>
            <p><strong>Anotação Específica:</strong> {{ $orcamento->orc_anotacao_espec }}</p>
        </div>

    </div>
</body>
                        
</html>
