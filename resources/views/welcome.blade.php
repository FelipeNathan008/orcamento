<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIGO - Alphamega</title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body
    class="h-screen flex items-center justify-center bg-cover bg-center"
    style="background-image: linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)), url('/images/uniforme.jpg');">
    <div class="bg-black/80 p-10 rounded-2xl text-center max-w-xl w-full shadow-2xl text-white">

        <img src="{{ asset('images/logo.png') }}" class="mx-auto w-80 mb-4">

        <h1 class="text-3xl font-bold mb-4">
            Sistema Integrado Gerador de Orçamentos
        </h1>

        <p class="text-gray-300 mb-8 leading-relaxed">
            Bem-vindo ao sistema da <b>Alphamega Uniformes</b>.
            Gerencie clientes, orçamentos, customizações e o módulo financeiro
            de forma simples, organizada e eficiente.
        </p>

        <a href="{{ route('login') }}"
            class="inline-block bg-orange-500 hover:bg-orange-600 px-8 py-3 rounded-lg font-semibold transition shadow-lg">
            Entrar no Sistema
        </a>

        <footer class="mt-6 text-sm text-gray-400">
            © {{ date('Y') }} Alphamega Uniformes
        </footer>

    </div>

</body>

</html>