@extends('layouts.app')

@section('title', 'Lista de Clientes de Orçamento')

@section('content')

<x-container>
    <x-page-header
        title="Clientes Cadastrados"
        :route="route('cliente_orcamento.create')"
        label="Novo Cliente" />

    <x-alert-flash />

    <form method="GET" action="{{ route('cliente_orcamento.index') }}" class="mb-6">

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-5">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                {{-- Nome --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nome
                    </label>

                    <input
                        type="text"
                        name="nome"
                        value="{{ request('nome') }}"
                        placeholder="Digite o nome..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500">
                </div>

                {{-- E-mail --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        E-mail
                    </label>

                    <input
                        type="text"
                        name="email"
                        value="{{ request('email') }}"
                        placeholder="Digite o e-mail..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500">
                </div>

                {{-- Código --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Código
                    </label>

                    <input
                        type="text"
                        name="codigo"
                        value="{{ request('codigo') }}"
                        placeholder="Digite o código..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500">
                </div>

                {{-- ID Orçamento --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        ID Orçamento
                    </label>

                    <input
                        type="number"
                        name="id_orcamento"
                        value="{{ request('id_orcamento') }}"
                        placeholder="Digite o ID..."
                        class="w-full h-10 px-3 text-sm border border-gray-300 rounded-md shadow-sm focus:ring-2 focus:ring-orange-500">
                </div>

                {{-- Buscar --}}
                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full h-10 text-white rounded-md"
                        style="background-color:#EA792D;">
                        Buscar
                    </button>
                </div>

                {{-- Limpar --}}
                <div class="flex items-end">
                    <a
                        href="{{ route('cliente_orcamento.index') }}"
                        class="w-full h-10 bg-gray-300 rounded-md text-gray-800 flex items-center justify-center hover:bg-gray-400 transition">
                        Limpar
                    </a>
                </div>

            </div>

        </div>

    </form>
    @if ($clientesOrcamento->isEmpty())

    @if(
    request('nome') ||
    request('email') ||
    request('codigo') ||
    request('id_orcamento')
    )

    <div class="text-center py-8">
        <p class="text-gray-600 text-lg">
            Nenhum cliente de orçamento encontrado com os filtros informados.
        </p>

        <a href="{{ route('cliente_orcamento.index') }}"
            class="inline-block mt-3 text-orange-600 hover:text-orange-700 font-medium">
            Limpar filtros
        </a>
    </div>

    @else

    <p class="text-gray-600 text-center py-8">
        Nenhum cliente de orçamento cadastrado ainda.
    </p>

    @endif

    @else

    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">

        <table class="min-w-full w-full divide-y divide-gray-200">

            <thead class="bg-table-header-bg">

                <tr>

                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Cód.
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Nome
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        Celular
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                        E-mail
                    </th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                        Ações
                    </th>

                </tr>

            </thead>

            <tbody class="bg-white divide-y divide-gray-200">

                @foreach ($clientesOrcamento as $cliente)

                <tr class="hover:bg-gray-50 transition duration-150">

                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $cliente->clie_orc_cod_interno }}
                    </td>

                    <td class="px-6 py-4 text-sm font-medium text-gray-900">
                        {{ $cliente->clie_orc_nome }}
                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700">

                        @php
                        $celular = preg_replace('/\D/', '', $cliente->clie_orc_celular);

                        if (strlen($celular) == 11) {

                        $celularFormatado = preg_replace(
                        '/(\d{2})(\d{5})(\d{4})/',
                        '($1) $2-$3',
                        $celular
                        );

                        } elseif (strlen($celular) == 10) {

                        $celularFormatado = preg_replace(
                        '/(\d{2})(\d{4})(\d{4})/',
                        '($1) $2-$3',
                        $celular
                        );

                        } else {

                        $celularFormatado = $cliente->clie_orc_celular;
                        }
                        @endphp

                        {{ $celularFormatado }}

                    </td>

                    <td class="px-6 py-4 text-sm text-gray-700">
                        {{ $cliente->clie_orc_email }}
                    </td>

                    <td class="px-2 py-4 whitespace-nowrap text-center text-sm font-medium">

                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">

                            <a href="{{ route('contato_cliente.index', ['cliente_orcamento' => $cliente->id_co]) }}"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-button-contact-bg hover:bg-button-contact-hover">
                                Contatos
                            </a>

                            <a href="{{ route('orcamento.index', ['cliente_orcamento_id' => $cliente->id_co]) }}"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-button-budget-bg hover:bg-button-budget-hover">
                                Orçamentos
                            </a>

                            <a href="{{ route('cliente_orcamento.show', $cliente->id_co) }}"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Ver
                            </a>

                            <a href="{{ route('cliente_orcamento.edit', $cliente->id_co) }}"
                                class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover">
                                Editar
                            </a>

                            <form action="{{ route('cliente_orcamento.destroy', $cliente->id_co) }}"
                                method="POST"
                                class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja excluir este cliente de orçamento?');">

                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md text-white bg-button-cancel-bg hover:bg-button-cancel-hover">
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
    <x-pagination-compact :paginator="$clientesOrcamento" />
    @endif
</x-container>
@endsection