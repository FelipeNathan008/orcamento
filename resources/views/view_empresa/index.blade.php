{{-- resources/views/empresas/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Lista de Empresas')

@section('content')
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

        {{-- Cabeçalho --}}
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <h1 class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
                Empresas Cadastradas
            </h1>
            <a href="{{ route('empresa.create') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
               style="background-color: #EA792D;">
                Nova Empresa
            </a>
        </div>

        {{-- Alerta de sucesso --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
                <strong class="font-bold">Sucesso!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        {{-- Campo de busca --}}
        <div class="relative w-full mb-6">
            <input type="text" id="searchCompanyInput" placeholder="Pesquisar Empresa..."
                   class="w-full h-9 pl-10 pr-3 font-poppins text-sm leading-tight font-normal bg-white border border-custom-border-light rounded-md outline-none
                               hover:border-custom-border-hover focus:border-custom-border-focus disabled:border-custom-border-light disabled:text-custom-border-light disabled:bg-white text-custom-dark-text">
            <svg class="absolute top-1/2 left-3 -translate-y-1/2 w-4 h-4 fill-custom-dark-text" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd"
                      d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                      clip-rule="evenodd"/>
            </svg>
        </div>

        {{-- Sem empresas --}}
        @if ($empresas->isEmpty())
            <p class="text-gray-600 text-center py-8" id="noCompaniesMessage">Nenhuma empresa cadastrada ainda.</p>
        @else
            {{-- Tabela --}}
            <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
                <table class="min-w-full w-full divide-y divide-gray-200">
                    <thead class="bg-table-header-bg">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Nome</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">CNPJ</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">Cidade/UF</th>
                            <th class="px-2 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="companyTableBody">
                        @foreach ($empresas as $empresa)
                            <tr>
                                <td class="px-4 py-4 text-sm font-medium text-gray-900 font-poppins">
                                    {{ $empresa->emp_nome }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                                    {{ $empresa->emp_cnpj }}
                                </td>
                                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                                    {{ $empresa->emp_cidade }} - {{ $empresa->emp_uf }}
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                                        <a href="{{ route('empresa.edit', $empresa->id_emp) }}"
                                           class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                            Editar
                                        </a>
                                        <form action="{{ route('empresa.destroy', $empresa->id_emp) }}" method="POST" class="inline-block"
                                              onsubmit="return confirm('Tem certeza que deseja excluir esta empresa?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-cancel-bg hover:bg-button-cancel-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-cancel-bg transition duration-150 ease-in-out">
                                                Excluir
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p class="text-gray-600 text-center py-8 hidden" id="noResultsMessage">
                    Nenhuma empresa encontrada com esse termo de busca.
                </p>
            </div>
        @endif
    </div>

    {{-- Script de busca dinâmica --}}
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const searchInput = document.getElementById('searchCompanyInput');
                const companyTableBody = document.getElementById('companyTableBody');
                const noCompaniesMessage = document.getElementById('noCompaniesMessage');
                const noResultsMessage = document.getElementById('noResultsMessage');

                searchInput.addEventListener('input', function () {
                    const searchTerm = this.value.toLowerCase();
                    const rows = companyTableBody.querySelectorAll('tr');
                    let foundResults = false;

                    rows.forEach(row => {
                        const rowText = row.textContent.toLowerCase();

                        if (rowText.includes(searchTerm)) {
                            row.style.display = '';
                            foundResults = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    if (searchTerm === '') {
                        if (companyTableBody.querySelectorAll('tr').length === 0) {
                            noCompaniesMessage?.classList.remove('hidden');
                        } else {
                            noCompaniesMessage?.classList.add('hidden');
                        }
                        noResultsMessage?.classList.add('hidden');
                    } else {
                        noCompaniesMessage?.classList.add('hidden');
                        foundResults ? noResultsMessage?.classList.add('hidden') : noResultsMessage?.classList.remove('hidden');
                    }
                });
            });
        </script>
    @endpush
@endsection