<!DOCTYPE html>
<html lang="pt-BR" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aplicação Laravel')</title>

    @stack('styles')

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@700&family=Poppins:wght@400&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'],
                        'bai-jamjuree': ['Bai Jamjuree', 'sans-serif'],
                        poppins: ['Poppins', 'sans-serif']
                    },
                    colors: {
                        'custom-dark-text': '#171A1FFF',
                        'custom-border-light': '#BCC1CAFF',
                        'custom-border-hover': '#A7ADB7FF',
                        'custom-border-focus': '#9095A0FF',
                        'custom-bg-general': '#E2E2CCFF',
                        'table-header-bg': '#2D3748',
                        'button-edit-bg': '#34D399',
                        'button-edit-hover': '#10B981',
                        'button-budget-bg': '#F59E0B',
                        'button-budget-hover': '#D97706',
                        'button-contact-bg': '#3B82F6',
                        'button-contact-hover': '#2563EB',
                        'button-cancel-bg': '#EF4444',
                        'button-cancel-hover': '#DC2626',
                        'form-bg': '#1E293B',
                        'form-text': '#F8FAFC',
                        'form-placeholder': '#94A3B8',
                        'button-save-bg': '#F97316',
                        'button-save-hover': '#EA580C',
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
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #E2D8CC;
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
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #1AB5A6;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col bg-[#E2D8CC] font-inter overflow-x-hidden">

    @php
    $homeAtivo = request()->is('dashboard*');

    $financeiroAtivo =
    request()->is('financeiro*') ||
    request()->is('forma_pagamento*') ||
    request()->is('orcamento*') ||
    request()->is('orcamento-fracionado*') ||
    request()->is('detalhes-orcamento-fracionado*') ||
    request()->is('customizacao-fracionada*');

    $cobrancaAtivo =
    request()->is('cobranca*') ||
    request()->is('detalhes_cobranca*');

    $notificacaoAtivo =
    request()->is('notificacao*');

    $fluxoCaixaAtivo =
    request()->is('fluxo_caixa*') ||
    request()->is('nota_fiscal*') ||
    request()->is('conta_bancaria*') ||
    request()->is('fluxo_nota_conta*');

    $administracaoAtivo =
    request()->is('administracao*') ||
    request()->is('produto*') ||
    request()->is('preco_customizacao*') ||
    request()->is('tipo_fluxo_caixa*') ||
    request()->is('tipo_pagamento*') ||
    request()->is('users*');
    @endphp

    <nav class="sticky top-0 z-50 bg-[#173038]/95 backdrop-blur-md border-b border-white/10 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="min-h-[72px] flex items-center justify-between gap-4">

                <div class="relative flex-1 min-w-0">
                    <div class="flex items-center gap-2 py-3 overflow-x-auto pr-2">

                        {{-- HOME --}}
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium whitespace-nowrap transition-all duration-200
                            {{ $homeAtivo ? 'bg-[#EA792D] text-white font-semibold shadow-[0_4px_10px_rgba(234,121,45,0.25)]' : 'text-[#E2D8CC] hover:bg-white/10 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm10 8h8V11h-8v10zM3 21h8v-6H3v6zm10-10h8V3h-8v8z" />
                            </svg>
                            Home
                        </a>

                        {{-- FINANCEIRO --}}
                        <a href="{{ route('financeiro.index') }}"
                            class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium whitespace-nowrap transition-all duration-200
                            {{ $financeiroAtivo ? 'bg-[#EA792D] text-white font-semibold shadow-[0_4px_10px_rgba(234,121,45,0.25)]' : 'text-[#E2D8CC] hover:bg-white/10 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h3" />
                            </svg>
                            Financeiro
                        </a>

                        {{-- COBRANÇA --}}
                        <a href="{{ route('cobranca.index') }}"
                            class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium whitespace-nowrap transition-all duration-200
                            {{ $cobrancaAtivo ? 'bg-[#EA792D] text-white font-semibold shadow-[0_4px_10px_rgba(234,121,45,0.25)]' : 'text-[#E2D8CC] hover:bg-white/10 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h18M5 5v14a2 2 0 002 2h10a2 2 0 002-2V5M8 9h8M8 13h6" />
                            </svg>
                            Cobrança
                        </a>

                        {{-- NOTIFICAÇÃO --}}
                        <a href="{{ route('notificacao.index') }}"
                            class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium whitespace-nowrap transition-all duration-200
                            {{ $notificacaoAtivo ? 'bg-[#EA792D] text-white font-semibold shadow-[0_4px_10px_rgba(234,121,45,0.25)]' : 'text-[#E2D8CC] hover:bg-white/10 hover:text-white' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 01-6 0" />
                            </svg>
                            Notificação
                        </a>

                        {{-- FLUXOS / NOTAS / SALDOS --}}
                        <div class="relative" id="fluxos-notas-menu">
                            <button type="button"
                                id="fluxos-notas-button"
                                class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium whitespace-nowrap transition-all duration-200
                                {{ $fluxoCaixaAtivo ? 'bg-[#EA792D] text-white font-semibold shadow-[0_4px_10px_rgba(234,121,45,0.25)]' : 'text-[#E2D8CC] hover:bg-white/10 hover:text-white' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M8 16l3-3 3 2 5-6" />
                                </svg>
                                Fluxos / Notas
                                <svg id="fluxos-notas-arrow" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                                </svg>
                            </button>

                            <div id="fluxos-notas-dropdown" class="fixed w-64 bg-white rounded-xl shadow-xl border border-gray-200 p-2 hidden z-[100]">
                                <a href="{{ route('fluxo_caixa.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M8 16l3-3 3 2 5-6" />
                                    </svg>
                                    Fluxos de Caixa
                                </a>

                                <a href="{{ route('nota_fiscal.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 2h9l5 5v15H6a2 2 0 01-2-2V4a2 2 0 012-2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6M9 17h6M14 2v6h6" />
                                    </svg>
                                    Notas Fiscais
                                </a>

                                <a href="{{ route('conta_bancaria.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="5" width="18" height="14" rx="2" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h3" />
                                    </svg>
                                    Contas Bancárias
                                </a>
                            </div>
                        </div>

                        {{-- ADMINISTRAÇÃO --}}
                        <div class="relative" id="administracao-menu">
                            <button type="button"
                                id="administracao-button"
                                class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium whitespace-nowrap transition-all duration-200
                                {{ $administracaoAtivo ? 'bg-[#EA792D] text-white font-semibold shadow-[0_4px_10px_rgba(234,121,45,0.25)]' : 'text-[#E2D8CC] hover:bg-white/10 hover:text-white' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.7 1.7 0 00.34 1.88l.06.06-1.9 1.9-.06-.06a1.7 1.7 0 00-1.88-.34 1.7 1.7 0 00-1.03 1.56V20h-2.68v-.09a1.7 1.7 0 00-1.03-1.56 1.7 1.7 0 00-1.88.34l-.06.06-1.9-1.9.06-.06A1.7 1.7 0 007.78 15a1.7 1.7 0 00-1.56-1.03H6V11.3h.22A1.7 1.7 0 007.78 10a1.7 1.7 0 00-.34-1.88l-.06-.06 1.9-1.9.06.06A1.7 1.7 0 0011.22 6.6 1.7 1.7 0 0012.25 5h-.25V3h2.68v.09a1.7 1.7 0 001.03 1.56 1.7 1.7 0 001.88-.34l.06-.06 1.9 1.9-.06.06A1.7 1.7 0 0019.4 8a1.7 1.7 0 001.56 1.03H21v2.68h-.04A1.7 1.7 0 0019.4 13a1.7 1.7 0 000 2z" />
                                </svg>
                                Administração
                                <svg id="administracao-arrow" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                                </svg>
                            </button>

                            <div id="administracao-dropdown" class="fixed w-64 bg-white rounded-xl shadow-xl border border-gray-200 p-2 hidden z-[100]">
                                <a href="{{ route('produto.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7H4a2 2 0 00-2 2v6a2 2 0 002 2h16a2 2 0 002-2V9a2 2 0 00-2-2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V5a2 2 0 012-2h4a2 2 0 012 2v2" />
                                    </svg>
                                    Produtos
                                </a>

                                <a href="{{ route('preco_customizacao.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18" />
                                    </svg>
                                    Preço Customização
                                </a>

                                <a href="{{ route('tipo_fluxo_caixa.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19V5m0 14h16M8 16l3-3 3 2 5-6" />
                                    </svg>
                                    Tipo Fluxo Caixa / Nota Fiscal
                                </a>

                                <a href="{{ route('tipo_pagamento.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="5" width="18" height="14" rx="2" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h3" />
                                    </svg>
                                    Tipo Pagamento
                                </a>

                                <a href="{{ route('users.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm13 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                                    </svg>
                                    Usuários
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- SAIR --}}
                <div class="shrink-0 pl-2 border-l border-white/10">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-semibold text-red-300 bg-red-500/10 border border-red-500/20 hover:bg-red-500/20 hover:text-red-200 transition-all duration-200 whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5M15 12H3m12-7h4a2 2 0 012 2v10a2 2 0 01-2 2h-4" />
                        </svg>
                        Sair
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <main class="flex-grow p-4 max-w-7xl mx-auto w-full">
        @yield('content')
    </main>

    <footer class="bg-[#173038] text-[#E2D8CC] text-center py-4 mt-auto border-t border-white/5">
        <span class="text-xs">&copy; {{ date('Y') }} Alphamega. Todos os direitos reservados.</span>
    </footer>

    @stack('scripts')
    @stack('script')

    <script>
        const menus = [{
                button: document.getElementById('fluxos-notas-button'),
                dropdown: document.getElementById('fluxos-notas-dropdown'),
                arrow: document.getElementById('fluxos-notas-arrow')
            },
            {
                button: document.getElementById('administracao-button'),
                dropdown: document.getElementById('administracao-dropdown'),
                arrow: document.getElementById('administracao-arrow')
            }
        ];

        menus.forEach(menu => {
            if (!menu.button || !menu.dropdown) return;

            menu.button.addEventListener('click', function(event) {
                event.stopPropagation();

                menus.forEach(outro => {
                    if (outro !== menu && outro.dropdown) {
                        outro.dropdown.classList.add('hidden');
                        outro.arrow?.classList.remove('rotate-180');
                    }
                });

                menu.dropdown.classList.toggle('hidden');
                menu.arrow?.classList.toggle('rotate-180');
            });

            menu.dropdown.addEventListener('click', function(event) {
                event.stopPropagation();
            });
        });

        document.addEventListener('click', function() {
            menus.forEach(menu => {
                menu.dropdown?.classList.add('hidden');
                menu.arrow?.classList.remove('rotate-180');
            });
        });
    </script>

</body>

</html>