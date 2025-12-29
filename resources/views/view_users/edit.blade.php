@extends('layouts.app')

{{-- Define o título da página usando o nome do usuário --}}
@section('title', 'Editar Usuário: ' . $user->name)

@section('content')
    {{-- Contêiner principal para centralizar o formulário e definir largura máxima --}}
    <div class="max-w-4xl mx-auto p-8 mt-10 mb-10 font-poppins">
        {{-- Título do formulário --}}
        <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Editar Usuário: {{ $user->name }}</h1>

        {{-- Formulário de edição com método PUT para atualização --}}
        <form id="editUserForm" action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT') {{-- Importante: Laravel usa PUT para atualizar recursos --}}

            {{-- Campo Nome --}}
            <div>
                <label for="name" class="block text-sm font-medium text-custom-dark-text mb-1">Nome</label>
                <input type="text" name="name" id="name"
                    class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo E-mail --}}
            <div>
                <label for="email" class="block text-sm font-medium text-custom-dark-text mb-1">E-mail</label>
                <input type="email" name="email" id="email"
                    class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campo Papel/Role (Select) --}}
            <div>
                <label for="role" class="block text-sm font-medium text-custom-dark-text mb-1">Papel</label>
                <select name="role" id="role"
                    class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                    required>
                    <option value="">Selecione um papel</option>
                    {{-- Percorre a lista de papéis e marca o atual como selecionado --}}
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role', $user->roles->first()->name ?? null) == $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botão de Envio e Cancelar --}}
            <div class="flex justify-center space-x-4 mt-8">
                <button type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                    ATUALIZAR
                </button>
                <a href="{{ route('users.index') }}"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition duration-150 ease-in-out">
                    CANCELAR
                </a>
            </div>
        </form>
    </div>
@endsection