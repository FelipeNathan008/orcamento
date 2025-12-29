@extends('layouts.app')

@section('title', 'Lista de Contatos de Cliente')

@section('content')
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h1
                class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
                Contatos de Cliente Cadastrados
            </h1>
            <a href="{{ route('contato_cliente.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Novo Contato
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
                <strong class="font-bold">Sucesso!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Campos de busca --}}
        <form id="filtroContatoForm" action="{{ route('contato_cliente.index') }}" method="GET"
            class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            <input type="text" name="cliente_orcamento" id="searchClienteOrcamento"
                placeholder="Buscar Cliente de Orçamento..." value="{{ request('cliente_orcamento') }}"
                class="w-full h-9 pl-3 pr-3 text-sm border border-custom-border-light rounded-md outline-none focus:border-custom-border-focus text-custom-dark-text">
            <input type="text" name="nome_contato" id="searchNomeContato" placeholder="Buscar Nome do Contato..."
                value="{{ request('nome_contato') }}"
                class="w-full h-9 pl-3 pr-3 text-sm border border-custom-border-light rounded-md outline-none focus:border-custom-border-focus text-custom-dark-text">
            <input type="text" name="email" id="searchEmail" placeholder="Buscar Email..." value="{{ request('email') }}"
                class="w-full h-9 pl-3 pr-3 text-sm border border-custom-border-light rounded-md outline-none focus:border-custom-border-focus text-custom-dark-text">
        </form>

        @if ($contatosCliente->isEmpty())
            <p class="text-gray-600 text-center py-8" id="noContatosMessage">Nenhum contato de cliente cadastrado ainda.</p>
        @else
            <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
                <table class="min-w-full w-full divide-y divide-gray-200">
                    <thead class="bg-table-header-bg">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Cliente de
                                Orçamento</th>
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
                                <td class="px-4 py-4 text-sm font-medium text-gray-900">
                                    {{ $contato->clienteOrcamento->clie_orc_nome ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $contato->cont_nome }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $contato->cont_email }}</td>
                                <td class="px-4 py-4 text-sm text-gray-700">{{ $contato->cont_celular }}</td>
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
        document.addEventListener('DOMContentLoaded', function () {
            const searchClienteOrcamento = document.getElementById('searchClienteOrcamento');
            const searchNomeContato = document.getElementById('searchNomeContato');
            const searchEmail = document.getElementById('searchEmail');
            const contatoTableBody = document.getElementById('contatoTableBody');
            const noResultsMessage = document.getElementById('noResultsContatoMessage');

            function filtrar() {
                const clienteTerm = searchClienteOrcamento.value.toLowerCase();
                const nomeTerm = searchNomeContato.value.toLowerCase();
                const emailTerm = searchEmail.value.toLowerCase();
                const rows = contatoTableBody.querySelectorAll('tr');
                let foundResults = false;

                rows.forEach(row => {
                    const cliente = row.children[0]?.textContent.toLowerCase() || '';
                    const nome = row.children[1]?.textContent.toLowerCase() || '';
                    const email = row.children[2]?.textContent.toLowerCase() || '';

                    if (cliente.includes(clienteTerm) && nome.includes(nomeTerm) && email.includes(emailTerm)) {
                        row.style.display = '';
                        foundResults = true;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (!foundResults) {
                    noResultsMessage.classList.remove('hidden');
                } else {
                    noResultsMessage.classList.add('hidden');
                }
            }

            [searchClienteOrcamento, searchNomeContato, searchEmail].forEach(input => {
                input.addEventListener('input', filtrar);
            });
        });
    </script>
@endsection