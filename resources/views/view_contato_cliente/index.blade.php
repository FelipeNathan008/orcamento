@extends('layouts.app')

@section('title', 'Lista de Contatos de Cliente')
@if(!isset($clienteSelecionado))
<div class="text-center text-gray-600 py-10">
    Nenhum cliente selecionado.
</div>
@return
@endif
@section('content')
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Contatos Cadastrados
        </h1>
        <div class="flex items-center gap-3">

            <a href="{{ route('cliente_orcamento.index') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                VOLTAR
            </a>

            <a href="{{ route('contato_cliente.create_for_cliente', $clienteSelecionado->id_co) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Novo Contato
            </a>
        </div>

    </div>

    @if(request('cliente_orcamento') && isset($clienteSelecionado))
    <div class="bg-orange-50 border border-orange-200 rounded-lg p-6 mb-6 shadow-sm">

        <h2 class="text-lg font-bold text-orange-700 mb-3">
            Informações do Cliente Selecionado
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
    @endif

    <div class="bg-gray-50 border border-gray-200 rounded-lg p-5 mb-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">

            {{-- Buscar Nome --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pesquisar Nome
                </label>

                <div class="relative">
                    <input
                        type="text"
                        id="searchNomeContato"
                        placeholder="Nome do contato..."
                        class="w-full h-10 pl-10 pr-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">

                    <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            {{-- Buscar Email --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pesquisar Email
                </label>

                <div class="relative">
                    <input
                        type="text"
                        id="searchEmailContato"
                        placeholder="Email..."
                        class="w-full h-10 pl-10 pr-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500">

                    <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            {{-- Botão limpar --}}
            <div class="flex md:justify-end items-end">
                <button
                    type="button"
                    id="clearFiltersContato"
                    class="inline-flex items-center px-4 py-2 h-10 border border-transparent text-sm font-medium rounded-md shadow-sm text-gray-700 bg-gray-200 hover:bg-gray-300">
                    Limpar Busca
                </button>
            </div>

        </div>
    </div>
    @if ($contatosCliente->isEmpty())
    <p class="text-gray-600 text-center py-8" id="noContatosMessage">Nenhum contato de cliente cadastrado ainda.</p>
    @else
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nome</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Celular</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tipo</th>
                    <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="contatoTableBody">
                @foreach ($contatosCliente as $contato)
                <tr class="hover:bg-gray-50 transition duration-150">

                    <td class="px-4 py-4 text-sm text-gray-700">{{ $contato->cont_nome }}</td>
                    <td class="px-4 py-4 text-sm text-gray-700">{{ $contato->cont_email }}</td>
                    <td class="px-4 py-4 text-sm text-gray-700">
                        @php
                        $celular = preg_replace('/\D/', '', $contato->cont_celular);
                        @endphp

                        @if(strlen($celular) === 11)
                        {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celular) }}
                        @else
                        {{ $contato->cont_celular }}
                        @endif
                    </td>
                    <td class="px-4 py-4 text-sm text-gray-700">
                        @php
                        $cores = [
                        'administrativo' => 'bg-purple-200',
                        'comercial' => 'bg-yellow-200',
                        'financeiro' => 'bg-blue-200',
                        ];
                        $tipoClasse = $cores[$contato->cont_tipo] ?? 'bg-gray-200';
                        @endphp
                        <span class="relative inline-block px-3 py-1 font-semibold leading-tight text-gray-900">
                            <span aria-hidden="true"
                                class="absolute inset-0 opacity-50 rounded-full {{ $tipoClasse }}"></span>
                            <span class="relative">{{ ucfirst($contato->cont_tipo) }}</span>
                        </span>
                    </td>
                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('contato_cliente.show', $contato->id_contato) }}"
                                class="px-2 py-1 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Ver</a>
                            <a href="{{ route('contato_cliente.edit', $contato->id_contato) }}"
                                class="px-2 py-1 text-xs font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover">Editar</a>
                            <form action="{{ route('contato_cliente.destroy', $contato->id_contato) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir este contato?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="px-2 py-1 text-xs font-medium rounded-md text-white bg-button-cancel-bg hover:bg-button-cancel-hover">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p class="text-gray-600 text-center py-8 hidden" id="noResultsContatoMessage">Nenhum contato encontrado.</p>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const searchNome = document.getElementById('searchNomeContato');
        const searchEmail = document.getElementById('searchEmailContato');
        const clearBtn = document.getElementById('clearFiltersContato');

        const tableBody = document.getElementById('contatoTableBody');
        const noResultsMessage = document.getElementById('noResultsContatoMessage');

        if (!tableBody) return;

        const rows = tableBody.querySelectorAll('tr');

        function filtrar() {

            const nomeTerm = searchNome.value.toLowerCase();
            const emailTerm = searchEmail.value.toLowerCase();

            let found = false;

            rows.forEach(row => {

                const nome = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();

                const matchNome = !nomeTerm || nome.includes(nomeTerm);
                const matchEmail = !emailTerm || email.includes(emailTerm);

                if (matchNome && matchEmail) {
                    row.style.display = '';
                    found = true;
                } else {
                    row.style.display = 'none';
                }

            });

            if (noResultsMessage) {
                noResultsMessage.classList.toggle('hidden', found);
            }

        }

        function limparBusca() {

            searchNome.value = '';
            searchEmail.value = '';
            filtrar();

        }

        searchNome.addEventListener('input', filtrar);
        searchEmail.addEventListener('input', filtrar);

        clearBtn.addEventListener('click', limparBusca);

    });
</script>
@endsection