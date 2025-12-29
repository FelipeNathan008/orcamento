@extends('layouts.app_financeiro')

@section('title', 'Editar Detalhe de Forma de Pagamento')

@section('content')
<div class="max-w-4xl mx-auto p-8 mt-10 mb-10 font-poppins">

    {{-- Título --}}
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
        Editar Detalhe da Forma de Pagamento
    </h1>

    {{-- Mensagens de Erro do Laravel --}}
    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
        <ul class="mt-1 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('detalhes_forma_pag.update', $detalhe->id_det_forma) }}" method="POST" id="detForm" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Campo Forma de Pagamento --}}
            <div>
                <label for="id_forma_pag" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Forma de Pagamento
                </label>
                <select name="id_forma_pag" id="id_forma_pag"
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none 
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border border-gray-300"
                    required>

                    <option value="">Selecione</option>

                    @foreach ($formas as $forma)
                    <option value="{{ $forma->id_forma_pag }}"
                        {{ $detalhe->id_forma_pag == $forma->id_forma_pag ? 'selected' : '' }}>
                        {{ $forma->forma_nome }}
                    </option>
                    @endforeach
                </select>
                @error('id_forma_pag')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo Valor da Parcela --}}
            <div>
                <label for="det_forma_valor_parcela" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Valor da Parcela
                </label>
                <input type="text" name="det_forma_valor_parcela" id="det_forma_valor_parcela"
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md outline-none 
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border border-gray-300"
                    placeholder="0,00"
                    value="{{ old('det_forma_valor_parcela', $detalhe->det_forma_valor_parcela) }}"
                    required>
                @error('det_forma_valor_parcela')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo Data de Vencimento --}}
            <div>
                <label for="det_forma_data_venc" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Data de Vencimento
                </label>
                <input type="date" name="det_forma_data_venc" id="det_forma_data_venc"
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md outline-none 
                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border border-gray-300"
                    value="{{ old('det_forma_data_venc', $detalhe->det_forma_data_venc) }}"
                    required>
                @error('det_forma_data_venc')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Botão Salvar --}}
        <div class="flex justify-center mt-8">
            <button type="submit"
                class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium 
                       rounded-md text-white bg-button-save-bg hover:bg-button-save-hover 
                       focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                ATUALIZAR
            </button>
        </div>
    </form>
</div>

{{-- Botão Voltar --}}
<div class="flex justify-center mb-8">
    <a href="{{ route('detalhes_forma_pag.index') }}"
        class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm 
               text-base font-medium rounded-md text-custom-dark-text bg-gray-300 
               hover:bg-gray-400 transition">
        VOLTAR PARA A LISTA
    </a>
</div>

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(document).ready(function() {
        $('#det_forma_valor_parcela').mask('000.000.000,00', {
            reverse: true
        });
    });
</script>
@endpush
@endsection