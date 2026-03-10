@extends('layouts.app')

@section('title', 'Gerenciamento de Usuários')

@section('content')

{{-- Div principal que centraliza o conteúdo e ajusta a largura máxima --}}
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-xl mt-10 mb-10 font-poppins">

    {{-- Cabeçalho da Seção (Título e Botão) --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <h1
            class="text-3xl sm:text-[32px] font-bold leading-tight text-custom-dark-text font-bai-jamjuree mb-4 sm:mb-0">
            Gerenciar Usuários
        </h1>
        {{-- Botão "Adicionar Novo Usuário" visível apenas para quem tem a permissão --}}

        <a href="{{ route('users.create') }}"
            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white hover:brightness-90 focus:outline-none focus:ring-2 focus:ring-offset-2 transition duration-150 ease-in-out"
            style="background-color: #EA792D;">
            Adicionar Novo Usuário
        </a>


    </div>

    @if (session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-md relative mb-4" role="alert">
        <strong class="font-bold">Sucesso!</strong>
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    {{-- TABELA DE USUÁRIOS --}}
    @if ($users->isEmpty())
    <p class="text-gray-600 text-center py-8">Nenhum usuário cadastrado ainda.</p>
    @else
    <div class="w-full rounded-lg shadow-table-shadow-image mb-4 overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-table-header-bg">
                <tr>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        ID</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Nome</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        E-mail</th>
                    <th scope="col"
                        class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Papel</th>
                    <th scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-white uppercase tracking-wider font-poppins">
                        Ações</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($users as $user)
                <tr class="hover:bg-gray-50 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 font-poppins">
                        {{ $user->id }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">{{ $user->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">{{ $user->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-poppins">
                        @foreach($user->roles as $role)
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-gray-800">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        <div class="flex items-center justify-center space-x-1 sm:space-x-2">
                            {{-- Botão "Editar" --}}
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-edit-bg hover:bg-button-edit-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-edit-bg transition duration-150 ease-in-out">
                                Editar
                            </a>

                            {{-- Botão "Excluir" --}}
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block"
                                onsubmit="return confirm('Tem certeza que deseja deletar este usuário?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-button-cancel-bg hover:bg-button-cancel-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-button-cancel-bg transition duration-150 ease-in-out">
                                    Deletar
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection