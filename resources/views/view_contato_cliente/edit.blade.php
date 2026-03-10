@extends('layouts.app')

@section('title', 'Editar Contato')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

    <h1 class="text-3xl font-bold text-custom-dark-text mb-2 text-center">
        Editar Contato
    </h1>

    @if(isset($contatoCliente->clienteOrcamento))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Cliente
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

            <div>
                <p class="text-gray-600">Nome</p>
                <p class="font-semibold text-gray-900">
                    {{ $contatoCliente->clienteOrcamento->clie_orc_nome }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">E-mail</p>
                <p class="font-semibold text-gray-900">
                    {{ $contatoCliente->clienteOrcamento->clie_orc_email }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Celular</p>
                <p class="font-semibold text-gray-900">
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $contatoCliente->clienteOrcamento->clie_orc_celular)) }}
                </p>
            </div>

        </div>

    </div>
    @endif

    <form action="{{ route('contato_cliente.update', $contatoCliente->id_contato) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- ID DO CLIENTE FIXO (OCULTO) --}}
        <input type="hidden" name="cliente_orcamento_id_co"
            value="{{ $contatoCliente->cliente_orcamento_id_co }}">

        @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

            {{-- COLUNA ESQUERDA --}}
            <div class="space-y-6">

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Nome do Contato
                    </label>
                    <input type="text" name="cont_nome"
                        placeholder="Nome completo do contato"
                        class="w-full px-4 py-2 border rounded-md"
                        value="{{ old('cont_nome', $contatoCliente->cont_nome) }}" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Telefone (Opcional)
                    </label>
                    <input type="text" name="cont_telefone"
                        class="w-full px-4 py-2 border rounded-md"
                        placeholder="(XX) XXXX-XXXX"
                        value="{{ old('cont_telefone', $contatoCliente->cont_telefone) }}">
                </div>

            </div>

            {{-- COLUNA DIREITA --}}
            <div class="space-y-6">

                <div>
                    <label class="block text-sm font-medium mb-1">Celular</label>
                    <input type="text" name="cont_celular"
                        class="w-full px-4 py-2 border rounded-md"
                        placeholder="(XX) XXXXX-XXXX"
                        value="{{ old('cont_celular', $contatoCliente->cont_celular) }}" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">
                        Tipo de Contato
                    </label>
                    <select name="cont_tipo"
                        class="w-full px-4 py-2 border rounded-md"
                        required>
                        <option value="">Selecione</option>
                        <option value="administrativo"
                            {{ old('cont_tipo', $contatoCliente->cont_tipo) == 'administrativo' ? 'selected' : '' }}>
                            Administrativo
                        </option>
                        <option value="comercial"
                            {{ old('cont_tipo', $contatoCliente->cont_tipo) == 'comercial' ? 'selected' : '' }}>
                            Comercial
                        </option>
                        <option value="financeiro"
                            {{ old('cont_tipo', $contatoCliente->cont_tipo) == 'financeiro' ? 'selected' : '' }}>
                            Financeiro
                        </option>
                    </select>
                </div>

            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="cont_email"
                class="w-full px-4 py-2 border rounded-md"
                placeholder="contato@exemplo.com"
                value="{{ old('cont_email', $contatoCliente->cont_email) }}" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">
                Descrição (Opcional)
            </label>
            <textarea name="cont_descricao"
                placeholder="Informações adicionais sobre o contato, como cargo ou setor"
                rows="4"
                class="w-full px-4 py-2 border rounded-md">{{ old('cont_descricao', $contatoCliente->cont_descricao) }}</textarea>
        </div>

        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                ATUALIZAR
            </button>
        </div>

        {{-- BOTÃO VOLTAR --}}
        <div class="flex justify-center mb-8">
            <a href="{{ route('contato_cliente.index', ['cliente_orcamento' => $contatoCliente->cliente_orcamento_id_co]) }}"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR PARA A LISTA
            </a>
        </div>

    </form>
</div>

{{-- Apenas máscaras --}}
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(function() {
        $('input[name="cont_telefone"]').mask('(00) 0000-0000');
        $('input[name="cont_celular"]').mask('(00) 00000-0000');
    });
</script>
@endpush

@endsection