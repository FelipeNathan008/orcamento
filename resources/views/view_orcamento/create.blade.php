{{-- resources/views/view_orcamento/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Criar Novo Orçamento')

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Criar Novo Orçamento</h1>

    @if(isset($clienteSelecionado))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-4">
            Informações do Cliente
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">

            <div>
                <p class="text-gray-600">Nome</p>
                <p class="font-semibold text-gray-900">
                    {{ $clienteSelecionado->clie_orc_nome }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">E-mail</p>
                <p class="font-semibold text-gray-900">
                    {{ $clienteSelecionado->clie_orc_email }}
                </p>
            </div>

            <div>
                <p class="text-gray-600">Celular</p>
                <p class="font-semibold text-gray-900">
                    {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $clienteSelecionado->clie_orc_celular)) }}
                </p>
            </div>

        </div>
    </div>

    <x-alert-flash />


    <div>
        <form id="orcamentoForm" action="{{ route('orcamento.store') }}" method="POST" class="space-y-6">
            @csrf
            {{-- Campo hidden para salvar o id --}}
            <input type="hidden" name="cliente_orcamento_id_co"
                value="{{ $clienteSelecionado->id_co }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">

                {{-- PRIMEIRA COLUNA --}}
                <div class="space-y-6">

                    <!-- Campo Data Início -->
                    <div>
                        <label for="orc_data_inicio" class="block text-sm font-medium text-custom-dark-text mb-1">Data
                            Início</label>
                        <input type="date" name="orc_data_inicio" id="orc_data_inicio"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            value="{{ old('orc_data_inicio') }}" required>
                        @error('orc_data_inicio')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Anotação Específica dividido em 3 -->
                    <div>
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">Anotação Específica</label>

                        <div class="space-y-2">
                            <textarea name="anotacoes[]" rows="3" maxlength="1000"
                                class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300"
                                placeholder="ANOTAÇÕES">{{ old('anotacoes.0') }}</textarea>

                            <textarea name="anotacoes[]" rows="3" maxlength="1000"
                                class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300"
                                placeholder="OBSERVAÇÕES">{{ old('anotacoes.1') }}</textarea>

                            <textarea name="anotacoes[]" rows="3" maxlength="1000"
                                class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300"
                                placeholder="IMPORTANTE">{{ old('anotacoes.2') }}</textarea>
                        </div>

                        @error('anotacoes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div> {{-- Fim PRIMEIRA COLUNA --}}

                {{-- SEGUNDA COLUNA --}}
                <div class="space-y-6">
                    <!-- Campo Data Fim -->
                    <div>
                        <label for="orc_data_fim" class="block text-sm font-medium text-custom-dark-text mb-1">Data
                            Fim</label>
                        <input type="date" name="orc_data_fim" id="orc_data_fim"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            value="{{ old('orc_data_fim') }}" required>
                        @error('orc_data_fim')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Status -->
                    <div>
                        <label for="orc_status"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Status</label>
                        <select name="orc_status" id="orc_status"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="">Selecione um Status</option>
                            <option value="pendente" {{ old('orc_status') == 'pendente' ? 'selected' : '' }}>Pendente
                            </option>
                            <option value="para aprovacao" {{ old('orc_status') == 'para aprovacao' ? 'selected' : '' }}>
                                Para Aprovação</option>
                            <option value="rejeitado" {{ old('orc_status') == 'rejeitado' ? 'selected' : '' }}>Rejeitado
                            </option>

                        </select>
                        @error('orc_status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Códigos -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- Código da Fábrica -->
                        <div>
                            <label for="orc_cod_fabrica"
                                class="block text-sm font-medium text-custom-dark-text mb-1">
                                Código da Fábrica
                            </label>

                            <input type="text"
                                name="orc_cod_fabrica"
                                id="orc_cod_fabrica"
                                maxlength="60"
                                required
                                placeholder="Código da fábrica"
                                value="{{ old('orc_cod_fabrica') }}"
                                class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            @error('orc_cod_fabrica')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Código Interno -->
                        <div>
                            <label for="orc_cod_interno"
                                class="block text-sm font-medium text-custom-dark-text mb-1">
                                Código Interno
                            </label>

                            <input type="text"
                                name="orc_cod_interno"
                                id="orc_cod_interno"
                                maxlength="60"
                                required
                                placeholder="Código interno"
                                value="{{ old('orc_cod_interno') }}"
                                class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            @error('orc_cod_interno')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <!-- Campo Anotação Geral -->
                    <div>
                        <label for="orc_anotacao_geral"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Anotação Geral</label>
                        <textarea name="orc_anotacao_geral" id="orc_anotacao_geral" rows="4" maxlength="1000"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Digite uma anotação geral">{{ old('orc_anotacao_geral') }}</textarea>
                        @error('orc_anotacao_geral')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Campo Motivo da Rejeição (inicialmente oculto) -->
                    <div id="motivoRejeicaoContainer" class="hidden">
                        <label for="orc_motivo_rejeicao" class="block text-sm font-medium text-custom-dark-text mb-1">
                            Motivo da Rejeição
                        </label>
                        <textarea name="orc_motivo_rejeicao" id="orc_motivo_rejeicao" rows="4" maxlength="500"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300"
                            placeholder="Descreva o motivo da rejeição...">{{ old('orc_motivo_rejeicao') }}</textarea>
                        @error('orc_motivo_rejeicao')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>


            </div>
            {{-- BOTÕES --}}
            <div class="flex justify-center mt-8">
                <button type="submit" id="BtnSalvarOrcamento"
                    class="py-3 px-8 rounded-md text-white bg-button-save-bg hover:bg-button-save-hover">
                    SALVAR
                </button>
            </div>
            <div class="flex justify-center mb-8">

                <a href="{{ route('orcamento.index', ['cliente_orcamento_id' => $clienteSelecionado->id_co]) }}"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                    VOLTAR PARA A LISTA
                </a>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // BOTÃO SALVAR
        const form = document.getElementById('orcamentoForm');
        const btnSalvar = document.getElementById('BtnSalvarOrcamento');

        form.addEventListener('submit', function() {
            btnSalvar.disabled = true;
            btnSalvar.textContent = 'SALVANDO...';
            btnSalvar.classList.remove('hover:bg-button-save-hover');
            btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
        });


        // CÁLCULO AUTOMÁTICO DATA FIM (+15 DIAS)
        const dataInicioInput = document.getElementById('orc_data_inicio');
        const dataFimInput = document.getElementById('orc_data_fim');

        dataInicioInput.addEventListener('change', function() {

            if (this.value) {

                const dataInicio = new Date(this.value + 'T00:00:00');

                // Data mínima permitida para Data Fim = Data Início + 1 dia
                const dataMinimaFim = new Date(dataInicio);
                dataMinimaFim.setDate(dataMinimaFim.getDate() + 1);

                const anoMin = dataMinimaFim.getFullYear();
                const mesMin = String(dataMinimaFim.getMonth() + 1).padStart(2, '0');
                const diaMin = String(dataMinimaFim.getDate()).padStart(2, '0');

                dataFimInput.min = `${anoMin}-${mesMin}-${diaMin}`;

                // Preenche automaticamente com +15 dias
                const dataFim = new Date(dataInicio);
                dataFim.setDate(dataFim.getDate() + 15);

                const ano = dataFim.getFullYear();
                const mes = String(dataFim.getMonth() + 1).padStart(2, '0');
                const dia = String(dataFim.getDate()).padStart(2, '0');

                dataFimInput.value = `${ano}-${mes}-${dia}`;

            } else {

                dataFimInput.value = '';
                dataFimInput.removeAttribute('min');

            }

        });

        // Validação extra caso o usuário altere a data fim manualmente
        dataFimInput.addEventListener('change', function() {

            if (!dataInicioInput.value || !this.value) return;

            const dataInicio = new Date(dataInicioInput.value + 'T00:00:00');
            const dataFim = new Date(this.value + 'T00:00:00');

            if (dataFim <= dataInicio) {

                alert('A data final deve ser maior que a data inicial.');

                this.value = '';

                this.focus();
            }
        });


        // MOSTRAR / OCULTAR MOTIVO DA REJEIÇÃO
        const statusSelect = document.getElementById('orc_status');
        const motivoContainer = document.getElementById('motivoRejeicaoContainer');
        const motivoInput = document.getElementById('orc_motivo_rejeicao');

        function toggleMotivoRejeicao() {
            if (statusSelect.value === 'rejeitado') {
                motivoContainer.classList.remove('hidden');
                motivoInput.setAttribute('required', 'required');
            } else {
                motivoContainer.classList.add('hidden');
                motivoInput.removeAttribute('required');
                motivoInput.value = '';
            }
        }

        toggleMotivoRejeicao();
        statusSelect.addEventListener('change', toggleMotivoRejeicao);

    });
</script>

@endpush