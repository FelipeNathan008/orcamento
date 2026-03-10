<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplicação Laravel')</title>

    @stack('styles')
    <script src="https://cdn.tailwindcss.com"></script>

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

                        // Cores para tabelas e botões
                        'table-header-bg': '#2D3748',
                        'button-edit-bg': '#34D399',
                        'button-edit-hover': '#10B981',
                        'button-budget-bg': '#F59E0B',
                        'button-budget-hover': '#D97706',
                        'button-contact-bg': '#3B82F6',
                        'button-contact-hover': '#2563EB',
                        'button-cancel-bg': '#EF4444',
                        'button-cancel-hover': '#DC2626',

                        // Cores para formulários
                        'form-bg': '#1E293B',
                        'form-text': '#F8FAFC',
                        'form-placeholder': '#94A3B8',
                        'button-save-bg': '#F97316',
                        'button-save-hover': '#EA580C',

                        // Paleta financeira (opcional)
                        'azul-confianca': '#173038',
                        'bege-construcao': '#E2D8CC',
                        'verde-principal': '#1AB5A6',
                        'amarelo-principal': '#FFC931',
                        'laranja-seguranca': '#EA792D',
                    },
                    boxShadow: {
                        'custom-table': '0px 4px 9px rgba(23, 26, 31, 0.11), 0px 0px 2px rgba(23, 26, 31, 0.12)',
                        'table-shadow-image': '0px 0px 10px rgba(0, 0, 0, 0.1), 0px 0px 20px rgba(0, 0, 0, 0.05)',
                        'form-shadow': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                    },
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #E2D8CC;
            /* Bege Construção */
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #E2D8CC;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb {
            background: #173038;
            /* Azul Confiança */
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1AB5A6;
            /* Verde */
        }
    </style>
</head>

<body class="min-h-screen flex flex-col overflow-y-scroll overflow-x-hidden">

    {{-- Barra de Navegação Superior --}}
    <nav class="bg-azul-confianca p-4 shadow-xl sticky top-0 z-50 overflow-x-auto">
        <div class="max-w-7xl mx-auto flex justify-between items-center gap-2">

            <div class="flex flex-wrap gap-2">

                <a href="{{ route('empresa.index') }}"
                    class="nav-link text-bege-construcao hover:bg-laranja-seguranca hover:text-white px-4 py-2 rounded-lg transition">
                    Empresas
                </a>

                <a href="{{ route('cliente_orcamento.index') }}"
                    class="nav-link text-bege-construcao hover:bg-laranja-seguranca hover:text-white px-4 py-2 rounded-lg transition">
                    Clientes
                </a>

                <a href="{{ route('financeiro.index') }}"
                    class="nav-link text-bege-construcao hover:bg-laranja-seguranca hover:text-white px-4 py-2 rounded-lg transition">
                    Financeiro
                </a>

                <a href="{{ route('cobranca.index') }}"
                    class="nav-link text-bege-construcao hover:bg-laranja-seguranca hover:text-white px-4 py-2 rounded-lg transition">
                    Cobrança
                </a>

                 <a href="{{ route('notificacao.index') }}"
                    class="nav-link text-bege-construcao hover:bg-laranja-seguranca hover:text-white px-4 py-2 rounded-lg transition">
                    Notificação
                </a>
                <a href="{{ route('tipo_pagamento.index') }}"
                    class="nav-link text-bege-construcao hover:bg-laranja-seguranca hover:text-white px-4 py-2 rounded-lg transition">
                    Tipo Pagamento
                </a>

            </div>

            <div class="flex items-center gap-2">

                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="nav-link text-white bg-laranja-seguranca hover:bg-[#c75a20] px-4 py-2 rounded-lg transition">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>

                <a href="{{ route('users.index') }}"
                    class="nav-link text-white bg-verde-principal hover:bg-[#149085] px-4 py-2 rounded-lg transition">
                    Usuários
                </a>

            </div>

        </div>
    </nav>

    {{-- Conteúdo Principal --}}
    <main class="flex-grow p-4 max-w-7xl mx-auto w-full">
        @yield('content')
    </main>

    {{-- Rodapé --}}
    <footer class="bg-azul-confianca text-bege-construcao text-center p-4 shadow-inner mt-auto">
        &copy; {{ date('Y') }} Alphamega. Todos os direitos reservados.
    </footer>

    @stack('scripts')
</body>
@stack('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        const navLinks = document.querySelectorAll('.nav-link');

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

                // Financeiro inclui forma_pagamento
                if (
                    (
                        currentPathname.startsWith('/financeiro/') ||
                        currentPathname.startsWith('/forma_pagamento/')) &&
                    linkPathname === '/financeiro/'
                ) {
                    isActive = true;
                }

                if (
                    (
                        currentPathname.startsWith('/cobranca/') ||
                        currentPathname.startsWith('/detalhes_cobranca/')
                    ) &&
                    linkPathname === '/cobranca/'
                ) {
                    isActive = true;
                }

                if (!isActive) {

                    if (linkPathname === '/') {
                        isActive = (currentPathname === '/');
                    } else {
                        isActive = currentPathname.startsWith(linkPathname);
                    }

                }

                link.classList.remove('bg-laranja-seguranca', 'text-white', 'font-semibold', 'shadow-md');
                link.classList.add('text-bege-construcao');

                if (isActive) {
                    link.classList.remove('text-bege-construcao');
                    link.classList.add('bg-laranja-seguranca', 'text-white', 'font-semibold', 'shadow-md');
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

</html>