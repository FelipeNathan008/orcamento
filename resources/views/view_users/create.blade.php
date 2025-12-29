@extends('layouts.app')

@section('title', 'Cadastrar Novo Usuário')

@section('content')
    <div class="max-w-6xl mx-auto p-8 mt-10 mb-10 font-poppins"> {{-- Contêiner principal para centralizar o formulário --}}

        {{-- Título do formulário --}}
        <h1 class="text-3xl font-bold text-custom-dark-text mb-8 text-center">Cadastrar Novo Usuário</h1>

        {{-- Mensagem de erro unificada do Laravel --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6">
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Seção de campos do formulário em layout de coluna única, centralizada --}}
            <div class="grid grid-cols-1 gap-6">

                {{-- Campo Nome --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-custom-dark-text mb-1">Nome</label>
                    <input type="text" name="name" id="name"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="Nome do Usuário" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo E-mail --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-custom-dark-text mb-1">E-mail</label>
                    <input type="email" name="email" id="email"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        placeholder="nome@exemplo.com" value="{{ old('email') }}" required>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Senha --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-custom-dark-text mb-1">Senha</label>
                    <input type="password" name="password" id="password"
                        class="block w-full px-4 py-2 bg-white text-gray-900 placeholder-gray-400 rounded-md outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        required>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Campo Papel --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-custom-dark-text mb-1">Papel</label>
                    <select name="role" id="role"
                        class="block w-full px-4 py-2 bg-white text-gray-900 rounded-md shadow-sm outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out border border-gray-300"
                        required>
                        <option value="" class="text-gray-400">Selecione o Papel</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div> {{-- Fim do grid --}}

            {{-- Botões de Ação --}}
            <div class="flex justify-center mt-8 space-x-4">
                <button type="submit"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-button-save-bg hover:bg-button-save-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition duration-150 ease-in-out">
                    SALVAR
                </button>
                <a href="{{ route('users.index') }}"
                    class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-base font-medium rounded-md text-custom-dark-text bg-gray-300 hover:bg-gray-400 transition duration-150 ease-in-out">
                    VOLTAR
                </a>
            </div>
        </form>
    </div>
@endsection