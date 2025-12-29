{{-- resources/views/view_orcamento/edit.blade.php --}}
@extends('layouts.app')

@section('title', 'Editar Orçamento: ' . $orcamento->id_orcamento)

@section('content')
<div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">
    <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Editar Orçamento:
        #{{ $orcamento->id_orcamento }}</h1>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Opa!</strong>
        <span class="block sm:inline">Existem alguns problemas com seus dados.</span>
        <ul class="mt-3 list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div>
        <form action="{{ route('orcamento.update', $orcamento->id_orcamento) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                {{-- PRIMEIRA COLUNA --}}
                <div class="space-y-6">
                    {{-- Cliente com busca --}}
                    <div>
                        <label for="cliente_orcamento_search"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Cliente de Orçamento</label>
                        <div class="relative">
                            <input type="text" id="cliente_orcamento_search" placeholder="Buscar cliente..." class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none 
                                           focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out 
                                           border border-gray-300"
                                value="{{ old('cliente_orcamento_id_co', $orcamento->cliente->clie_orc_nome ?? '') }}">

                            <div id="cliente_orcamento_results" class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg 
                                           max-h-60 overflow-y-auto hidden">
                                {{-- Resultados via JS --}}
                            </div>
                        </div>

                        <input type="hidden" name="cliente_orcamento_id_co" id="selected_client_id_hidden"
                            value="{{ old('cliente_orcamento_id_co', $orcamento->cliente_orcamento_id_co) }}" required>

                        @error('cliente_orcamento_id_co')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Data Início --}}
                    <div>
                        <label for="orc_data_inicio" class="block text-sm font-medium text-custom-dark-text mb-1">Data
                            Início</label>
                        <input type="date" name="orc_data_inicio" id="orc_data_inicio"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            value="{{ old('orc_data_inicio', $orcamento->orc_data_inicio ? $orcamento->orc_data_inicio->format('Y-m-d') : '') }}"
                            required>
                        @error('orc_data_inicio')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Anotações Específicas --}}
                    <div>
                        <label class="block text-sm font-medium text-custom-dark-text mb-1">Anotações
                            Específicas</label>
                        <div class="space-y-2">
                            @php
                            $anotacoes = old('anotacoes', $orcamento->orc_anotacao_espec ? explode("\n", $orcamento->orc_anotacao_espec) : ['', '', '']);
                            @endphp
                            <textarea name="anotacoes[]" rows="3" maxlength="1000"
                                class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300"
                                placeholder="ANOTAÇÕES">{{ $anotacoes[0] ?? '' }}</textarea>
                            <textarea name="anotacoes[]" rows="3" maxlength="1000"
                                class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300"
                                placeholder="OBSERVAÇÕES">{{ $anotacoes[1] ?? '' }}</textarea>
                            <textarea name="anotacoes[]" rows="3" maxlength="1000"
                                class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md border border-gray-300"
                                placeholder="IMPORTANTE">{{ $anotacoes[2] ?? '' }}</textarea>
                        </div>
                        @error('anotacoes')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- SEGUNDA COLUNA --}}
                <div class="space-y-6">
                    {{-- Data Fim --}}
                    <div>
                        <label for="orc_data_fim" class="block text-sm font-medium text-custom-dark-text mb-1">Data
                            Fim</label>
                        <input type="date" name="orc_data_fim" id="orc_data_fim"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            value="{{ old('orc_data_fim', $orcamento->orc_data_fim ? $orcamento->orc_data_fim->format('Y-m-d') : '') }}"
                            required>
                        @error('orc_data_fim')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="orc_status"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Status</label>
                        <select name="orc_status" id="orc_status"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="">Selecione um Status</option>
                            <option value="pendente" {{ old('orc_status', $orcamento->orc_status) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                            <option value="para aprovacao" {{ old('orc_status', $orcamento->orc_status) == 'para aprovacao' ? 'selected' : '' }}>Para Aprovação</option>
                            <option value="aprovado" {{ old('orc_status', $orcamento->orc_status) == 'aprovado' ? 'selected' : '' }}>Aprovado</option>
                            <option value="rejeitado" {{ old('orc_status', $orcamento->orc_status) == 'rejeitado' ? 'selected' : '' }}>Rejeitado</option>
                            <option value="finalizado" {{ old('orc_status', $orcamento->orc_status) == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                        </select>
                        @error('orc_status')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Anotação Geral --}}
                    <div>
                        <label for="orc_anotacao_geral"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Anotação Geral</label>
                        <textarea name="orc_anotacao_geral" id="orc_anotacao_geral" rows="4" maxlength="1000"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Digite uma anotação geral">{{ old('orc_anotacao_geral', $orcamento->orc_anotacao_geral) }}</textarea>
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
                            placeholder="Descreva o motivo da rejeição...">{{ old('orc_motivo_rejeicao', $orcamento->orc_motivo_rejeicao) }}</textarea>
                        @error('orc_motivo_rejeicao')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-center pt-8 space-x-4">
                <button type="submit"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                    ATUALIZAR
                </button>
                <a href="{{ route('orcamento.index') }}"
                    class="inline-flex items-center justify-center py-3 px-8 border border-gray-300 shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    VOLTAR
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clientesOrcamento = @json($clientesOrcamento);
        const searchInput = document.getElementById('cliente_orcamento_search');
        const resultsDiv = document.getElementById('cliente_orcamento_results');
        const hiddenInput = document.getElementById('selected_client_id_hidden');

        // Preenche o campo com o cliente selecionado
        const selectedClientId = hiddenInput.value;
        if (selectedClientId) {
            const selectedClient = clientesOrcamento.find(c => c.id_co == selectedClientId);
            if (selectedClient) searchInput.value = selectedClient.clie_orc_nome;
        }

        function renderResults(clientes) {
            resultsDiv.innerHTML = '';
            if (clientes.length > 0) {
                clientes.forEach(cliente => {
                    const item = document.createElement('div');
                    item.className = 'px-4 py-2 cursor-pointer hover:bg-gray-100';
                    item.textContent = cliente.clie_orc_nome;
                    item.dataset.id = cliente.id_co;
                    item.addEventListener('click', function() {
                        searchInput.value = cliente.clie_orc_nome;
                        hiddenInput.value = cliente.id_co;
                        resultsDiv.classList.add('hidden');
                    });
                    resultsDiv.appendChild(item);
                });
                resultsDiv.classList.remove('hidden');
            } else {
                resultsDiv.classList.add('hidden');
            }
        }

        // --- Mostrar/ocultar o campo "Motivo da Rejeição" ---
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
                motivoInput.value = ''; // limpa o campo se mudar o status
            }
        }

        // chama quando a página carrega
        toggleMotivoRejeicao();

        // escuta mudanças no select
        statusSelect.addEventListener('change', toggleMotivoRejeicao);



        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const filteredClients = clientesOrcamento.filter(cliente =>
                cliente.clie_orc_nome.toLowerCase().includes(searchTerm)
            );
            renderResults(filteredClients);
        });

        searchInput.addEventListener('focus', function() {
            renderResults(clientesOrcamento);
        });

        document.addEventListener('click', function(event) {
            if (!event.target.closest('#cliente_orcamento_search') && !event.target.closest('#cliente_orcamento_results')) {
                resultsDiv.classList.add('hidden');
            }
        });
        // --- ALERTA AO ENVIAR FORMULÁRIO COM STATUS APROVADO ---
        setTimeout(() => {
            const form = document.querySelector('form[action*="orcamento"]'); // GARANTE pegar o form certo

            if (!form) {
                console.error("FORMULÁRIO NÃO ENCONTRADO");
                return;
            }

            form.addEventListener('submit', function(e) {
                const status = document.getElementById('orc_status').value;

                if (status === 'aprovado') {
                    const confirmar = confirm("Você deseja colocar como APROVADO e ir para o módulo Financeiro?");
                    if (!confirmar) {
                        e.preventDefault();
                    }
                }
            });
        }, 300);

    });
</script>
@endpush
@endsection