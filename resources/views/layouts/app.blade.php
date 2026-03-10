<!DOCTYPE html>
<html lang="pt-BR" class="h-full"> {{-- ESSENCIAL: Garante que o HTML ocupe 100% da altura da viewport --}}

<head>
    {{-- Meta Tags Essenciais --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplicação Laravel')</title>

    {{-- Estilos e Frameworks --}}
    @stack('styles')
    {{-- CDN do Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Configuração Customizada do Tailwind CSS (JIT para CDN) --}}
    {{-- ATENÇÃO: Isso é para desenvolvimento com CDN. Em produção, use a build tooling do Tailwind (npm run dev/prod).
    --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                        'bai-jamjuree': ['Bai Jamjuree', 'sans-serif'],
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        // Cores customizadas gerais
                        'custom-dark-text': '#171A1FFF',
                        'custom-border-light': '#BCC1CAFF',
                        'custom-border-hover': '#A7ADB7FF',
                        'custom-border-focus': '#9095A0FF',
                        'custom-bg-general': '#E2E2CCFF',

                        // Cores para tabelas e botões de ação (Editar, Orçamento, Contato, Cancelar)
                        'table-header-bg': '#2D3748',
                        'button-edit-bg': '#34D399',
                        'button-edit-hover': '#10B981',
                        'button-budget-bg': '#F59E0B',
                        'button-budget-hover': '#D97706',
                        'button-contact-bg': '#3B82F6',
                        'button-contact-hover': '#2563EB',
                        'button-cancel-bg': '#EF4444',
                        'button-cancel-hover': '#DC2626',

                        // Cores para o Formulário de Cadastro (create.blade.php)
                        'form-bg': '#1E293B',
                        'form-text': '#F8FAFC',
                        'form-placeholder': '#94A3B8',
                        'button-save-bg': '#F97316',
                        'button-save-hover': '#EA580C',
                    },
                    boxShadow: {
                        // Sombras customizadas para elementos gerais e tabelas
                        'custom-table': '0px 4px 9px rgba(23, 26, 31, 0.11), 0px 0px 2px rgba(23, 26, 31, 0.12)',
                        'table-shadow-image': '0px 0px 10px rgba(0, 0, 0, 0.1), 0px 0px 20px rgba(0, 0, 0, 0.05)',

                        // Sombra customizada para o contêiner do Formulário de Cadastro
                        'form-shadow': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                    }
                }
            }
        }
    </script>

    {{-- Importação das fontes Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@700&family=Poppins:wght@400&display=swap"
        rel="stylesheet">

    {{-- Estilos CSS Globais e de Utilitários (Não Tailwind) --}}
    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Define a fonte padrão do corpo */
            background-color: #E2E2CC;
            /* Cor de fundo geral do corpo, correspondendo a custom-bg-general */
        }

        /* Estilo para a barra de rolagem personalizada em navegadores WebKit (Chrome, Safari) */
        ::-webkit-scrollbar {
            width: 8px;
            /* Largura da barra de rolagem vertical */
            height: 8px;
            /* Altura da barra de rolagem horizontal */
        }

        ::-webkit-scrollbar-track {
            background: #e2e8f0;
            /* Cor do trilho da barra de rolagem (Tailwind bg-gray-200) */
            border-radius: 10px;
            /* Bordas arredondadas para o trilho */
        }

        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            /* Cor do "polegar" da barra de rolagem (Tailwind bg-slate-400) */
            border-radius: 10px;
            /* Bordas arredondadas para o "polegar" */
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
            /* Cor do "polegar" ao passar o mouse (Tailwind bg-slate-600) */
        }
    </style>
</head>

