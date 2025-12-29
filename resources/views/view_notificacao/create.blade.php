{{-- resources/views/view_notificacao/create.blade.php --}}
@extends('layouts.app_financeiro')

@section('title', 'Cadastrar Nova Notificação')

@section('content')
{{-- Contêiner principal centralizado --}}
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">
    {{-- Título centralizado --}}
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Cadastro de Nova Notificação
    </h1>

    {{-- Formulário --}}
    <form action="{{ route('notificacao.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Grid principal --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Cobrança (FK) --}}
            <div class="md:col-span-2">

                {{-- FK real enviada no POST --}}
                <input type="hidden"
                    name="cobranca_id_cobranca"
                    value="{{ $cobranca?->id_cobranca }}">

                <label class="block text-sm font-medium text-custom-dark-text mb-1">
                    Cobrança Selecionada
                </label>

                <div class="bg-gray-100 border border-gray-300 rounded-md p-4 text-sm text-gray-800">
                    <p><strong>Cliente:</strong> {{ $cobranca?->cobr_cliente }}</p>
                    <p><strong>Tipo de Pagamento:</strong>
                        {{ $cobranca?->tipoPagamento->tipo_plano_fin ?? 'Não informado' }}
                    </p>
                    <p><strong>Status da Cobrança:</strong>
                        {{ $cobranca?->cobr_status }}
                    </p>
                </div>

                @error('cobranca_id_cobranca')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

            </div>



            {{-- Tipo da Notificação --}}
            <div class="md:col-span-2">
                <label for="not_tipo" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Tipo da Notificação
                </label>

                <select name="not_tipo"
                    id="not_tipo"
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md outline-none
               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    required>

                    @if ($proximoTipo == 1)
                    <option value="1" selected>Aviso Bancário</option>

                    @elseif ($proximoTipo == 2)
                    <option value="2" selected>E-mail / Telefone</option>

                    @elseif ($proximoTipo == 3)
                    <option value="3" selected>Carta Registrada</option>

                    @elseif ($proximoTipo == 4)
                    <option value="4" selected>Protesto</option>

                    @else
                    <option disabled selected>Todas as notificações já foram enviadas</option>
                    @endif

                </select>

                @error('not_tipo')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Descrição --}}
            <div class="md:col-span-2">
                <label for="not_descricao" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Descrição da Notificação
                </label>
                <textarea name="not_descricao"
                    id="not_descricao"
                    rows="4"
                    class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none
                                     focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    placeholder="Descreva a notificação..."
                    required>{{ old('not_descricao') }}</textarea>
                @error('not_descricao')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div> {{-- Fim do grid --}}

        {{-- Botão de envio centralizado --}}
        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white
                               bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2
                               focus:ring-button-edit-bg transition duration-150 ease-in-out">
                CADASTRAR NOTIFICAÇÃO
            </button>
        </div>
    </form>
</div>

{{-- Botão voltar --}}
<div class="flex justify-center mb-8">
    <a href="{{ route('notificacao.index') }}"
        class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium
                  rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
        VOLTAR PARA A LISTA
    </a>
</div>
@endsection