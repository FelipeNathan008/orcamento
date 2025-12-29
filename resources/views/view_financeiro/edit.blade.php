@extends('layouts.app_financeiro')

{{-- Define o título da página usando o nome do cliente --}}
@section('title', 'Editar Financeiro: ' . $financeiro->fin_nome_cliente)

@section('content')
    {{-- Contêiner principal --}}
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">

        {{-- TÍTULO --}}
        <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">
            Editar Financeiro: {{ $financeiro->fin_nome_cliente }}
        </h1>

        {{-- FORMULÁRIO --}}
        <form id="editFinForm" action="{{ route('financeiro.update', $financeiro->id_fin) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- GRID PRINCIPAL --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- COLUNA ESQUERDA --}}
                <div>

                    {{-- Orçamento (FK) --}}
                    <div>
                        <label for="orcamento_id_orcamento" class="block text-sm font-medium text-custom-dark-text mb-1">
                            Orçamento (FK)
                        </label>

                        <select name="orcamento_id_orcamento" id="orcamento_id_orcamento"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500"
                            required>

                            <option value="">Selecione um orçamento</option>

                            @foreach ($orcamentos as $orc)
                                <option value="{{ $orc->id_orcamento }}"
                                    {{ old('orcamento_id_orcamento', $financeiro->orcamento_id_orcamento) == $orc->id_orcamento ? 'selected' : '' }}>
                                    {{ $orc->id_orcamento }} — {{ $orc->orc_nome ?? 'Sem nome' }}
                                </option>
                            @endforeach
                        </select>

                        @error('orcamento_id_orcamento')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ID Orçamento --}}
                    <div class="mt-6">
                        <label for="id_orcamento" class="block text-sm font-medium text-custom-dark-text mb-1">ID Orçamento</label>
                        <input type="number" name="id_orcamento" id="id_orcamento"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('id_orcamento', $financeiro->id_orcamento) }}"
                            placeholder="ID vinculado ao orçamento" required>

                        @error('id_orcamento')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ID Cliente --}}
                    <div class="mt-6">
                        <label for="id_cliente" class="block text-sm font-medium text-custom-dark-text mb-1">ID Cliente</label>
                        <input type="number" name="id_cliente" id="id_cliente"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('id_cliente', $financeiro->id_cliente) }}"
                            placeholder="ID do cliente" required>

                        @error('id_cliente')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                {{-- COLUNA DIREITA --}}
                <div>

                    {{-- Nome do Cliente --}}
                    <div>
                        <label for="fin_nome_cliente" class="block text-sm font-medium text-custom-dark-text mb-1">
                            Nome do Cliente
                        </label>
                        <input type="text" name="fin_nome_cliente" id="fin_nome_cliente"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500"
                            maxlength="90"
                            value="{{ old('fin_nome_cliente', $financeiro->fin_nome_cliente) }}"
                            placeholder="Nome completo" required>

                        @error('fin_nome_cliente')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Valor Total --}}
                    <div class="mt-6">
                        <label for="fin_valor_total" class="block text-sm font-medium text-custom-dark-text mb-1">
                            Valor Total (R$)
                        </label>
                        <input type="text" name="fin_valor_total" id="fin_valor_total"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500"
                            value="{{ old('fin_valor_total', number_format($financeiro->fin_valor_total, 2, ',', '.')) }}"
                            placeholder="0,00" required>

                        @error('fin_valor_total')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mt-6">
                        <label for="fin_status" class="block text-sm font-medium text-custom-dark-text mb-1">
                            Status
                        </label>
                        <select name="fin_status" id="fin_status"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 outline-none focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">Selecione</option>
                            <option value="Pendente" {{ old('fin_status', $financeiro->fin_status) == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="Pago" {{ old('fin_status', $financeiro->fin_status) == 'Pago' ? 'selected' : '' }}>Pago</option>
                            <option value="Atrasado" {{ old('fin_status', $financeiro->fin_status) == 'Atrasado' ? 'selected' : '' }}>Atrasado</option>
                        </select>

                        @error('fin_status')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

            </div> {{-- FIM GRID --}}

            {{-- BOTÃO ATUALIZAR --}}
            <div class="flex justify-center mt-8">
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover transition">
                    ATUALIZAR
                </button>
            </div>

        </form>
    </div>

    @push('scripts')
        {{-- jQuery --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

        {{-- Máscara de moeda --}}
        <script>
            $(document).ready(function () {
                // Formata moeda
                $('#fin_valor_total').on('input', function () {
                    let v = $(this).val().replace(/\D/g, '');

                    if (v.length === 0) {
                        $(this).val('');
                        return;
                    }

                    v = (v / 100).toFixed(2) + '';
                    v = v.replace('.', ',');
                    v = v.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');

                    $(this).val(v);
                });
            });
        </script>
    @endpush

@endsection
