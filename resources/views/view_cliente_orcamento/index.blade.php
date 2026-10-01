@extends('layouts.app')

@section('title', 'Lista de Clientes de Orçamento')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Clientes de Orçamento Cadastrados">
            <x-header-action href="{{ route('cliente_orcamento.create') }}">
                Novo Cliente
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('cliente_orcamento.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Nome</label>
                            <input type="text" name="nome" id="nome" value="{{ request('nome') }}" placeholder="Digite o nome..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">E-mail</label>
                            <input type="text" name="email" id="email" value="{{ request('email') }}" placeholder="Digite o e-mail..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <div>
                            <label for="codigo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Código</label>
                            <input type="text" name="codigo" id="codigo" value="{{ request('codigo') }}" placeholder="Código interno..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <div>
                            <label for="id_orcamento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">ID Orçamento</label>
                            <input type="number" name="id_orcamento" id="id_orcamento" value="{{ request('id_orcamento') }}" placeholder="ID do orçamento..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <div class="flex items-end">
                            <x-primary-button class="w-full h-11">
                                <x-icons.search class="w-4 h-4" />
                                Buscar
                            </x-primary-button>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('cliente_orcamento.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-orange-600 transition">
                            <x-icons.reset class="w-4 h-4" />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de clientes</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $clientesOrcamento->total() }} cliente(s)</span>
                </div>
            </div>

            @if ($clientesOrcamento->isEmpty())
            @if (request('nome') || request('email') || request('codigo') || request('id_orcamento'))
            <x-empty-state
                title="Nenhum cliente encontrado"
                message="Não existem clientes correspondentes aos filtros informados."
                route="cliente_orcamento.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhum cliente cadastrado"
                message="Ainda não existem clientes de orçamento cadastrados no sistema."
                route="cliente_orcamento.create"
                button-text="Cadastrar cliente" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Cód.</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Nome</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Celular</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">E-mail</th>
                                <th class="px-6 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($clientesOrcamento as $cliente)
                            @php
                            $celular = preg_replace('/\D/', '', $cliente->clie_orc_celular ?? '');
                            if (strlen($celular) === 11) {
                            $celularFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celular);
                            } elseif (strlen($celular) === 10) {
                            $celularFormatado = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $celular);
                            } else {
                            $celularFormatado = $cliente->clie_orc_celular ?: 'Não informado';
                            }
                            @endphp

                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                            <x-icons.document />
                                        </div>
                                        <span class="text-sm font-bold text-gray-800">{{ $cliente->clie_orc_cod_interno }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-bold text-gray-800">{{ $cliente->clie_orc_nome }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $celularFormatado }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm text-gray-700">{{ $cliente->clie_orc_email }}</span>
                                </td>
                                <td class="px-2 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1 sm:gap-2">
                                        <x-table-actions
                                            :contacts-route="route('contato_cliente.index', CryptHelper::encrypt($cliente->id_co))"
                                            contacts-text="Contatos"
                                            :budgets-route="route('orcamento.index', CryptHelper::encrypt($cliente->id_co))"
                                            :show-route="route('cliente_orcamento.show', CryptHelper::encrypt($cliente->id_co))"
                                            :edit-route="route('cliente_orcamento.edit', CryptHelper::encrypt($cliente->id_co))"
                                            :delete-action="route('cliente_orcamento.destroy', CryptHelper::encrypt($cliente->id_co))"
                                            delete-id="formExcluirCliente{{ CryptHelper::encrypt($cliente->id_co) }}"
                                            delete-modal="modalExcluirCliente" />
                                    </div>
                                    <form id="formExcluirCliente{{ $cliente->id_co }}"
                                        action="{{ route('cliente_orcamento.destroy', CryptHelper::encrypt($cliente->id_co)) }}"
                                        method="POST" class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$clientesOrcamento" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirCliente"
    titulo="Excluir cliente"
    mensagem="Deseja realmente apagar este cliente?"
    textoConfirmar="Excluir" />

@push('scripts')
<script>
    (function() {
        const key = 'scroll:' + location.pathname + location.search;
        const saved = sessionStorage.getItem(key);
        if (saved !== null) {
            window.scrollTo(0, parseInt(saved, 10));
            sessionStorage.removeItem(key);
        }
        document.addEventListener('click', function(e) {
            if (e.target.closest('tbody a, tbody button, a[href$="/create"]')) {
                sessionStorage.setItem(key, window.scrollY);
            }
        });
    })();
</script>
@endpush
@endsection