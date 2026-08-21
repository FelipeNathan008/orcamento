@extends('layouts.app_financeiro')

@section('title', 'Editar Customização (Fracionado)')

@section('content')

<div class="max-w-4xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Editar Customização — Fracionado #{{ $orcamentoFracionado->orc_fracao }}
    </h1>

    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">
        <h2 class="text-lg font-bold text-orange-700 mb-4">Produto</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
            <div>
                <p class="text-gray-600">Produto</p>
                <p class="font-semibold">
                    {{ $detalhe->produto->prod_cod ?? 'N/A' }} - {{ $detalhe->produto->prod_nome ?? 'N/A' }}
                </p>
            </div>
            <div>
                <p class="text-gray-600">Cliente</p>
                <p class="font-semibold">{{ $orcamento->clienteOrcamento->clie_orc_nome ?? 'N/A' }}</p>
            </div>
            <div>
                <p class="text-gray-600">Tamanho</p>
                <p class="font-semibold">{{ $detalhe->det_tamanho ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <x-alert-flash />

    <form action="{{ route('customizacao_fracionado.update', $customizacao->id_customizacao_fracionada) }}"
        method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <input type="hidden" name="detalhes_orcamento_fracionado_id" value="{{ $detalhe->id_det_fracionado }}">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-sm font-medium mb-1">Tipo</label>
                <input type="text" name="cust_tipo" value="{{ old('cust_tipo', $customizacao->cust_tipo) }}"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                @error('cust_tipo') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Local</label>
                <input type="text" name="cust_local" value="{{ old('cust_local', $customizacao->cust_local) }}"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                @error('cust_local') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Posição</label>
                <input type="text" name="cust_posicao" value="{{ old('cust_posicao', $customizacao->cust_posicao) }}"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                @error('cust_posicao') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Tamanho</label>
                <input type="text" name="cust_tamanho" value="{{ old('cust_tamanho', $customizacao->cust_tamanho) }}"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                @error('cust_tamanho') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Formatação</label>
                <input type="text" name="cust_formatacao" value="{{ old('cust_formatacao', $customizacao->cust_formatacao) }}"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                @error('cust_formatacao') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Valor</label>
                <input type="text" name="cust_valor"
                    value="{{ old('cust_valor', number_format($customizacao->cust_valor, 2, ',', '.')) }}"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md" required>
                @error('cust_valor') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Descrição</label>
                <textarea name="cust_descricao" rows="3"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md">{{ old('cust_descricao', $customizacao->cust_descricao) }}</textarea>
                @error('cust_descricao') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="block text-sm font-medium mb-1">Imagem</label>

                @if($customizacao->cust_imagem)
                    <div class="mb-3">
                        <img src="{{ asset('images_customizacoes/' . $customizacao->cust_imagem) }}"
                            class="w-24 h-24 object-cover rounded shadow">
                        <p class="text-xs text-gray-500 mt-1">Envie uma nova imagem para substituir a atual.</p>
                    </div>
                @endif

                <input type="file" name="cust_imagem" accept="image/*"
                    class="block w-full px-4 py-2 border border-gray-300 rounded-md">
                @error('cust_imagem') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

        </div>

        <div class="flex justify-center gap-3 mt-8">
            <button type="submit"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white hover:brightness-90 transition"
                style="background-color:#EA792D;">
                SALVAR ALTERAÇÕES
            </button>
            <a href="{{ route('customizacao_fracionado.index', ['id_det_fracionado' => $detalhe->id_det_fracionado]) }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition">
                CANCELAR
            </a>
        </div>

    </form>
</div>

@endsection