@extends('layouts.app')

@section('title', 'Lista de Contatos de Cliente')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Contatos Cadastrados" :back-url="$urlClienteOrcamento">
            <x-header-action href="{{ route('contato_cliente.create', ['id' => CryptHelper::encrypt($clienteSelecionado->id_co)]) }}">
                Novo Contato
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            @php
            $celularCliente = preg_replace('/\D/', '', $clienteSelecionado->clie_orc_celular ?? '');

            if (strlen($celularCliente) === 11) {
            $celularClienteFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celularCliente);
            } elseif (strlen($celularCliente) === 10) {
            $celularClienteFormatado = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $celularCliente);
            } else {
            $celularClienteFormatado = $clienteSelecionado->clie_orc_celular ?: 'Não informado';
            }
            @endphp

            <x-info-card
                title="Cliente selecionado"
                :name="$clienteSelecionado->clie_orc_nome"
                type="Cliente"
                :fields="[
                    [
                        'label' => 'Código interno',
                        'value' => $clienteSelecionado->clie_orc_cod_interno ?: 'Não informado',
                        'bold' => true,
                    ],
                    [
                        'label' => 'E-mail',
                        'value' => $clienteSelecionado->clie_orc_email ?: 'Não informado',
                        'break' => true,
                    ],
                    [
                        'label' => 'Celular',
                        'value' => $celularClienteFormatado,
                    ],
                ]" />

            <form method="GET" action="{{ route('contato_cliente.index', ['id' => CryptHelper::encrypt($clienteSelecionado->id_co)]) }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Nome</label>
                            <input type="text" id="nome" name="nome" value="{{ request('nome') }}" placeholder="Digite o nome..." class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="celular" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Celular</label>
                            <input type="text" id="celular" name="celular" value="{{ request('celular') }}" placeholder="Digite o celular..." class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">E-mail</label>
                            <input type="text" id="email" name="email" value="{{ request('email') }}" placeholder="Digite o e-mail..." class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="tipo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Tipo</label>
                            <select id="tipo" name="tipo" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos</option>
                                <option value="administrativo" {{ request('tipo') === 'administrativo' ? 'selected' : '' }}>Administrativo</option>
                                <option value="comercial" {{ request('tipo') === 'comercial' ? 'selected' : '' }}>Comercial</option>
                                <option value="financeiro" {{ request('tipo') === 'financeiro' ? 'selected' : '' }}>Financeiro</option>
                                <option value="rh" {{ request('tipo') === 'rh' ? 'selected' : '' }}>RH</option>
                                <option value="compras" {{ request('tipo') === 'compras' ? 'selected' : '' }}>Compras</option>
                                <option value="socio" {{ request('tipo') === 'socio' ? 'selected' : '' }}>Sócio</option>
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
                        <a href="{{ route('contato_cliente.index', ['id' => CryptHelper::encrypt($clienteSelecionado->id_co)]) }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de contatos</h2>

                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $contatosCliente->total() }} contato(s)
                    </span>
                </div>
            </div>

            @if ($contatosCliente->isEmpty())
            @if (request('nome') || request('email') || request('celular') || request('tipo'))
            <x-empty-state
                title="Nenhum contato encontrado"
                message="Não existem contatos correspondentes aos filtros informados."
                route="contato_cliente.index"
                :route-params="['id' => CryptHelper::encrypt($clienteSelecionado->id_co)]"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhum contato cadastrado"
                message="Ainda não existem contatos cadastrados para este cliente."
                route="contato_cliente.create"
                :route-params="['id' => CryptHelper::encrypt($clienteSelecionado->id_co)]"
                button-text="Cadastrar contato" />
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
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Tipo</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">

                            @foreach ($contatosCliente as $contato)
                            @php
                            $celular = preg_replace('/\D/', '', $contato->cont_celular ?? '');

                            if (strlen($celular) === 11) {
                            $celularFormatado = preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $celular);
                            } elseif (strlen($celular) === 10) {
                            $celularFormatado = preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $celular);
                            } else {
                            $celularFormatado = $contato->cont_celular ?: 'Não informado';
                            }

                            $tipo = [
                            'administrativo' => 'purple',
                            'comercial' => 'yellow',
                            'financeiro' => 'blue',
                            'rh' => 'pink',
                            'compras' => 'green',
                            'socio' => 'red',
                            ][$contato->cont_tipo] ?? 'gray';
                            @endphp

                            <tr class="group transition hover:bg-orange-50/40">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">
                                        {{ $contato->cont_nome }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $celularFormatado }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $contato->cont_email }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <x-badge
                                        :type="$tipo"
                                        :text="ucfirst($contato->cont_tipo)" />
                                </td>

                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :show-route="route('contato_cliente.show', CryptHelper::encrypt($contato->id_contato))"
                                        :edit-route="route('contato_cliente.edit', CryptHelper::encrypt($contato->id_contato))"
                                        :delete-action="route('contato_cliente.destroy', CryptHelper::encrypt($contato->id_contato))"
                                        delete-id="formExcluirContato{{ CryptHelper::encrypt($contato->id_contato) }}"
                                        delete-modal="modalExcluirContato" />
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$contatosCliente" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirContato"
    titulo="Excluir contato"
    mensagem="Deseja realmente apagar este contato?"
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