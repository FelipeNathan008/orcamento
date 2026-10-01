@extends('layouts.app')

@section('title', 'Notas Fiscais')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Notas Fiscais">
            <x-header-action href="{{ route('nota_fiscal.create', ['return_url' => request()->fullUrl()]) }}">
                Nova Nota
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('nota_fiscal.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="data" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Data</label>
                            <input type="date" id="data" name="data" value="{{ request('data') }}"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tipo</label>
                            <select id="tipo" name="tipo"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos</option>
                                @foreach($tipos as $tipo)
                                <option value="{{ $tipo->tipo_flu_nome }}" {{ request('tipo') === $tipo->tipo_flu_nome ? 'selected' : '' }}>
                                    {{ $tipo->tipo_flu_nome }}
                                </option>
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
                        <a href="{{ route('nota_fiscal.index') }}"
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
                <h2 class="text-lg font-bold text-gray-800">Lista de notas fiscais</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $notas->total() }} nota(s)
                    </span>
                </div>
            </div>

            @if($notas->isEmpty())
            @if(request('data') || request('tipo'))
            <x-empty-state
                title="Nenhuma nota encontrada"
                message="Não existem notas fiscais correspondentes aos filtros informados."
                route="nota_fiscal.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhuma nota fiscal cadastrada"
                message="Ainda não existem notas fiscais cadastradas."
                route="nota_fiscal.create"
                button-text="Cadastrar nota" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Data</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Orçamento</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Número</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Tipo</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Movimentação</th>
                                <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Valor</th>
                                <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach($notas as $nota)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                            <x-icons.calendar class="w-4 h-4" />
                                        </div>
                                        <span class="text-sm font-semibold text-gray-700">
                                            {{ \Carbon\Carbon::parse($nota->nota_data)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">
                                        #{{ $nota->orcamento_id_orcamento }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $nota->nota_numero }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $nota->tipo->tipo_flu_nome ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $nota->movimentacao->mov_nome ?? 'N/A' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-right whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">
                                        R$ {{ number_format($nota->nota_valor, 2, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-2 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :show-route="route('nota_fiscal.show', CryptHelper::encrypt($nota->id_nota_fiscal))"
                                        show-text="Ver"
                                        :edit-route="route('nota_fiscal.edit', CryptHelper::encrypt($nota->id_nota_fiscal))"
                                        :delete-action="route('nota_fiscal.destroy', CryptHelper::encrypt($nota->id_nota_fiscal))"
                                        delete-id="formExcluirNota{{ $nota->id_nota_fiscal }}"
                                        delete-modal="modalExcluirNota" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$notas" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirNota"
    titulo="Excluir nota fiscal"
    mensagem="Deseja realmente apagar esta nota fiscal?"
    textoConfirmar="Excluir" />

@push('scripts')
<script>
    (function() {
        const scrollKey = 'nota_fiscal.index.scroll';
        const savedScroll = sessionStorage.getItem(scrollKey);

        if (savedScroll !== null) {
            window.scrollTo(0, parseInt(savedScroll, 10));
            sessionStorage.removeItem(scrollKey);
        }

        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            const button = e.target.closest('button');

            if (
                link?.closest('tbody') ||
                link?.closest('thead') ||
                link?.href?.includes('/create') ||
                button?.closest('tbody')
            ) {
                sessionStorage.setItem(scrollKey, window.scrollY);
            }
        });
    })();
</script>
@endpush
@endsection