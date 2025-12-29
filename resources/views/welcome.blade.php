<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Alphamega</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('style.css') }}" />
</head>

<body class="bg-bege">

    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto flex justify-between items-center py-4 px-6">
            <div class="flex items-center space-x-4">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-20 h-20 object-contain" />
                <h1 class="text-azul text-3xl font-semibold">Alphamega</h1>
            </div>
            <div>
                <a href="{{ route('login') }}"
                    class="bg-azul text-white px-4 py-2 rounded-lg hover:bg-blue-800 transition duration-300">Login</a>
            </div>
            
        </div>
    </nav>


    <!-- Seção principal -->
    <div class="bg-azul w-full py-6">
        <div class="container mx-auto text-center">
            <h2 class="text-white text-3xl font-bold">Bem-vindo à Alphamega Uniformes</h2>
            <img src="{{ asset('images/logo_laranja.png') }}" alt="Logo" class="w-20 h-20 object-contain" />
            <h1 class="text-azul text-3xl font-semibold">Alphamega</h1>
            <p class="text-white mt-2">SIGO - Sistema Integrado Gerador de Orçamentos.</p>
        </div>
    </div>

    <!-- Imagem de destaque -->
    <div class="container mx-auto mt-8">
        <img src="{{ asset('images/uniforme.jpg') }}" class="rounded-lg shadow-lg mx-auto">
    </div>

</body>

</html>