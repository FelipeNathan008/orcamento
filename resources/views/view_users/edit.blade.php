@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Editar Usuário" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="userForm" action="{{ route('users.update', CryptHelper::encrypt($user->id)) }}" method="POST" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            @method('PUT')

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">
                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do usuário</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Atualize as informações cadastrais do usuário.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Nome
                        </label>
                        <input
                            type="text"
                            name="name"
                            id="name"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Nome do usuário"
                            maxlength="255"
                            value="{{ old('name', $user->name) }}"
                            required>
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            E-mail
                        </label>
                        <input
                            type="email"
                            name="email"
                            id="email"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="nome@exemplo.com"
                            maxlength="255"
                            value="{{ old('email', $user->email) }}"
                            required>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Nova senha
                        </label>
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Deixe em branco para manter a atual">
                    </div>

                    <div>
                        <label for="role" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Papel
                        </label>
                        <select
                            name="role"
                            id="role"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            required>
                            <option value="">Selecione o papel</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role', $user->roles->first()->name ?? null) == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="btnSalvarUsuario" class="px-6">
                    <span id="textoSalvar">Atualizar usuário</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const form = document.getElementById('userForm');
    const btnSalvar = document.getElementById('btnSalvarUsuario');
    const textoSalvar = document.getElementById('textoSalvar');

    form.addEventListener('submit', function(event) {
        if (btnSalvar.disabled) {
            event.preventDefault();
            return;
        }
        btnSalvar.disabled = true;
        textoSalvar.innerText = 'Atualizando...';
        btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
    });
</script>
@endpush
@endsection