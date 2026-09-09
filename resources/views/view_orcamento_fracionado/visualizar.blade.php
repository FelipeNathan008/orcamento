@extends('layouts.app_financeiro')

@section('title', 'Detalhes do Orçamento Fracionado')

@section('content')
<div class="max-w-6xl mx-auto mt-10 mb-4 text-right">
    {{-- Voltar para a lista de fracionados do orçamento principal --}}
    <a href="{{ route('orcamento.fracionado.index', $orcamento->orcamento_id_orcamento) }}"
        class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition-colors duration-300 transform hover:scale-105 mr-2">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Voltar
    </a>

    <a href="{{ route('orcamento.fracionado.edit', $orcamento->id_orcamento_fracionado) }}"
        class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition-colors duration-300 transform hover:scale-105 mr-2">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15.232 5.232l3.536 3.536m-2.036-5.036a4.5 4.5 0 016.364 6.364L10 18l-4 1 1-4 9.172-9.172z">
            </path>
        </svg>
        Editar
    </a>
</div>

<div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-md mt-10 mb-10 font-poppins">
    <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-gray-900 font-bai-jamjuree mb-6 border-b pb-4">
        Orçamento Fracionado # {{ $orcamento->id_orcamento_fracionado }}
        <span class="text-lg font-medium text-gray-500">
            (Fração {{ $orcamento->orc_fracao }} do Orçamento #{{ $orcamento->orcamento_id_orcamento }})
        </span>
    </h1>

    <div class="space-y-8">
        {{-- Seção de Dados do Cliente --}}
        <div class="border-b pb-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Dados do Cliente</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-2 text-gray-700">
                <div>
                    <p><strong class="text-gray-900">Nome:</strong> {{ $clienteOrcamento->clie_orc_nome }}</p>
                    <p><strong class="text-gray-900">Email:</strong> {{ $clienteOrcamento->clie_orc_email }}</p>
                    @if ($clienteOrcamento->clie_orc_tipo_doc == 'CPF')
                    <p><strong class="text-gray-900">CPF:</strong> {{ $clienteOrcamento->clie_orc_cpf }}</p>
                    @elseif ($clienteOrcamento->clie_orc_tipo_doc == 'CNPJ')
                    <p><strong class="text-gray-900">CNPJ:</strong> {{ $clienteOrcamento->clie_orc_cnpj }}</p>
                    @endif
                </div>
                <div>
                    @if ($clienteOrcamento->clie_orc_telefone)
                    <p><strong class="text-gray-900">Telefone:</strong> {{ $clienteOrcamento->clie_orc_telefone }}</p>
                    @endif
                    @if ($clienteOrcamento->clie_orc_celular)
                    <p><strong class="text-gray-900">Celular:</strong> {{ $clienteOrcamento->clie_orc_celular }}</p>
                    @endif
                    <p><strong class="text-gray-900">Endereço:</strong> {{ $clienteOrcamento->clie_orc_logradouro }},
                        {{ $clienteOrcamento->clie_orc_bairro }} -
                        {{ $clienteOrcamento->clie_orc_cidade }}/{{ $clienteOrcamento->clie_orc_uf }}, CEP
                        {{ $clienteOrcamento->clie_orc_cep }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Seção de Dados do Orçamento Fracionado --}}
        <div class="border-b pb-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Dados do Orçamento Fracionado</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-2 gap-x-4 text-gray-700">
                <p><strong class="text-gray-900">Cód. Fábrica:</strong> {{ $orcamento->orc_cod_fabrica ?: 'N/D' }}</p>
                <p><strong class="text-gray-900">Cód. Interno:</strong> {{ $orcamento->orc_cod_interno ?: 'N/D' }}</p>
                <p><strong class="text-gray-900">Status:</strong> {{ ucfirst($orcamento->orc_status) }}</p>
                <p><strong class="text-gray-900">Data de Início:</strong>
                    {{ $orcamento->orc_data_inicio->format('d/m/Y') }}
                </p>
                <p><strong class="text-gray-900">Data de Fim:</strong> {{ $orcamento->orc_data_fim->format('d/m/Y') }}
                </p>
                @php
                $quantidadeTotalItens = $orcamento->detalhesOrcamentoFracionado->sum(function ($detalhe) {
                return (int) ($detalhe->det_quantidade ?? 0);
                });
                @endphp

                <p>
                    <strong class="text-gray-900">Quantidade Total de Itens:</strong>
                    {{ $quantidadeTotalItens }}
                </p>
            </div>
        </div>
    </div>

    @if ($orcamento->detalhesOrcamentoFracionado->count() > 0)
    <div class="mt-8">
        <h2 class="text-2xl font-bold leading-tight text-gray-900 mb-6 border-b pb-2">
            Detalhes do Orçamento Fracionado
        </h2>

        {{-- Loop para exibir cada detalhe do orçamento fracionado --}}
        <div class="space-y-6">
            @foreach ($orcamento->detalhesOrcamentoFracionado as $detalhe)
            @php
            $quantidade = (int) ($detalhe->det_quantidade ?? 0);
            // Total dos produtos
            $totalProduto = $quantidade * (float) ($detalhe->det_valor_unit ?? 0);
            // Total das customizações para todas as unidades
            $totalCustomizacoesDetalhe = 0;
            foreach ($detalhe->customizacoes as $customizacao) {
            $totalCustomizacoesDetalhe +=
            $quantidade * (float) ($customizacao->cust_valor ?? 0);
            }
            // Subtotal do detalhe
            $subtotalDetalhe = $totalProduto + $totalCustomizacoesDetalhe;
            @endphp
            <div class="border p-6 rounded-lg shadow-sm bg-white hover:shadow-lg transition-shadow duration-300">
                <p class="mb-2">
                    <strong class="text-gray-900">Item:</strong> {{ $detalhe->det_cod }} - {{ $detalhe->det_categoria }}
                    - {{ $detalhe->det_modelo }} - {{ $detalhe->det_cor }} - {{ $detalhe->det_tamanho }} -
                    {{ $detalhe->det_genero }}
                </p>
                <p class="mb-2">
                    <strong class="text-gray-900">Características:</strong> {{ $detalhe->det_caract }}
                </p>
                <p class="mb-4">
                    <strong class="text-gray-900">Quantidade:</strong> {{ $detalhe->det_quantidade }} -
                    <strong class="text-gray-900">Valor Unitário:</strong> R$
                    {{ number_format($detalhe->det_valor_unit, 2, ',', '.') }}
                </p>

                {{-- Loop para exibir as customizações de cada detalhe --}}
                @if ($detalhe->customizacoes->count() > 0)
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <h3 class="text-lg font-bold mb-3 text-gray-800">Customizações @if ($detalhe->det_quantidade > 1) ({{ $detalhe->det_quantidade }} produtos) @endif</h3>
                    <div class="space-y-2">
                        @foreach ($detalhe->customizacoes as $customizacao)
                        <p class="text-sm text-gray-700">
                            <strong class="text-gray-900">Customização:</strong>

                            Tipo:
                            <span class="font-bold">
                                {{ $customizacao->cust_tipo }}
                            </span>
                            -

                            Local:
                            <span class="font-bold">
                                {{ $customizacao->cust_local }}
                            </span>
                            -

                            Posição:
                            <span class="font-bold">
                                {{ $customizacao->cust_posicao }}
                            </span>
                            -

                            Valor unitário:
                            <span class="font-bold">
                                R$ {{ number_format($customizacao->cust_valor, 2, ',', '.') }}
                            </span>

                            × {{ $detalhe->det_quantidade }} produtos =

                            <span class="font-bold">
                                R$
                                {{ number_format($customizacao->cust_valor * $detalhe->det_quantidade,2,',','.') }}
                            </span>
                        </p>
                        @endforeach
                    </div>
                </div>
                @endif
                <div class="mt-4 pt-4 border-t border-gray-200 text-right">
                    <p class="text-lg font-bold text-gray-800"><strong class="text-gray-900">Subtotal:</strong> R$
                        {{ number_format($subtotalDetalhe, 2, ',', '.') }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Aplicar Desconto -->
    <div class="border p-6 rounded-lg bg-gray-50 mt-6">
        <h3 class="text-lg font-bold mb-3 text-gray-800">Aplicar Desconto</h3>

        <form action="{{ route('orcamento.fracionado.desconto', $orcamento->id_orcamento_fracionado) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                <select name="orc_desconto_tipo" class="w-full border rounded p-2">
                    <option value="">Sem desconto</option>
                    <option value="valor" {{ $orcamento->orc_desconto_tipo === 'valor' ? 'selected' : '' }}>Valor (R$)</option>
                    <option value="percentual" {{ $orcamento->orc_desconto_tipo === 'percentual' ? 'selected' : '' }}>Percentual (%)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Valor</label>
                <input type="number" step="0.01" min="0" name="orc_desconto_valor"
                    value="{{ $orcamento->orc_desconto_valor }}" class="w-full border rounded p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Motivo</label>
                <input type="text" name="orc_desconto_motivo"
                    value="{{ $orcamento->orc_desconto_motivo }}" class="w-full border rounded p-2">
            </div>

            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Aplicar
            </button>
        </form>

        @error('orc_desconto_valor')
        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>

    @php
    $totalBrutoCalculado = 0;

    foreach ($orcamento->detalhesOrcamentoFracionado as $detalhe) {

    $quantidade = (int) ($detalhe->det_quantidade ?? 0);

    // Produtos
    $totalBrutoCalculado +=
    $quantidade * (float) ($detalhe->det_valor_unit ?? 0);

    // Customizações
    foreach ($detalhe->customizacoes as $customizacao) {

    $totalBrutoCalculado +=
    $quantidade * (float) ($customizacao->cust_valor ?? 0);
    }
    }

    // Calcula o desconto
    $valorDescontoCalculado = 0;

    if ($orcamento->orc_desconto_tipo === 'percentual') {

    $valorDescontoCalculado =
    $totalBrutoCalculado *
    ((float) ($orcamento->orc_desconto_valor ?? 0) / 100);

    } elseif ($orcamento->orc_desconto_tipo === 'valor') {

    $valorDescontoCalculado =
    (float) ($orcamento->orc_desconto_valor ?? 0);
    }

    // Evita desconto maior que o próprio orçamento
    $valorDescontoCalculado = min(
    $valorDescontoCalculado,
    $totalBrutoCalculado
    );

    // Total final
    $totalComDescontoCalculado =
    $totalBrutoCalculado - $valorDescontoCalculado;
    @endphp
    <div class="mt-6 text-right space-y-1">
        <p class="text-gray-700">
            Subtotal:
            R$ {{ number_format($totalBrutoCalculado, 2, ',', '.') }}
        </p>
        @if ($valorDescontoCalculado > 0)
        <p class="text-red-600">
            Desconto
            @if ($orcamento->orc_desconto_tipo === 'percentual')
            ({{ number_format($orcamento->orc_desconto_valor, 2, ',', '.') }}%)
            @endif
            :
            - R$
            {{ number_format($valorDescontoCalculado, 2, ',', '.') }}
        </p>
        @endif
        <p class="text-2xl font-extrabold text-gray-900">
            Total:
            R$ {{ number_format($totalComDescontoCalculado, 2, ',', '.') }}
        </p>
    </div>

    @else
    <p class="mt-8 text-gray-600 text-center text-xl">Este orçamento fracionado ainda não possui detalhes cadastrados.</p>
    @endif
</div>
@endsection