@extends('layouts.app')

@section('title', 'Detalhes do Orçamento')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 mb-4 font-poppins">
    <div class="flex flex-wrap justify-end gap-2">
        <a href="{{ $urlVoltar }}"
            class="inline-flex items-center bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition-colors duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Voltar
        </a>

        <a href="{{ route('orcamento.edit', CryptHelper::encrypt($orcamento->id_orcamento)) }}"
            class="inline-flex items-center bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition-colors duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15.232 5.232 18.768 8.768m-2.036-5.036a4.5 4.5 0 0 1 6.364 6.364L10 18l-4 1 1-4 9.172-9.172z">
                </path>
            </svg>
            Editar
        </a>

        <a href="{{ route('gerar_orcamento_pdf', CryptHelper::encrypt($orcamento->id_orcamento)) }}"
            class="inline-flex items-center bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition-colors duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0-3-3m3 3 3-3m2 8H7a2 2 0 0 1-2-2v-4a2 2 0 0 1 2-2h2m0-4a4.5 4.5 0 0 1 2-2h6a2 2 0 0 1 2 2v4h2m-4 4h.01M17 12h.01">
                </path>
            </svg>
            Gerar PDF
        </a>

        <a href="{{ route('orcamento_preview', CryptHelper::encrypt($orcamento->id_orcamento)) }}"
            class="inline-flex items-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full shadow-lg transition-colors duration-300">
            Visualizar Orçamento
        </a>
    </div>
</div>

