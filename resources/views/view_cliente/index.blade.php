@extends('layouts.app')

@section('title', 'Lista de Prospecções')

@section('content')

<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">

        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Prospecções Cadastrados
        </h1>
        <div class="flex items-center gap-3">

            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                HOME
            </a>

            <a href="{{ route('cliente.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
                style="background-color: #EA792D;">
                Nova Prospecção
            </a>
        </div>

    </div>

    <x-alert-flash />

    <form method="GET" action="{{ route('cliente.index') }}" class="mb-6">

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
                        placeholder="Nome do cliente..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        E-mail
                    </label>

                    <input
                        type="text"
                        name="email"
                        value="{{ request('email') }}"
                        placeholder="E-mail..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Celular
                    </label>

                    <input
                        type="text"
                        name="celular"
                        value="{{ request('celular') }}"
                        placeholder="Celular..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipo Documento
                    </label>

                    <select
                        name="tipo_doc"
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">

                        <option value="">Todos</option>

                        <option value="CPF"
                            {{ request('tipo_doc') == 'CPF' ? 'selected' : '' }}>
                            CPF
                        </option>

                        <option value="CNPJ"
                            {{ request('tipo_doc') == 'CNPJ' ? 'selected' : '' }}>
                            CNPJ
                        </option>

                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        CPF/CNPJ
                    </label>

                    <input
                        type="text"
                        name="documento"
                        value="{{ request('documento') }}"
                        placeholder="Documento..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md">
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
                        href="{{ route('cliente.index') }}"
                        class="w-full h-10 bg-gray-300 rounded-md text-gray-800 flex items-center justify-center hover:bg-gray-400 transition">
                        Limpar
                    </a>
                </div>

            </div>

        </div>

    </form>

    @if ($clientes->isEmpty())

    @if(
    request('nome') ||
    request('email') ||
    request('celular') ||
    request('tipo_doc') ||
    request('documento')
    )

    <div class="text-center py-8">
        <p class="text-gray-600">
            Nenhuma prospecção encontrada para os filtros informados.
        </p>

        <a href="{{ route('cliente.index') }}"
            class="inline-block mt-3 text-orange-600 hover:text-orange-700 font-medium">
            Limpar filtros
        </a>
    </div>

    @else

    <p class="text-gray-600 text-center py-8">
        Nenhuma prospecção cadastrada.
    </p>

    @endif

    @endif

    @if ($clientes->isEmpty())

    @if(request('search'))
    <div class="text-center py-8">
        <p class="text-gray-600 text-lg">
            Nenhum cliente encontrado para
            <strong>"{{ request('search') }}"</strong>.
        </p>

        <a href="{{ route('cliente.index') }}"
            class="inline-block mt-3 text-orange-600 hover:text-orange-700 font-medium">
            Limpar filtro
        </a>
    </div>
    @else
    <p class="text-gray-600 text-center py-8">
        Nenhum cliente cadastrado ainda.
    </p>
    @endif

    @else

    {{-- TABELA DE CLIENTES --}}
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Nome
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Celular
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        E-mail
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Documento
                    </th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Ações
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="clientTableBody">
                @foreach ($clientes as $cliente)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-normal break-words text-sm font-medium text-gray-900 font-poppins">
                        {{ $cliente->clie_nome }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                        {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $cliente->clie_celular)) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                        {{ $cliente->clie_email }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">

                        {{-- CPF --}}
                        @if ($cliente->clie_tipo_doc === 'CPF' && $cliente->clie_cpf)
                        @php
                        $cpf = preg_replace('/\D/', '', $cliente->clie_cpf);
                        @endphp

                        {{ preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf) }}
                        @endif

                        {{-- CNPJ --}}
                        @if ($cliente->clie_tipo_doc === 'CNPJ' && $cliente->clie_cnpj)
                        @php
                        $cnpj = $cliente->clie_cnpj;
                        @endphp

                        {{ preg_replace('/([A-Za-z0-9]{2})([A-Za-z0-9]{3})([A-Za-z0-9]{3})([A-Za-z0-9]{4})([A-Za-z0-9]{2})/', '$1.$2.$3/$4-$5', $cnpj) }}
                        @endif

                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            {{-- Botão "Cliente Orçamento" - Direciona para a tela de clientes de orçamento --}}
                            <a href="{{ route('cliente_orcamento.create', ['cliente_id' => $cliente->id_cliente]) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-budget-bg hover:bg-button-budget-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-budget-bg transition duration-150 ease-in-out">
                                Cliente Orçamento
                            </a>

                            {{-- Botão "Ver" --}}
                            <a href="{{ route('cliente.show', $cliente->id_cliente) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                                Ver
                            </a>

                            {{-- Botão "Editar" --}}
                            <a href="{{ route('cliente.edit', $cliente->id_cliente) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>

                            {{-- Botão "Excluir" --}}
                            <form action="{{ route('cliente.destroy', $cliente->id_cliente) }}" method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir este cliente?');">
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
    </div>

    <x-pagination-compact :paginator="$clientes" />

    @endif
</div>
</div>


@endsection