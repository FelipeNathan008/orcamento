@extends('layouts.app')

@section('title', 'Detalhes do Orçamento')

@section('content')

    <div class="max-w-6xl mx-auto mt-10 mb-4 text-right">
        <!-- Novo botão para Voltar -->
        <a href="{{ route('orcamento.index') }}"
            class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition-colors duration-300 transform hover:scale-105 mr-2">
            <!-- Icone de voltar SVG -->
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </a>
        <!-- Novo botão de editar -->
        <a href="{{ route('orcamento.edit', $orcamento->id_orcamento) }}"
            class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition-colors duration-300 transform hover:scale-105 mr-2">
            <!-- Icone de editar SVG -->
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a4.5 4.5 0 016.364 6.364L10 18l-4 1 1-4 9.172-9.172z">
                </path>
            </svg>
            Editar
        </a>
        <a href="{{ route('gerar_orcamento_pdf', $orcamento->id_orcamento) }}"
            class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition-colors duration-300 transform hover:scale-105 mr-2">
            <!-- Icone de download SVG -->
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2v-4a2 2 0 012-2h2m0-4a2 2 0 01-2-2V4a2 2 0 012-2h6a2 2 0 012 2v4a2 2 0 01-2 2h2m-4 4h.01M17 12h.01">
                </path>
            </svg>
            Gerar PDF
        </a>
        <a href="{{ route('orcamento_preview', $orcamento->id_orcamento) }}"
            class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition-colors duration-300 transform hover:scale-105">
            Visualizar Orçamento
        </a>
    </div>

    <div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-md mt-10 mb-10 font-poppins">
        <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-gray-900 font-bai-jamjuree mb-6 border-b pb-4">
            Orçamento #{{ $orcamento->id_orcamento }}
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

            {{-- Seção de Dados do Orçamento --}}
            <div class="border-b pb-6">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Dados do Orçamento</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-y-2 gap-x-4 text-gray-700">
                    <p><strong class="text-gray-900">ID do Orçamento:</strong> {{ $orcamento->id_orcamento }}</p>
                    <p><strong class="text-gray-900">Data de Início:</strong>
                        {{ $orcamento->orc_data_inicio->format('d/m/Y') }}</p>
                    <p><strong class="text-gray-900">Data de Fim:</strong> {{ $orcamento->orc_data_fim->format('d/m/Y') }}
                    </p>
                    <p><strong class="text-gray-900">Quantidade de Itens:</strong>
                        {{ $orcamento->detalhesOrcamento->count() }}</p>
                </div>
            </div>
        </div>

        @if ($orcamento->detalhesOrcamento->count() > 0)
            <div class="mt-8">
                <h2 class="text-2xl font-bold leading-tight text-gray-900 mb-6 border-b pb-2">
                    Detalhes do Orçamento
                </h2>
                {{-- Inicializa a variável para o total geral --}}
                @php
                    $totalGeral = 0;
                @endphp
                {{-- Loop para exibir cada detalhe do orçamento --}}
                <div class="space-y-6">
                    @foreach ($orcamento->detalhesOrcamento as $detalhe)
                        @php
                            // para somar o detalhe
                            $subtotalDetalhe = $detalhe->det_quantidade * $detalhe->det_valor_unit;

                            // para somar a customização
                            foreach ($detalhe->customizacoes as $customizacao) {
                                $subtotalDetalhe += $customizacao->cust_valor;
                            }

                            $totalGeral += $subtotalDetalhe;
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
                                    <h3 class="text-lg font-bold mb-3 text-gray-800">Customizações</h3>
                                    <div class="space-y-2">
                                        @foreach ($detalhe->customizacoes as $customizacao)
                                            <p class="text-sm text-gray-700">
                                                <strong class="text-gray-900">Customização:</strong>
                                                Tipo: <span class="font-bold">{{ $customizacao->cust_tipo }}</span> -
                                                Local: <span class="font-bold">{{ $customizacao->cust_local }}</span> -
                                                Posição: <span class="font-bold">{{ $customizacao->cust_posicao }}</span> -
                                                Valor: <span class="font-bold">R$
                                                    {{ number_format($customizacao->cust_valor, 2, ',', '.') }}</span>
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

            <div class="mt-8 text-right">
                <p class="text-3xl font-extrabold text-gray-900">Total Geral: R$ {{ number_format($totalGeral, 2, ',', '.') }}
                </p>
            </div>
        @else
            <p class="mt-8 text-gray-600 text-center text-xl">Este orçamento ainda não possui detalhes cadastrados.</p>
        @endif
    </div>
@endsection