<body class="min-h-screen flex flex-col overflow-y-scroll overflow-x-hidden"> {{-- CORREÇÃO: Adicionado
    overflow-y-scroll e overflow-x-hidden --}}

    {{-- Barra de Navegação Superior --}}
    <nav class="bg-gray-800 p-4 shadow-xl sticky top-0 z-50 overflow-x-auto">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-2">

            {{-- Links de Navegação Principal (lado esquerdo) --}}
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dashboard') }}"
                    class="nav-link text-gray-300 hover:bg-gray-700 hover:text-white px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 ease-in-out whitespace-nowrap hover:shadow-md">Dashboard</a>
                <a href="{{ route('empresa.index') }}"
                    class="nav-link text-gray-300 hover:bg-gray-700 hover:text-white px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 ease-in-out whitespace-nowrap hover:shadow-md">Empresas</a>
                <a href="{{ route('cliente.index') }}"
                    class="nav-link text-gray-300 hover:bg-gray-700 hover:text-white px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 ease-in-out whitespace-nowrap hover:shadow-md">Prospecções</a>
                <a href="{{ route('cliente_orcamento.index') }}"
                    class="nav-link text-gray-300 hover:bg-gray-700 hover:text-white px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 ease-in-out whitespace-nowrap hover:shadow-md">Clientes/Orçamentos</a>
                <a href="{{ route('produto.index') }}"
                    class="nav-link text-gray-300 hover:bg-gray-700 hover:text-white px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 ease-in-out whitespace-nowrap hover:shadow-md">Produtos</a>

                <a href="{{ route('preco_customizacao.index') }}"
                    class="nav-link text-gray-300 hover:bg-gray-700 hover:text-white px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 ease-in-out whitespace-nowrap hover:shadow-md">Preço
                    Customização</a>
                <a href="{{ route('financeiro.index') }}"
                    class="nav-link text-gray-300 hover:bg-gray-700 hover:text-white px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 ease-in-out whitespace-nowrap hover:shadow-md">
                    Financeiro</a>


            </div>

            {{-- Bloco da direita (Logout + Layout Camiseta) --}}
            <div class="flex items-center gap-2">
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="nav-link text-white bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 ease-in-out whitespace-nowrap hover:shadow-md">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>

                {{-- LINK PARA ADMINISTRAÇÃO DE USUÁRIOS (Cor Alterada) --}}
                <a href="{{ route('users.index') }}"
                    class="nav-link text-white bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 ease-in-out whitespace-nowrap hover:shadow-md">
                    Users
                </a>

                <a id="layout-camiseta-nav-link" href="#"
                    class="nav-link text-gray-300 hover:bg-gray-700 hover:text-white px-4 py-2 rounded-lg text-base font-medium transition-all duration-200 ease-in-out whitespace-nowrap hover:shadow-md hidden">
                    Layout Camiseta
                </a>
            </div>

        </div>
    </nav>



    {{-- Outros elementos do seu layout, como o link 'Layout Camiseta' --}}


    {{-- Conteúdo Principal da Página --}}
    <main class="flex-grow p-4 max-w-7xl mx-auto w-full"> {{-- ESSENCIAL: Permite que o conteúdo ocupe o espaço restante
        --}}
        @yield('content')
    </main>

    {{-- Rodapé da Página --}}
    <footer class="bg-gray-800 text-white text-center p-4 shadow-inner mt-auto"> {{-- ESSENCIAL: mt-auto empurra para o
        final --}}
        &copy; {{ date('Y') }} Alphamega. Todos os direitos reservados.
    </footer>

    {{-- Scripts JavaScript --}}
    @stack('scripts') {{-- MOVIDO: Apenas um @stack('scripts') no final do body --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const navLinks = document.querySelectorAll('.nav-link');
            const layoutCamisetaNavLink = document.getElementById('layout-camiseta-nav-link');

            function highlightCurrentNavLink() {

                let currentPathname = window.location.pathname;

                if (!currentPathname.endsWith('/')) {
                    currentPathname += '/';
                }

                navLinks.forEach(link => {

                    let linkPathname = new URL(link.href).pathname;

                    if (!linkPathname.endsWith('/')) {
                        linkPathname += '/';
                    }

                    let isActive = false;

                    if (
                        (
                            currentPathname.startsWith('/cliente_orcamento/') ||
                            currentPathname.startsWith('/orcamento/') ||
                            currentPathname.startsWith('/detalhes_orcamento/') ||
                            currentPathname.startsWith('/customizacao/') ||
                            currentPathname.startsWith('/contato')
                        ) &&
                        linkPathname === '/cliente_orcamento/'
                    ) {
                        isActive = true;
                    }


                    if (link.id === 'layout-camiseta-nav-link') {
                        if (currentPathname.startsWith('/camisa/show_layout/')) {
                            link.classList.remove('hidden');
                            isActive = true;
                            link.href = window.location.href;
                        } else {
                            link.classList.add('hidden');
                        }
                    }

                    // RESET VISUAL PADRÃO
                    link.classList.remove('bg-teal-700', 'text-white', 'font-semibold', 'shadow-md');
                    link.classList.add('text-gray-300');

                    // USERS (azul fixo)
                    if (linkPathname === '/users/') {
                        if (currentPathname.startsWith('/users/')) {
                            link.classList.remove('text-gray-300');
                            link.classList.add('text-white', 'bg-blue-700', 'font-semibold', 'shadow-md');
                        } else {
                            link.classList.remove('bg-teal-700', 'font-semibold', 'shadow-md');
                            link.classList.add('text-white', 'bg-blue-700', 'hover:bg-blue-800');
                        }
                        return;
                    }

                    // 🔹 REGRA NORMAL (outros links)
                    if (!isActive) {
                        if (linkPathname === '/') {
                            isActive = (currentPathname === '/');
                        } else {
                            isActive = currentPathname.startsWith(linkPathname);

                            // evita conflito cliente vs cliente_orcamento
                            if (linkPathname === '/cliente/' && currentPathname.startsWith('/cliente_orcamento/')) {
                                isActive = false;
                            }
                        }
                    }

                    if (isActive) {
                        link.classList.remove('text-gray-300');
                        link.classList.add('bg-teal-700', 'text-white', 'font-semibold', 'shadow-md');
                    }
                });
            }

            highlightCurrentNavLink();

            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    setTimeout(highlightCurrentNavLink, 50);
                });
            });

        });
    </script>
</body>

</html>