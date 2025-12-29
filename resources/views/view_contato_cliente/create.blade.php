@extends('layouts.app')

@section('title', 'Cadastrar Novo Contato de Cliente')

@section('content')
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins">
        <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Cadastro de Novo Contato</h1>

        <form action="{{ route('contato_cliente.store') }}" method="POST" class="space-y-6">
            @csrf

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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                {{-- COLUNA ESQUERDA --}}
                <div class="space-y-6">
                    <div>
                        <label for="cliente_orcamento_search" class="block text-sm font-medium text-custom-dark-text mb-1">
                            Cliente de Orçamento
                        </label>
                        <div class="relative">
                            <input type="text" id="cliente_orcamento_search" placeholder="Buscar cliente..."
                                class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300">
                            <div id="cliente_orcamento_results"
                                class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto hidden">
                            </div>
                        </div>
                        <input type="hidden" name="cliente_orcamento_id_co" id="selected_client_id_hidden"
                            value="{{ old('cliente_orcamento_id_co') }}" required>
                        @error('cliente_orcamento_id_co')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cont_celular"
                            class="block text-sm font-medium text-custom-dark-text mb-1">Celular</label>
                        <input type="text" name="cont_celular" id="cont_celular"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="(XX) XXXXX-XXXX" maxlength="20" value="{{ old('cont_celular') }}" required>
                        @error('cont_celular')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cont_telefone" class="block text-sm font-medium text-custom-dark-text mb-1">
                            Telefone (Opcional)
                        </label>
                        <input type="text" name="cont_telefone" id="cont_telefone"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="(XX) XXXX-XXXX" maxlength="20" value="{{ old('cont_telefone') }}">
                        @error('cont_telefone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- COLUNA DIREITA --}}
                <div class="space-y-6">
                    <div>
                        <label for="cont_nome" class="block text-sm font-medium text-custom-dark-text mb-1">
                            Nome do Contato
                        </label>
                        <input type="text" name="cont_nome" id="cont_nome"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="Nome completo do contato" maxlength="45" value="{{ old('cont_nome') }}" required>
                        @error('cont_nome')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cont_tipo" class="block text-sm font-medium text-custom-dark-text mb-1">
                            Tipo de Contato
                        </label>
                        <select name="cont_tipo" id="cont_tipo"
                            class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            required>
                            <option value="">Selecione um Tipo</option>
                            <option value="administrativo" {{ old('cont_tipo') == 'administrativo' ? 'selected' : '' }}>
                                Administrativo</option>
                            <option value="comercial" {{ old('cont_tipo') == 'comercial' ? 'selected' : '' }}>Comercial
                            </option>
                            <option value="financeiro" {{ old('cont_tipo') == 'financeiro' ? 'selected' : '' }}>Financeiro
                            </option>
                        </select>
                        @error('cont_tipo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cont_email" class="block text-sm font-medium text-custom-dark-text mb-1">Email</label>
                        <input type="email" name="cont_email" id="cont_email"
                            class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                            placeholder="contato@exemplo.com" maxlength="45" value="{{ old('cont_email') }}" required>
                        @error('cont_email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <label for="cont_descricao" class="block text-sm font-medium text-custom-dark-text mb-1">
                    Descrição (Opcional)
                </label>
                <textarea name="cont_descricao" id="cont_descricao" rows="4"
                    class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    placeholder="Informações adicionais sobre o contato, como cargo ou setor"
                    maxlength="500">{{ old('cont_descricao') }}</textarea>
                @error('cont_descricao')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-center pt-8 space-x-4">
                <button type="submit"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                    SALVAR
                </button>
                <a href="{{ route('contato_cliente.index') }}"
                    class="inline-flex items-center justify-center py-3 px-8 border border-gray-300 shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    VOLTAR
                </a>
            </div>
        </form>
    </div>

    @push('scripts')
        {{-- Inclua o jQuery e o jQuery Mask Plugin --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
        <script>
            $(document).ready(function () {
                // Máscaras de input
                $('#cont_telefone').mask('(00) 0000-0000');
                $('#cont_celular').mask('(00) 00000-0000');

                // Impede que números sejam digitados no campo de nome
                $('#cont_nome').on('input', function (e) {
                    const input = e.target;
                    input.value = input.value.replace(/[^a-zA-Z\s\u00C0-\u017F]/g, '');
                });

                // --- Lógica de busca e seleção de cliente ---
                const clientesOrcamento = @json($clientesOrcamento);
                const searchInput = $('#cliente_orcamento_search');
                const resultsDiv = $('#cliente_orcamento_results');
                const hiddenInput = $('#selected_client_id_hidden');

                function renderResults(clientes) {
                    resultsDiv.empty();
                    if (clientes.length > 0) {
                        clientes.forEach(cliente => {
                            const item = $('<div>')
                                .addClass('px-4 py-2 cursor-pointer hover:bg-gray-100')
                                .text(cliente.clie_orc_nome)
                                .attr('data-id', cliente.id_co)
                                .click(function () {
                                    searchInput.val(cliente.clie_orc_nome);
                                    hiddenInput.val(cliente.id_co);
                                    resultsDiv.addClass('hidden');
                                    // Remove o destaque de erro quando um cliente é selecionado
                                    searchInput.removeClass('border-red-500');
                                });
                            resultsDiv.append(item);
                        });
                        resultsDiv.removeClass('hidden');
                    } else {
                        resultsDiv.addClass('hidden');
                    }
                }

                // Lógica de busca e exibição
                searchInput.on('input', function () {
                    const searchTerm = $(this).val().toLowerCase();
                    const filteredClients = clientesOrcamento.filter(cliente =>
                        cliente.clie_orc_nome.toLowerCase().includes(searchTerm)
                    );
                    renderResults(filteredClients);
                    // IMPORTANTE: Limpa o campo escondido se o usuário começar a digitar,
                    // forçando a seleção de um novo item
                    if (searchTerm === '') {
                        hiddenInput.val('');
                    }
                });

                searchInput.on('focus', function () {
                    renderResults(clientesOrcamento);
                });

                // Remove o destaque de erro ao focar no campo
                searchInput.on('focus', function () {
                    $(this).removeClass('border-red-500');
                });

                // Esconde a lista de resultados ao clicar fora do campo
                $(document).on('click', function (event) {
                    if (!$(event.target).closest('#cliente_orcamento_search, #cliente_orcamento_results').length) {
                        resultsDiv.addClass('hidden');
                    }
                });

                // NOVO: Validação do formulário antes do envio
                $('form').on('submit', function (event) {
                    // Verifica se o campo escondido de ID do cliente está vazio
                    if (hiddenInput.val() === '') {
                        event.preventDefault(); // Impede o envio do formulário
                        alert('Por favor, selecione um cliente da lista de busca. Não apenas digite.');
                        searchInput.addClass('border-red-500').focus(); // Adiciona destaque de erro
                    }
                });
            });
        </script>
    @endpush
@endsection