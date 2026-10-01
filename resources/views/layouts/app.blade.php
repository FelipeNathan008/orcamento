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
                    }
                }
            }
        };
    </script>
</head>

<body class="min-h-screen flex flex-col bg-[#E2E2CC] font-inter overflow-x-hidden">
    @php
    $dashboardAtivo = request()->is('dashboard*') || request()->is('dashboard_orcamentos*');

    $empresaProspeccaoAtivo =
    request()->is('empresa*') ||
    request()->is('cliente*') && !request()->is('cliente_orcamento*');

    $clienteOrcamentoAtivo =
    request()->is('cliente_orcamento*') ||
    request()->is('cliente-orcamento*') ||
    request()->is('orcamento*') ||
    request()->is('detalhes_orcamento*') ||
    request()->is('customizacao*') ||
    request()->is('contato*');

    $fluxoAtivo =
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

    <nav class="sticky top-0 z-50 bg-slate-900/95 backdrop-blur-md border-b border-white/10 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="min-h-[72px] flex items-center justify-between gap-4">
                <div class="relative flex-1 min-w-0">
                    <div class="flex items-center gap-2 py-3 overflow-x-auto pr-2">

                        {{-- DASHBOARD --}}
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium whitespace-nowrap transition-all duration-200
                           {{ $dashboardAtivo ? 'bg-[#0f766e] text-white font-semibold shadow-[0_4px_10px_rgba(15,118,110,0.25)]' : 'text-gray-300 hover:bg-white/10' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h8V3H3v10zm10 8h8V11h-8v10zM3 21h8v-6H3v6zm10-10h8V3h-8v8z" />
                            </svg>
                            Dashboard
                        </a>

                        {{-- EMPRESAS / PROSPECÇÕES --}}
                        <div class="relative" id="empresa-prospeccao-menu">
                            <button type="button"
                                id="empresa-prospeccao-button"
                                class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium whitespace-nowrap transition-all duration-200
                                    {{ $empresaProspeccaoAtivo ? 'bg-[#0f766e] text-white font-semibold shadow-[0_4px_10px_rgba(15,118,110,0.25)]' : 'text-gray-300 hover:bg-white/10' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h2m-2 4h2m-2 4h2m4-8h2m-2 4h2m-2 4h2" />
                                </svg>
                                Empresas / Prospecções
                                <svg id="empresa-prospeccao-arrow" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                                </svg>
                            </button>

                            <div id="empresa-prospeccao-dropdown" class="fixed w-56 bg-white rounded-xl shadow-xl border border-gray-200 p-2 hidden z-[100]">
                                <a href="{{ route('empresa.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V5a2 2 0 012-2h10a2 2 0 012 2v16M9 7h2m-2 4h2m-2 4h2m4-8h2m-2 4h2m-2 4h2" />
                                    </svg>
                                    Empresas
                                </a>

                                <a href="{{ route('cliente.index') }}"
                                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm13 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                                    </svg>
                                    Prospecções
                                </a>
                            </div>
                        </div>

                        {{-- CLIENTES / ORÇAMENTOS --}}
                        <a href="{{ route('cliente_orcamento.index') }}"
                            class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium whitespace-nowrap transition-all duration-200
                           {{ $clienteOrcamentoAtivo ? 'bg-[#0f766e] text-white font-semibold shadow-[0_4px_10px_rgba(15,118,110,0.25)]' : 'text-gray-300 hover:bg-white/10' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm13 10v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" />
                            </svg>
                            Clientes / Orçamentos
                        </a>

                        {{-- FLUXOS / NOTAS / SALDOS --}}
                        <div class="relative" id="fluxos-notas-menu">
                            <button type="button"
                                id="fluxos-notas-button"
                                class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium whitespace-nowrap transition-all duration-200
        {{ $fluxoAtivo ? 'bg-[#0f766e] text-white font-semibold shadow-[0_4px_10px_rgba(15,118,110,0.25)]' : 'text-gray-300 hover:bg-white/10' }}">
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
        {{ $administracaoAtivo ? 'bg-[#0f766e] text-white font-semibold shadow-[0_4px_10px_rgba(15,118,110,0.25)]' : 'text-gray-300 hover:bg-white/10' }}">
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


                        {{-- FINANCEIRO --}}
                        <a href="{{ route('financeiro.index') }}"
                            class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium whitespace-nowrap transition-all duration-200
                           {{ request()->is('financeiro*') ? 'bg-[#0f766e] text-white font-semibold shadow-[0_4px_10px_rgba(15,118,110,0.25)]' : 'text-gray-300 hover:bg-white/10' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="5" width="18" height="14" rx="2" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h3" />
                            </svg>
                            Financeiro
                        </a>

                        {{-- LAYOUT CAMISETA --}}
                        @if(request()->is('camisa/show_layout*'))
                        <a href="{{ url()->current() }}"
                            class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-medium text-white bg-[#0f766e] font-semibold shadow-[0_4px_10px_rgba(15,118,110,0.25)] whitespace-nowrap">
                            Layout Camiseta
                        </a>
                        @endif
                    </div>
                </div>

                {{-- SAIR --}}
                <div class="shrink-0 pl-2 border-l border-white/10">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                        class="flex items-center gap-2 px-3.5 py-2.5 rounded-lg text-sm font-semibold text-red-300 bg-red-500/10 border border-red-500/20 hover:bg-red-500/20 hover:text-red-200 transition-all duration-200 whitespace-nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 17l5-5-5-5M15 12H3m12-7h4a2 2 0 012 2v10a2 2 0 01-2 2h-4" />
                        </svg>
                        Sair
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow p-4 max-w-7xl mx-auto w-full">
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-gray-400 text-center py-4 mt-auto border-t border-white/5">
        <span class="text-xs">&copy; {{ date('Y') }} Alphamega. Todos os direitos reservados.</span>
    </footer>

    @stack('scripts')

    <script>
        const menus = [{
                button: document.getElementById('empresa-prospeccao-button'),
                dropdown: document.getElementById('empresa-prospeccao-dropdown'),
                arrow: document.getElementById('empresa-prospeccao-arrow')
            },
            {
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