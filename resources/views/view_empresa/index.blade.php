{{-- resources/views/empresas/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Lista de Empresas')

@section('content')
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Empresas Cadastradas
        </h1>
        <div class="flex items-center gap-3">

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                HOME
            </a>

            <a href="{{ route('empresa.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Nova Empresa
            </a>
        </div>

    </div>

    <x-alert-flash />

    <form method="GET" action="{{ route('empresa.index') }}" class="mb-6">

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nome
                    </label>

                    <input
                        type="text"
                        name="nome"
                        value="{{ request('nome') }}"
                        placeholder="Nome da empresa..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        CNPJ
                    </label>

                    <input
                        type="text"
                        id="cnpj"
                        name="cnpj"
                        value="{{ request('cnpj') }}"
                        placeholder="00.000.000/0000-00"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Cidade
                    </label>

                    <input
                        type="text"
                        name="cidade"
                        value="{{ request('cidade') }}"
                        placeholder="Cidade..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        UF
                    </label>

                    <select
                        name="uf"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">

                        <option value="">Todos os Estados</option>

                        @php
                        $estados = [
                        'AC' => 'Acre',
                        'AL' => 'Alagoas',
                        'AP' => 'Amapá',
                        'AM' => 'Amazonas',
                        'BA' => 'Bahia',
                        'CE' => 'Ceará',
                        'DF' => 'Distrito Federal',
                        'ES' => 'Espírito Santo',
                        'GO' => 'Goiás',
                        'MA' => 'Maranhão',
                        'MT' => 'Mato Grosso',
                        'MS' => 'Mato Grosso do Sul',
                        'MG' => 'Minas Gerais',
                        'PA' => 'Pará',
                        'PB' => 'Paraíba',
                        'PR' => 'Paraná',
                        'PE' => 'Pernambuco',
                        'PI' => 'Piauí',
                        'RJ' => 'Rio de Janeiro',
                        'RN' => 'Rio Grande do Norte',
                        'RS' => 'Rio Grande do Sul',
                        'RO' => 'Rondônia',
                        'RR' => 'Roraima',
                        'SC' => 'Santa Catarina',
                        'SP' => 'São Paulo',
                        'SE' => 'Sergipe',
                        'TO' => 'Tocantins'
                        ];
                        @endphp

                        @foreach ($estados as $sigla => $nome)
                        <option
                            value="{{ $sigla }}"
                            {{ request('uf') == $sigla ? 'selected' : '' }}>
                            {{ $nome }}
                        </option>
                        @endforeach

                    </select>
                </div>

                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full h-10 text-white rounded-md"
                        style="background-color:#EA792D;">
                        Buscar
                    </button>
                </div>

                <div class="flex items-end">
                    <a
                        href="{{ route('empresa.index') }}"
                        class="w-full h-10 bg-gray-300 rounded-md text-gray-800 flex items-center justify-center hover:bg-gray-400 transition">
                        Limpar
                    </a>
                </div>

            </div>

        </div>

    </form>

    @if ($empresas->isEmpty())

    @if(
    request('nome') ||
    request('cnpj') ||
    request('cidade') ||
    request('uf')
    )
    <div class="text-center py-8">
        <p class="text-gray-600 text-lg">
            Nenhuma empresa encontrada para
            com os filtros informados. </p>

        <a href="{{ route('empresa.index') }}"
            class="inline-block mt-3 text-orange-600 hover:text-orange-700 font-medium">
            Limpar filtro
        </a>
    </div>
    @else
    <p class="text-gray-600 text-center py-8">
        Nenhuma empresa cadastrada ainda.
    </p>
    @endif

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

                            {{-- BOTÃO VER --}}
                            <a href="{{ route('empresa.show', $empresa->id_emp) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition duration-150 ease-in-out">
                                Ver
                            </a>

                            {{-- BOTÃO EDITAR --}}
                            <a href="{{ route('empresa.edit', $empresa->id_emp) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>

                            {{-- BOTÃO EXCLUIR --}}
                            <form action="{{ route('empresa.destroy', $empresa->id_emp) }}" method="POST"
                                class="inline-block"
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

    <x-pagination-compact :paginator="$empresas" />

    @endif
</div>


@endsection
@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(document).ready(function() {
        $('#cnpj').mask('AA.AAA.AAA/AAAA-AA', {
            translation: {
                'A': {
                    pattern: /[A-Za-z0-9]/
                }
            }
        });
    });
</script>
@endpush