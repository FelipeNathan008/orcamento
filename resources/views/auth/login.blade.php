<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Alphamega</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="h-screen flex items-center justify-center bg-cover bg-center"
    style="background-image: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)), url('/images/uniforme.jpg');">
    <div class="bg-black/85 p-10 rounded-2xl w-full max-w-md shadow-2xl text-white">

        <h2 class="text-2xl font-bold text-center mb-6">
            Acesso ao Sistema
        </h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm mb-1">Email</label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full p-3 rounded-md text-black focus:ring-2 focus:ring-orange-500">

                @error('email')
                <small class="text-red-400">{{ $message }}</small>
                @enderror
            </div>

            <!-- Senha -->
            <div class="mb-4">
                <label class="block text-sm mb-1">Senha</label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full p-3 rounded-md text-black focus:ring-2 focus:ring-orange-500">

                @error('password')
                <small class="text-red-400">{{ $message }}</small>
                @enderror
            </div>

            <!-- Remember -->
            <div class="flex items-center mb-4 text-sm">
                <input type="checkbox" name="remember" class="mr-2">
                Lembrar de mim
            </div>

            <!-- Botão -->
            <button
                type="submit"
                class="w-full bg-orange-500 hover:bg-orange-600 py-3 rounded-lg font-semibold transition ">
                Entrar
            </button>

            <!-- Esqueceu senha -->
            <div class="text-center mt-4 text-sm">
                @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-gray-300 hover:underline">
                    Esqueceu a senha?
                </a>
                @endif
            </div>

        </form>

    </div>

</body>

</html>