@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">

        <x-page-header title="Editar Produto" :back-url="$urlVoltar" />

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <form id="produtoForm" action="{{ route('produto.update', CryptHelper::encrypt($produto->id_produto)) }}" method="POST" class="px-6 sm:px-8 pt-6 pb-8">
            @csrf
            @method('PUT')

            <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 sm:p-6">

                <div class="flex items-center gap-3 pb-5 mb-6 border-b border-gray-200">
                    <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-orange-100 text-orange-600">
                        <x-icons.document class="w-5 h-5" />
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Dados do produto</h2>
                        <p class="text-xs text-gray-500 mt-0.5">Atualize as informações cadastrais do produto.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div class="md:col-span-2">
                        <label for="prod_nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Nome do Produto
                        </label>
                        <input type="text" name="prod_nome" id="prod_nome"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            placeholder="Nome completo do produto" maxlength="85"
                            value="{{ old('prod_nome', $produto->prod_nome) }}" required>
                    </div>

                    <div>
                        <label for="prod_familia" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Família
                        </label>
                        <select name="prod_familia" id="prod_familia"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            required>
                            <option value="" class="text-gray-400">Selecione a Família</option>
                            @php
                            $familias = [
                            'Linha Social' => 'Linha Social',
                            'Linha Operacional' => 'Linha Operacional',
                            'Linha Gastronômica' => 'Linha Gastronômica',
                            'Linha Inverno' => 'Linha Inverno',
                            ];
                            $selectedFamilia = old('prod_familia', $produto->prod_familia);
                            @endphp
                            @foreach ($familias as $familiaValue => $familiaName)
                            <option value="{{ $familiaValue }}" {{ $selectedFamilia == $familiaValue ? 'selected' : '' }}>
                                {{ $familiaName }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="prod_categoria" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Categoria
                        </label>
                        <select name="prod_categoria" id="prod_categoria"
                            data-categoria-selecionada="{{ old('prod_categoria', $produto->prod_categoria) }}"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 disabled:bg-gray-100 disabled:text-gray-400"
                            required disabled>
                            <option value="" class="text-gray-400">Selecione a Categoria</option>
                        </select>
                    </div>

                    <div>
                        <label for="prod_cod" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Código
                        </label>
                        <input type="text" name="prod_cod" id="prod_cod"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 disabled:bg-gray-100 disabled:text-gray-400"
                            placeholder="Código do produto" maxlength="45"
                            value="{{ old('prod_cod', $produto->prod_cod) }}" required disabled>
                    </div>

                    <div>
                        <label for="prod_material" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Material
                        </label>
                        <input type="text" name="prod_material" id="prod_material"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 disabled:bg-gray-100 disabled:text-gray-400"
                            placeholder="Ex: 100% Algodão, Poliéster" maxlength="45"
                            value="{{ old('prod_material', $produto->prod_material) }}" required disabled>
                    </div>

                    <div>
                        <label for="prod_preco" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Preço
                        </label>
                        <input type="text" id="prod_preco"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 disabled:bg-gray-100 disabled:text-gray-400"
                            placeholder="R$ 0,00"
                            value="{{ old('prod_preco', $produto->prod_preco) }}" required disabled>
                        <input type="hidden" name="prod_preco" id="prod_preco_hidden"
                            value="{{ old('prod_preco', $produto->prod_preco) }}">
                    </div>

                    <div>
                        <label for="prod_genero" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Gênero
                        </label>
                        <select name="prod_genero" id="prod_genero"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 disabled:bg-gray-100 disabled:text-gray-400"
                            required disabled>
                            <option value="" class="text-gray-400">Selecione o Gênero</option>
                            @php
                            $generos = ['Masculino' => 'Masculino', 'Feminino' => 'Feminino', 'Unissex' => 'Unissex'];
                            $selectedGenero = old('prod_genero', $produto->prod_genero);
                            @endphp
                            @foreach ($generos as $generosValue => $generosName)
                            <option value="{{ $generosValue }}" {{ $selectedGenero == $generosValue ? 'selected' : '' }}>
                                {{ $generosName }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="prod_modelo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Modelo
                        </label>
                        <input type="text" name="prod_modelo" id="prod_modelo"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 disabled:bg-gray-100 disabled:text-gray-400"
                            placeholder="Ex: Piquet, Listrado com Botões" maxlength="70"
                            value="{{ old('prod_modelo', $produto->prod_modelo) }}" required disabled>
                    </div>

                    <div>
                        <label for="prod_cor" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Cor
                        </label>
                        <input type="text" name="prod_cor" id="prod_cor"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100 disabled:bg-gray-100 disabled:text-gray-400"
                            placeholder="Ex: Azul, Preto, Branco" maxlength="20"
                            value="{{ old('prod_cor', $produto->prod_cor) }}" required disabled>
                    </div>

                    <div>
                        <label for="prod_caract_display" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Características
                        </label>
                        <input type="text" id="prod_caract_display"
                            class="w-full h-11 px-3 text-sm text-gray-700 bg-gray-100 border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100"
                            value="{{ old('prod_caract', $produto->prod_caract) }}" maxlength="55" disabled>
                        <input type="hidden" name="prod_caract" id="prod_caract_hidden"
                            value="{{ old('prod_caract', $produto->prod_caract) }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">
                            Tamanhos Disponíveis
                        </label>
                        <details id="prod_tamanho_details" class="border border-gray-300 rounded-lg bg-white disabled:opacity-60">
                            <summary class="h-11 flex items-center px-3 text-sm text-gray-700 cursor-pointer rounded-lg hover:bg-gray-50 transition">
                                Selecione os tamanhos disponíveis
                            </summary>
                            <div id="prod_tamanho_checkboxes" class="flex flex-wrap gap-x-6 gap-y-3 px-4 py-4 border-t border-gray-200">
                                @php
                                $sizes = ['PP', 'P', 'M', 'G', 'GG', 'EG', 'G1', 'G2', 'G3', 'G4', 'G5', 'Único'];
                                $selectedSizes = old('prod_tamanho', $produto->prod_tamanho);
                                if (!is_array($selectedSizes)) $selectedSizes = explode(',', (string) $selectedSizes);
                                $selectedSizes = array_map('trim', $selectedSizes);
                                @endphp
                                @foreach ($sizes as $size)
                                <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-gray-700">
                                    <input type="checkbox" name="prod_tamanho[]" value="{{ $size }}"
                                        class="form-checkbox h-4 w-4 text-orange-600 rounded focus:ring-orange-500 border-gray-300"
                                        id="prod_tamanho_{{ $size }}"
                                        {{ in_array($size, $selectedSizes) ? 'checked' : '' }} disabled>
                                    <span>{{ $size }}</span>
                                </label>
                                @endforeach
                            </div>
                        </details>
                    </div>

                </div>
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6 pt-5 border-t border-gray-200">
                <x-secondary-button :href="$urlVoltar">
                    Voltar para a lista
                </x-secondary-button>
                <x-primary-button type="submit" id="btnSalvarProduto" class="px-6">
                    <span id="textoSalvar">Atualizar produto</span>
                </x-primary-button>
            </div>
        </form>
    </div>
</div>

@push('scripts')

<script>
    const form = document.getElementById('produtoForm');
    const btnSalvar = document.getElementById('btnSalvarProduto');
    const textoSalvar = document.getElementById('textoSalvar');

    document.addEventListener('DOMContentLoaded', function() {
        const familia = document.getElementById('prod_familia');
        const categoria = document.getElementById('prod_categoria');
        const codigo = document.getElementById('prod_cod');
        const material = document.getElementById('prod_material');
        const preco = document.getElementById('prod_preco');
        const precoHidden = document.getElementById('prod_preco_hidden');
        const genero = document.getElementById('prod_genero');
        const modelo = document.getElementById('prod_modelo');
        const caractDisplay = document.getElementById('prod_caract_display');
        const caractHidden = document.getElementById('prod_caract_hidden');
        const tamanhos = document.getElementById('prod_tamanho_checkboxes');
        const tamanhoDetails = document.getElementById('prod_tamanho_details');
        const cor = document.getElementById('prod_cor');

        function formatarMoeda(input) {
            let valor = input.value.replace(/\D/g, '');
            if (!valor) {
                input.value = '';
                return;
            }
            valor = valor.replace(/^0+(?=\d)/, '');
            input.value = (parseInt(valor, 10) / 100).toLocaleString('pt-BR', {
                style: 'currency',
                currency: 'BRL',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        function moedaParaBanco(valor) {
            if (!valor) return '';
            return valor.replace('R$', '').replace(/\s/g, '').replace(/\./g, '').replace(',', '.');
        }

        function inicializarPreco() {
            const valor = preco.value.trim();
            if (!valor) return;

            let numero = valor.replace('R$', '').replace(/\s/g, '');

            if (numero.includes(',')) {
                numero = numero.replace(/\./g, '').replace(',', '.');
            }
            numero = parseFloat(numero);
            if (!isNaN(numero)) {
                preco.value = numero.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });

                precoHidden.value = numero.toFixed(2);
            }
        }

        function caracteristicas(f, c) {
            if (f === 'Linha Operacional' && c === 'Jaleco') return ['Com Bolso', 'Sem Bolso'];
            if (f === 'Linha Operacional' && ['Jaleco de Brim', 'Calças de Brim', 'Calças Jeans', 'Calça Bailarina', 'Calça Sarja'].includes(c)) return ['-'];
            if (f === 'Linha Operacional' && ['Camiseta Manga Curta', 'Camiseta Baby Look', 'Camiseta Manga Longa', 'Camiseta Polo'].includes(c)) return ['Com Bolso', 'Sem Bolso'];
            if (f === 'Linha Social' && ['Camisas', 'Camisetes', 'Camisetes com Detalhes'].includes(c)) return ['Manga Curta', 'Manga Longa', 'Com Bolso', 'Sem Bolso'];
            return ['-'];
        }

        function atualizarCategorias(selected = '') {
            const categorias = {
                'Linha Social': ['Camisetes', 'Camisas', 'Camisetes com Detalhes', 'Blazer', 'Vestido Tubinho', 'Calça Social'],
                'Linha Operacional': ['Camiseta Manga Curta', 'Camiseta Baby Look', 'Camiseta Manga Longa', 'Camiseta Polo', 'Jaleco', 'Jaleco de Brim', 'Calças de Brim', 'Calças Jeans', 'Calça Bailarina', 'Calça Sarja'],
                'Linha Gastronômica': ['Avental Peito', 'Meio Avental', 'Avental Gourmet', 'Touca Sushiman', 'Touca Telinha', 'Dolmã'],
                'Linha Inverno': ['Jaqueta tactel com forro tactel', 'Jaqueta tactel com forro matelassê', 'Blusa Helanca Flanelada', 'Blusa de Lã']
            };
            categoria.innerHTML = '\<option value="">Selecione a Categoria\</option>';

            (categorias[familia.value] || []).forEach(item => {
                categoria.appendChild(new Option(item, item, false, item === selected));
            });
        }

        function habilitarCampo(campo, habilitar) {
            campo.disabled = !habilitar;
            if (!habilitar) {
                campo.value = '';
                if (campo.tagName === 'SELECT') campo.selectedIndex = 0;
            }
        }

        function atualizarCampos() {
            const temFamilia = familia.value !== '';
            const temCategoria = categoria.value !== '';

            habilitarCampo(categoria, temFamilia);

            const lista = caracteristicas(familia.value, categoria.value);
            caractHidden.value = temCategoria ? lista.join(', ') : '';
            caractDisplay.value = caractHidden.value;

            codigo.disabled = !temFamilia;
            material.disabled = !temFamilia;
            preco.disabled = !temFamilia;
            genero.disabled = !temFamilia;
            modelo.disabled = !temFamilia;
            cor.disabled = !temFamilia;

            tamanhos.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
                checkbox.disabled = !temCategoria;
            });

            tamanhoDetails.open = temCategoria;
        }

        preco.addEventListener('input', () => formatarMoeda(preco));
        preco.addEventListener('blur', () => {
            if (preco.value) formatarMoeda(preco);
        });

        familia.addEventListener('change', function() {
            atualizarCategorias();
            atualizarCampos();
        });

        categoria.addEventListener('change', atualizarCampos);

        tamanhos.addEventListener('change', function(event) {
            if (event.target.type !== 'checkbox') return;

            const unico = document.getElementById('prod_tamanho_Único');
            const outros = tamanhos.querySelectorAll('input[type="checkbox"]\:not(#prod_tamanho_Único)');

            if (event.target.id === 'prod_tamanho_Único') {
                outros.forEach(checkbox => {
                    checkbox.checked = false;
                    checkbox.disabled = unico.checked;
                });
            } else if (event.target.checked) {
                unico.checked = false;
                unico.disabled = true;
            } else if (![...outros].some(checkbox => checkbox.checked)) {
                unico.disabled = false;
            }
        });

        form.addEventListener('submit', function(event) {
            document.querySelectorAll('.error-message').forEach(element => element.remove());
            let valido = true;

            form.querySelectorAll('input:enabled, select:enabled').forEach(field => {
                if (field.required && !field.value.trim()) {
                    valido = false;
                    const erro = document.createElement('p');
                    erro.className = 'mt-2 text-sm text-red-600 error-message';
                    erro.innerHTML = '<strong>Preencha este campo!</strong>';
                    field.closest('div').appendChild(erro);
                    field.classList.add('border-red-500');
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            const checkboxes = tamanhos.querySelectorAll('input[type="checkbox"]:enabled');
            if (checkboxes.length && ![...checkboxes].some(checkbox => checkbox.checked)) {
                valido = false;
                const erro = document.createElement('p');
                erro.className = 'mt-2 text-sm text-red-600 error-message';
                erro.innerHTML = '<strong>Selecione pelo menos um tamanho!</strong>';
                tamanhoDetails.after(erro);
                tamanhoDetails.classList.add('border-red-500');
            } else {
                tamanhoDetails.classList.remove('border-red-500');
            }

            if (!valido) {
                event.preventDefault();
                const primeiroErro = document.querySelector('.error-message');
                if (primeiroErro) {
                    window.scrollTo({
                        top: primeiroErro.getBoundingClientRect().top + window.scrollY - 100,
                        behavior: 'smooth'
                    });
                }
                return;
            }

            if (preco.value) precoHidden.value = moedaParaBanco(preco.value);

            if (btnSalvar.disabled) {
                event.preventDefault();
                return;
            }

            btnSalvar.disabled = true;
            textoSalvar.innerText = 'Atualizando...';
            btnSalvar.classList.add('opacity-70', 'cursor-not-allowed');
        });

        atualizarCategorias(categoria.dataset.categoriaSelecionada);
        atualizarCampos();
        inicializarPreco();

        const unico = document.getElementById('prod_tamanho_Único');
        const outros = tamanhos.querySelectorAll('input[type="checkbox"]:not(#prod_tamanho_Único)');

        if (unico.checked) {
            outros.forEach(checkbox => checkbox.disabled = true);
        } else if ([...outros].some(checkbox => checkbox.checked)) {
            unico.disabled = true;
        }
    });
</script>

@endpush
@endsection