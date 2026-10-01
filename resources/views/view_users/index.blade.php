@extends('layouts.app')

@section('title', 'Gerenciamento de Usuários')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Gerenciamento de Usuários">
            <x-header-action href="{{ route('users.create', array_merge(request()->query(), ['page' => $users->currentPage()])) }}">
                Novo Usuário
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('users.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Nome
                            </label>
                            <input
                                type="text"
                                id="nome"
                                name="nome"
                                value="{{ request('nome') }}"
                                placeholder="Nome do usuário..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                E-mail
                            </label>
                            <input
                                type="text"
                                id="email"
                                name="email"
                                value="{{ request('email') }}"
                                placeholder="E-mail do usuário..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>


                        <div>
                            <label for="papel" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                                Papel
                            </label>
                            <select
                                id="papel"
                                name="papel"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos</option>
                                @foreach ($roles as $role)
                                <option value="{{ $role->name }}" {{ request('papel') == $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
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
                        <a
                            href="{{ route('users.index') }}"
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
                <h2 class="text-lg font-bold text-gray-800">Lista de usuários</h2>

                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">
                        {{ $users->total() }} usuário(s)
                    </span>
                </div>
            </div>

            @if ($users->isEmpty())
            @if (request('nome') || request('email') || request('papel'))
            <x-empty-state
                title="Nenhum usuário encontrado"
                message="Não existem usuários correspondentes aos filtros informados."
                route="users.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhum usuário cadastrado"
                message="Ainda não existem usuários cadastrados no sistema."
                route="users.create"
                button-text="Cadastrar usuário" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    ID
                                </th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Nome
                                </th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    E-mail
                                </th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">
                                    Papel
                                </th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($users as $user)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5">
                                    <span class="text-sm font-semibold text-gray-800">
                                        {{ $user->id }}
                                    </span>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="text-sm font-semibold text-gray-800">
                                        {{ $user->name }}
                                    </span>
                                </td>

                                <td class="px-5 py-5">
                                    <span class="text-sm text-gray-700">
                                        {{ $user->email }}
                                    </span>
                                </td>

                                <td class="px-5 py-5">
                                    @foreach ($user->roles as $role)
                                    @php
                                    $badgeType = match (strtolower($role->name)) {
                                    'admin', 'administrador' => 'blue',
                                    default => 'gray',
                                    };
                                    @endphp
                                    <x-badge :type="$badgeType" :text="$role->name" />
                                    @endforeach
                                </td>

                                <td class="px-5 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :edit-route="route('users.edit', ['user' => CryptHelper::encrypt($user->id)] + request()->query())"
                                        :delete-action="route('users.destroy', CryptHelper::encrypt($user->id))"
                                        delete-id="formExcluirUsuario{{ $user->id }}"
                                        delete-modal="modalExcluirUsuario" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$users" />
            </div>
            @endif
        </div>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirUsuario"
    titulo="Excluir usuário"
    mensagem="Deseja realmente excluir este usuário?"
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
            if (e.target.closest('tbody a, tbody button, a[href*="/create"]')) {
                sessionStorage.setItem(key, window.scrollY);
            }
        });
    })();
</script>
@endpush