<div class="max-w-6xl mx-auto bg-white p-8 rounded-xl shadow-md mt-10 mb-10 font-poppins">

    <x-alert-flash />

    <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-gray-900 font-bai-jamjuree mb-6 border-b pb-4">
        Orçamento # {{ $orcamento->id_orcamento }}
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
                <p><strong class="text-gray-900">Cód. Fábrica:</strong> {{ $orcamento->orc_cod_fabrica ?: 'N/D' }}</p>
                <p><strong class="text-gray-900">Cód. Interno:</strong> {{ $orcamento->orc_cod_interno ?: 'N/D' }}</p>
                <p><strong class="text-gray-900">Data de Início:</strong>
                    {{ $orcamento->orc_data_inicio->format('d/m/Y') }}
                </p>
                <p><strong class="text-gray-900">Data de Fim:</strong> {{ $orcamento->orc_data_fim->format('d/m/Y') }}
                </p>
                @php
                $quantidadeTotalItens = $orcamento->detalhesOrcamento->sum(function ($detalhe) {
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

    @if ($orcamento->detalhesOrcamento->count() > 0)
    <div class="mt-8">
        <h2 class="text-2xl font-bold leading-tight text-gray-900 mb-6 border-b pb-2">
            Detalhes do Orçamento
        </h2>

        {{-- Loop para exibir cada detalhe do orçamento --}}
        <div class="space-y-6">
            @foreach ($orcamento->detalhesOrcamento as $detalhe)
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
                    <strong class="text-gray-900">Item:</strong> {{ $detalhe->det_cod }} - {{ $detalhe->det_categoria }} -
                    {{ $detalhe->det_categoria }}
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

    @if (!in_array($orcamento->orc_status, ['aprovado', 'rejeitado', 'finalizado']))

    <div class="border p-6 rounded-lg bg-gray-50 mt-6">
        <h3 class="text-lg font-bold mb-3 text-gray-800">Aplicar Desconto</h3>

        <form action="{{ route('orcamento.desconto', $orcamento->id_orcamento) }}" method="POST"
            id="formDesconto" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                <select name="orc_desconto_tipo" id="descontoTipo" class="w-full border rounded p-2">
                    <option value="" {{ !$orcamento->orc_desconto_tipo ? 'selected' : '' }}>Sem desconto</option>
                    <option value="valor" {{ $orcamento->orc_desconto_tipo === 'valor' ? 'selected' : '' }}>Valor (R$)</option>
                    <option value="percentual" {{ $orcamento->orc_desconto_tipo === 'percentual' ? 'selected' : '' }}>Percentual (%)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Valor</label>
                <input type="text" inputmode="decimal" id="descontoValorDisplay"
                    class="w-full border rounded p-2"
                    placeholder="0,00"
                    {{ !$orcamento->orc_desconto_tipo ? 'disabled' : '' }}>
                <input type="hidden" name="orc_desconto_valor" id="descontoValorReal"
                    value="{{ $orcamento->orc_desconto_valor }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Motivo</label>
                <input type="text" name="orc_desconto_motivo" id="descontoMotivo"
                    value="{{ $orcamento->orc_desconto_motivo }}" class="w-full border rounded p-2"
                    {{ !$orcamento->orc_desconto_tipo ? 'disabled' : '' }}>
            </div>

            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Aplicar
            </button>
        </form>

        @error('orc_desconto_tipo')
        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
        @enderror
        @error('orc_desconto_valor')
        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
        @enderror
        @error('orc_desconto_motivo')
        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
        @enderror
    </div>
    @endif

    <script>
        (function() {
            const tipoSelect = document.getElementById('descontoTipo');
            const valorDisplay = document.getElementById('descontoValorDisplay');
            const valorReal = document.getElementById('descontoValorReal');
            const motivoInput = document.getElementById('descontoMotivo');
            const form = document.getElementById('formDesconto');

            // Converte número (float) para string mascarada de acordo com o tipo
            function formatarParaExibicao(valor, tipo) {
                if (valor === null || valor === undefined || valor === '' || isNaN(valor)) return '';
                const num = parseFloat(valor);
                if (tipo === 'percentual') {
                    return num.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + '%';
                }
                return num.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
            }

            // Aplica máscara dinâmica enquanto o usuário digita (só dígitos viram centavos)
            function aplicarMascara(input, tipo) {
                let digits = input.value.replace(/\D/g, '');
                if (digits === '') {
                    valorReal.value = '';
                    input.value = '';
                    return;
                }

                let num = parseInt(digits, 10) / 100;

                if (tipo === 'percentual') {
                    if (num > 100) num = 100; // trava em 100%
                    input.value = num.toLocaleString('pt-BR', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + '%';
                } else {
                    input.value = num.toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }

                valorReal.value = num.toFixed(2);
            }

            function atualizarEstadoCampos() {
                const tipo = tipoSelect.value;
                const semDesconto = tipo === '';

                valorDisplay.disabled = semDesconto;
                motivoInput.disabled = semDesconto;

                if (semDesconto) {
                    valorDisplay.value = '';
                    valorReal.value = '';
                    motivoInput.value = '';
                } else {
                    // Reaplica a formatação correta ao trocar de tipo (ex: valor -> percentual)
                    aplicarMascara(valorDisplay, tipo);
                }
            }

            // Estado inicial (edição de um desconto já existente)
            if (tipoSelect.value) {
                valorDisplay.value = formatarParaExibicao(valorReal.value, tipoSelect.value);
            }

            tipoSelect.addEventListener('change', atualizarEstadoCampos);

            valorDisplay.addEventListener('input', function() {
                aplicarMascara(valorDisplay, tipoSelect.value);
            });

            // Garante consistência antes do submit (defesa extra além da validação do backend)
            form.addEventListener('submit', function(e) {
                const tipo = tipoSelect.value;
                if (tipo === '') {
                    valorReal.value = '';
                    motivoInput.value = '';
                    return; // sem desconto, backend vai limpar
                }

                const valor = parseFloat(valorReal.value);
                if (!valor || valor <= 0) {
                    e.preventDefault();
                    alert('Informe um valor de desconto maior que zero.');
                    valorDisplay.focus();
                    return;
                }

                if (!motivoInput.value.trim()) {
                    e.preventDefault();
                    alert('Informe o motivo do desconto.');
                    motivoInput.focus();
                }
            });
        })();
    </script>

    @php
    $totalBrutoCalculado = 0;

    foreach ($orcamento->detalhesOrcamento as $detalhe) {

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
    <p class="mt-8 text-gray-600 text-center text-xl">Este orçamento ainda não possui detalhes cadastrados.</p>
    @endif
</div>
@endsection