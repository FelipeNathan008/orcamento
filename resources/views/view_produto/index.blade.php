@extends('layouts.app')

@section('title', 'Lista de Produtos')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Produtos Cadastrados">
            <x-header-action href="{{ route('produto.create') }}">
                Novo Produto
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('produto.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="busca" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Produto</label>
                            <input type="text" id="busca" name="busca" value="{{ request('busca') }}" placeholder="Nome ou código..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>
                        <div>
                            <label for="familia" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Família</label>
                            <select id="familia" name="familia" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                @foreach ($familias as $familia)
                                <option value="{{ $familia }}" {{ request('familia') == $familia ? 'selected' : '' }}>{{ $familia }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="categoria" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Categoria</label>
                            <select id="categoria" name="categoria" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todas</option>
                                @foreach ($categorias as $categoria)
                                <option value="{{ $categoria }}" {{ request('categoria') == $categoria ? 'selected' : '' }}>{{ $categoria }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-end">
                            <x-primary-button class="w-full h-11">
                                <x-icons.search />
                                Buscar
                            </x-primary-button>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('produto.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de produtos</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $produtos->total() }} produto(s)</span>
                </div>
            </div>

            @if ($produtos->isEmpty())
            @if (request('busca') || request('familia') || request('categoria'))
            <x-empty-state
                title="Nenhum produto encontrado"
                message="Não existem produtos correspondentes aos filtros informados."
                route="produto.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhum produto cadastrado"
                message="Ainda não existem produtos cadastrados no sistema."
                route="produto.create"
                button-text="Cadastrar produto" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Código</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Nome</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Família</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Categoria</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Cor</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Preço</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($produtos as $produto)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">{{ $produto->prod_cod }}</span>
                                </td>
                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">{{ $produto->prod_nome }}</span>
                                </td>
                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">{{ $produto->prod_familia }}</span>
                                </td>
                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">{{ $produto->prod_categoria }}</span>
                                </td>
                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">{{ $produto->prod_cor }}</span>
                                </td>
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-800">R$ {{ number_format($produto->prod_preco, 2, ',', '.') }}</span>
                                </td>
                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :show-route="route('produto.show', CryptHelper::encrypt($produto->id_produto))"
                                        :edit-route="route('produto.edit', CryptHelper::encrypt($produto->id_produto))"
                                        :delete-action="route('produto.destroy', CryptHelper::encrypt($produto->id_produto))"
                                        delete-id="formExcluirProduto{{ $produto->id_produto }}"
                                        delete-modal="modalExcluirProduto" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$produtos" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirProduto"
    titulo="Excluir produto"
    mensagem="Deseja realmente excluir este produto?"
    textoConfirmar="Excluir" />
@endsection

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