@extends('layouts.app')

@section('title', 'Preços de Customização')

@section('content')

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Preços de Customização Cadastrados">
            <x-header-action href="{{ route('preco_customizacao.create') }}">
                Novo Preço
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('preco_customizacao.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tipo</label>
                            <input type="text" id="tipo" name="tipo" value="{{ request('tipo') }}" placeholder="Tipo de customização..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="tamanho" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tamanho</label>
                            <input type="text" id="tamanho" name="tamanho" value="{{ request('tamanho') }}" placeholder="Tamanho..."
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
                        <a href="{{ route('preco_customizacao.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de preços</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $precosCustomizacao->total() }} preço(s)</span>
                </div>
            </div>

            @if ($precosCustomizacao->isEmpty())
            @if (request('tipo') || request('tamanho'))
            <x-empty-state
                title="Nenhum preço encontrado"
                message="Não existem preços de customização correspondentes aos filtros informados."
                route="preco_customizacao.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhum preço cadastrado"
                message="Ainda não existem preços de customização cadastrados no sistema."
                route="preco_customizacao.create"
                button-text="Cadastrar preço" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Tipo</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Tamanho</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Valor</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($precosCustomizacao as $preco)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5">
                                    <span class="text-sm font-bold text-gray-800">{{ $preco->preco_tipo }}</span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $preco->preco_tamanho }}</span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">R$ {{ number_format($preco->preco_valor, 2, ',', '.') }}</span>
                                </td>

                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :edit-route="route('preco_customizacao.edit', $preco->id_preco)"
                                        :delete-action="route('preco_customizacao.destroy', $preco->id_preco)"
                                        delete-id="formExcluirPreco{{ $preco->id_preco }}"
                                        delete-modal="modalExcluirPreco" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$precosCustomizacao" />
            </div>
            @endif
        </div>
    </div>

</div>

<x-modal-confirmacao
    id="modalExcluirPreco"
    titulo="Excluir preço de customização"
    mensagem="Deseja realmente apagar este preço de customização?"
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