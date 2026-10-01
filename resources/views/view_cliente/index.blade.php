@extends('layouts.app')

@section('title', 'Lista de Prospecções')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Prospecções Cadastradas">
            <x-header-action href="{{ route('cliente.create') }}">
                Nova Prospecção
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('cliente.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div>
                            <label for="nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Nome</label>
                            <input type="text" id="nome" name="nome" value="{{ request('nome') }}" placeholder="Nome da prospecção..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="documento" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">CPF/CNPJ</label>
                            <input type="text" id="documento" name="documento" value="{{ request('documento') }}" placeholder="Documento..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="celular" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Celular</label>
                            <input type="text" id="celular" name="celular" value="{{ request('celular') }}" placeholder="Celular..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">E-mail</label>
                            <input type="text" id="email" name="email" value="{{ request('email') }}" placeholder="E-mail..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div class="flex items-end">
                            <x-primary-button class="w-full h-11">
                                <x-icons.search />
                                Buscar
                            </x-primary-button>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('cliente.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de prospecções</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $clientes->total() }} prospecção(ões)</span>
                </div>
            </div>

            @if ($clientes->isEmpty())
            @if (request('nome') || request('documento') || request('celular') || request('email'))
            <x-empty-state
                title="Nenhuma prospecção encontrada"
                message="Não existem prospecções correspondentes aos filtros informados."
                route="cliente.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhuma prospecção cadastrada"
                message="Ainda não existem prospecções cadastradas no sistema."
                route="cliente.create"
                button-text="Cadastrar prospecção" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Nome</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Celular</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">E-mail</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Documento</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($clientes as $cliente)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5">
                                    <span class="text-sm font-bold text-gray-800">{{ $cliente->clie_nome }}</span>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">
                                        {{ preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', preg_replace('/\D/', '', $cliente->clie_celular)) }}
                                    </span>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">{{ $cliente->clie_email }}</span>
                                </td>

                                <td class="px-5 py-5">
                                    @if ($cliente->clie_tipo_doc === 'CPF' && $cliente->clie_cpf)
                                    @php $cpf = preg_replace('/\D/', '', $cliente->clie_cpf); @endphp
                                    <span class="text-sm text-gray-700">
                                        {{ preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf) }}
                                    </span>
                                    @elseif ($cliente->clie_tipo_doc === 'CNPJ' && $cliente->clie_cnpj)
                                    @php $cnpj = preg_replace('/\D/', '', $cliente->clie_cnpj); @endphp
                                    <span class="text-sm text-gray-700">
                                        {{ preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $cnpj) }}
                                    </span>
                                    @endif
                                </td>

                                <td class="px-2 py-5 whitespace-nowrap">
                                    <div class="flex items-center justify-center gap-1 sm:gap-2">
                                        <x-table-actions
                                            :budgets-route="route('cliente_orcamento.create', CryptHelper::encrypt($cliente->id_cliente))"
                                            budgets-text="Orçamentos"
                                            :show-route="route('cliente.show', CryptHelper::encrypt($cliente->id_cliente))"
                                            :edit-route="route('cliente.edit', CryptHelper::encrypt($cliente->id_cliente))"
                                            :delete-action="route('cliente.destroy', CryptHelper::encrypt($cliente->id_cliente))"
                                            delete-id="formExcluirCliente{{ $cliente->id_cliente }}"
                                            delete-modal="modalExcluirCliente" />
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$clientes" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirCliente"
    titulo="Excluir prospecção"
    mensagem="Deseja realmente apagar esta prospecção?"
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