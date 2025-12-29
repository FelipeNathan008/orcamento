@extends('layouts.app') {{-- Assume que você tem um layout principal chamado 'app'. --}}

@section('title', 'Layout da Camisa')

@section('content')
    <div class="max-w-4xl mx-auto p-8 mt-10 mb-10 bg-white shadow-lg rounded-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Layout Customizado da Camisa</h1>

        @if(isset($customizacao))
            {{-- Exibe o nome do produto e o ID do detalhe do orçamento ao qual esta customização pertence. --}}
            <p class="text-gray-600 mb-2">Produto: <span
                    class="font-semibold">{{ $customizacao->detalhesOrcamento->produto->prod_nome ?? 'Nome do Produto Desconhecido' }}</span>
                     -  <span
                    class="font-semibold">{{ $customizacao->detalhesOrcamento->det_categoria ?? 'Categoria do Produto Desconhecido' }}</span>
                     -  <span
                    class="font-semibold">{{ $customizacao->detalhesOrcamento->det_cor ?? 'Cor do Produto Desconhecido' }}</span>
                     -  <span
                    class="font-semibold">{{ $customizacao->detalhesOrcamento->det_tamanho ?? 'Tamaho do Produto Desconhecido' }}</span>
            </p>
            <p class="text-gray-600 mb-2">ID do Detalhe do Orçamento: <span
                    class="font-semibold">{{ $customizacao->detalhes_orcamento_id_det }}</span></p>

            {{-- Este bloco busca e lista TODAS as customizações que estão associadas ao MESMO detalhe de orçamento
            da customização que está sendo visualizada. Isso é útil para ver todas as customizações de um único produto. --}}
            @php
                // Obtém o ID do detalhe do orçamento da customização atual.
                $detalheId = $customizacao->detalhes_orcamento_id_det;
                // Busca todas as customizações que compartilham este 'detalhes_orcamento_id_det'.
                $allCustomizacoesForDetail = \App\Models\Customizacao::where('detalhes_orcamento_id_det', $detalheId)->get();
            @endphp

            @if($allCustomizacoesForDetail->isNotEmpty())
                {{-- Nova linha para exibir a quantidade de customizações --}}
                <p class="text-gray-600 mb-2">Quantidade de Customizações: <span
                        class="font-semibold">{{ $allCustomizacoesForDetail->count() }}</span></p>
                <p class="text-gray-600 mt-4 mb-2">IDs de Customizações Referentes a este Detalhe:</p>
                <ul class="list-disc list-inside ml-4 text-gray-800">
                    @foreach($allCustomizacoesForDetail as $cust)
                        <li class="flex items-center justify-between mb-2"> {{-- Adicionado flexbox para alinhar itens e botões --}}
                            <div>
                                ID: <span class="font-medium">{{ $cust->id_customizacao }}</span> -
                                Posição: <span class="font-medium">{{ $cust->cust_posicao }}</span> -
                                Local: <span class="font-medium">{{ $cust->cust_local }}</span>
                            </div>
                            <div class="flex space-x-2"> {{-- Container para os botões --}}
                                <a href="{{ route('customizacao.show', $cust->id_customizacao) }}"
                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                                    Ver
                                </a>
                                <a href="{{ route('customizacao.edit', $cust->id_customizacao) }}"
                                    class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-yellow-500 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 ease-in-out">
                                    Editar
                                </a>
                                <form action="{{ route('customizacao.destroy', $cust->id_customizacao) }}"
                                    method="POST" class="inline-block"
                                    onsubmit="return confirm('Tem certeza que deseja excluir esta customização?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-2 py-1 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-150 ease-in-out">
                                        Excluir
                                    </button>
                                </form>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-gray-600 mt-4">Nenhuma outra customização encontrada para este detalhe.</p>
            @endif

            {{--- INÍCIO DO CÓDIGO DA CAMISA: SEÇÃO DE VISUALIZAÇÃO ---}}
            <div class="mt-8 p-4 border rounded-lg flex flex-col items-center">
                <h2 class="text-xl font-semibold mb-4 text-center">Visualização da Camisa</h2>

                @php
                    // As variáveis abaixo representam as diferentes "áreas" na camisa onde as customizações podem aparecer.
                    // Elas são inicializadas como strings vazias e serão preenchidas com o conteúdo da customização (imagem ou texto)
                    // se houver uma customização mapeada para aquela área.
                    $ombro_dir = '';
                    $ombro_esq = '';
                    $frente_pos1 = '';
                    $frente_pos2 = '';
                    $frente_pos3 = '';
                    $frente_pos4 = '';
                    $frente_pos5 = '';
                    $frente_pos6 = '';
                    $frente_pos7 = '';
                    $frente_pos8 = '';
                    $frente_pos9 = '';
                    $costa_pos1 = '';
                    $costa_pos2 = '';
                    $costa_pos3 = '';
                    $costa_pos4 = '';
                    $costa_pos5 = '';
                    $costa_pos6 = '';
                    $costa_pos7 = '';
                    $costa_pos8 = '';
                    $costa_pos9 = '';

                    // Mapeamento que conecta os valores de 'cust_local' e 'cust_posicao'
                    // (como salvos no banco de dados) às variáveis PHP que representam as áreas visuais da camisa.
                    // Isso permite que o sistema saiba onde "desenhar" cada customização.
                    $positionMap = [
                        'Ombro' => [
                            'Direito' => 'ombro_dir',
                            'Esquerdo' => 'ombro_esq',
                        ],
                        'Frente' => [
                            'Esquerdo' => 'frente_pos1',
                            'Posição 2' => 'frente_pos2',
                            'Direito' => 'frente_pos3',
                            'Posição 4' => 'frente_pos4',
                            'Centro' => 'frente_pos5',
                            'Posição 6' => 'frente_pos6',
                            'Posição 7' => 'frente_pos7',
                            'Posição 8' => 'frente_pos8',
                            'Posição 9' => 'frente_pos9',
                        ],
                        'Costa' => [
                            'Posição 1' => 'costa_pos1',
                            'Topo' => 'costa_pos2',
                            'Posição 3' => 'costa_pos3',
                            'Posição 4' => 'costa_pos4',
                            'Centro' => 'costa_pos5',
                            'Posição 6' => 'costa_pos6',
                            'Posição 7' => 'costa_pos7',
                            'Rodapé' => 'costa_pos8',
                            'Posição 9' => 'costa_pos9',
                        ],
                    ];
                @endphp

                {{-- Itera sobre CADA customização encontrada para o detalhe de orçamento atual. --}}
                @foreach($allCustomizacoesForDetail as $cust)
                    @php
                        $content = ''; // Variável para armazenar o HTML da imagem ou o texto, inicializada como string vazia.
                        $isRodapePosition = ($cust->cust_local === 'Costa' && $cust->cust_posicao === 'Rodapé');

                        // Determina se a customização é para a frente ou para as costas
                        $isFrente = ($cust->cust_local === 'Frente' || $cust->cust_local === 'Ombro');
                        $isCosta = ($cust->cust_local === 'Costa');

                        // Prioriza a imagem se ela existir.
                        if (!empty($cust->cust_imagem)) {
                            if ($isFrente) {
                                // Frente: 150x160
                                $content = '<img src="data:image/jpeg;base64,' . base64_encode($cust->cust_imagem) . '" alt="' . $cust->cust_local . ' ' . $cust->cust_posicao . '" class="custom-image" style="width:230px; height:110px; object-fit:contain;" />';
                            } elseif ($isCosta) {
                                // Costa: 150x100
                                $content = '<img src="data:image/jpeg;base64,' . base64_encode($cust->cust_imagem) . '" alt="' . $cust->cust_local . ' ' . $cust->cust_posicao . '" class="custom-image" style="width:250px; height:100px; object-fit:contain;" />';
                            }
                        } else {
                            // Se NÃO há imagem e a posição é o Rodapé, mostra APENAS o ID da customização.
                            if ($isRodapePosition) {
                                $content = '<span class="text-xs text-gray-700">' . $cust->id_customizacao . '</span>';
                            } else {
                                // Para todas as outras posições SEM imagem, mostra o ID, Tipo e Tamanho da customização.
                                $content = '<span class="text-xs text-gray-700">' . $cust->id_customizacao . '</span>';
                                $content .= '<br><span class="text-xs text-gray-700">Tipo: ' . $cust->cust_tipo . '</span>';
                                if (!empty($cust->cust_tamanho)) {
                                    $content .= '<br><span class="text-xs text-gray-700">Tamanho: ' . $cust->cust_tamanho . '</span>';
                                }
                            }
                        }

                        // Usando o mapeamento, atribui o '$content' gerado à variável PHP correta
                        // que representa a área visual na camisa (ex: $frente_pos1, $ombro_dir).
                        if (isset($positionMap[$cust->cust_local]) && isset($positionMap[$cust->cust_local][$cust->cust_posicao])) {
                            $variableName = $positionMap[$cust->cust_local][$cust->cust_posicao];
                            $$variableName = $content; // Atribuição dinâmica da variável (ex: $frente_pos1 = $content).
                        }
                    @endphp
                @endforeach

                {{-- --- VISUALIZAÇÃO DA FRENTE DA CAMISA --- --}}
                <h3 class="text-xl font-semibold text-gray-700 mt-6 mb-4 text-center">FRENTE CAMISA</h3>
                <div class="camisa-container-frente">
                    <div class="div-pescoco"></div> {{-- Simula o pescoço/gola da camisa. --}}

                    <div class="manga-esquerda">
                        @if($ombro_dir) {!! $ombro_dir !!} @endif
                    </div>

                    <div class="manga-direita">
                        @if($ombro_esq) {!! $ombro_esq !!} @endif
                    </div>

                    <div class="div-frente" style="grid-template-columns: 1fr 1fr; grid-template-rows: 1fr 1.3fr;">
                        <div class="frente-area">{!! $frente_pos1 !!}</div> {{-- Área superior esquerda --}}

                        <div class="frente-area">{!! $frente_pos3 !!}</div> {{-- Área superior direita --}}
                        <div class="frente-area frente-area-rowspan"
                            style="grid-column: 1 / span 2; display: flex; justify-content: center; align-items: center; flex-direction: column;">
                            @if($frente_pos4) {!! $frente_pos4 !!} @endif
                            @if($frente_pos5) {!! $frente_pos5 !!} @endif
                            @if($frente_pos6) {!! $frente_pos6 !!} @endif
                        </div>
                        <div class="frente-area" style="grid-column: 1 / span 3; border-top;">
                            @if($frente_pos7){!! $frente_pos7 !!}@endif
                            @if($frente_pos8){!! $frente_pos8 !!}@endif
                            @if($frente_pos9){!! $frente_pos9 !!}@endif
                        </div>
                    </div>
                </div>

                {{-- --- VISUALIZAÇÃO DA COSTA DA CAMISA --- --}}
                <h3 class="text-xl font-semibold text-gray-700 mt-8 mb-4 text-center">COSTA CAMISA</h3>
                <div class="camisa-container-costa">
                    <div class="div-pescoco"></div> {{-- Simula o pescoço/gola da camisa (para a costa). --}}

                    <div class="manga-esquerda"> {{-- Manga Esquerda (visual na costa, geralmente vazia). --}}
                    </div>

                    <div class="manga-direita"> {{-- Manga Direita (visual na costa, geralmente vazia). --}}
                    </div>

                    <div class="div-costa">
                        <div class="costa-area costa-area-topo" style="grid-column:1 / span3;">
                            @if($costa_pos1){!! $costa_pos1 !!}@endif
                            @if($costa_pos2){!! $costa_pos2 !!}@endif
                            @if($costa_pos3){!! $costa_pos3 !!}@endif
                        </div>
                        <div class="costa-area costa-area-centro" style="grid-column:1 / span3;">
                            @if($costa_pos4){!! $costa_pos4 !!}@endif
                            @if($costa_pos5){!! $costa_pos5 !!}@endif
                            @if($costa_pos6){!! $costa_pos6 !!}@endif
                        </div>
                        <div class="costa-area costa-area-rodape" style="grid-column:1 / span 3;">
                            @if($costa_pos7){!! $costa_pos7 !!}@endif
                            @if($costa_pos8){!! $costa_pos8 !!}@endif
                            @if($costa_pos9){!! $costa_pos9 !!}@endif
                        </div>
                    </div>
                </div>

            </div>
            {{--- FIM DO CÓDIGO DA CAMISA: SEÇÃO DE VISUALIZAÇÃO ---}}

        @else
            <p class="text-gray-600">Nenhuma customização selecionada para exibir o layout.</p>
        @endif

        {{-- Botão para retornar à lista de customizações, mantendo os filtros. --}}
        <div class="mt-8">
            <a href="{{ route('customizacao.index', [
                'search_detalhes_id' => $customizacao->detalhesOrcamento->id_det,
                'prod_nome_from_list' => $customizacao->detalhesOrcamento->produto->prod_nome ?? 'Nome do Produto Desconhecido',
                'prod_categoria_from_list' => $customizacao->detalhesOrcamento->produto->prod_categoria ?? 'Categoria Desconhecida'
            ]) }}"
                class="inline-flex items-center justify-center py-2 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                Voltar para Lista de Customizações
            </a>
        </div>
    </div>

    <style>
        /* Define a fonte 'Inter' para todo o documento, garantindo consistência visual. */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Estilos gerais para os contêineres que representam a frente e a costa da camisa. */
        .camisa-container-frente,
        .camisa-container-costa {
            position: relative;
            /* Permite posicionar elementos filhos de forma absoluta dentro dele. */
            width: 300px;
            /* Largura fixa para a visualização da camisa. */
            height: 400px;
            /* Altura fixa para a visualização da camisa. */
            background-color: #fff;
            /* Fundo branco para a camisa. */
            border: 2px solid #333;
            /* Borda escura para definir o contorno da camisa. */
            border-radius: 5px;
            /* Cantos levemente arredondados para a camisa. */
            display: flex;
            /* Usa Flexbox para organizar o pescoço e o corpo da camisa verticalmente. */
            flex-direction: column;
            /* Coloca os itens internos (pescoço, div-frente/costa) em uma coluna. */
            margin: 20px auto;
            /* Centraliza a camisa horizontalmente e adiciona margem vertical. */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            /* Adiciona uma sombra suave para profundidade. */
            overflow: visible;
            /* Essencial para que as mangas possam se estender para fora do contêiner principal. */
            z-index: 0;
            /* Define a ordem de empilhamento (fundo). */
        }

        .manga-esquerda,
        .manga-direita {
            position: absolute;
            width: 150px;
            height: 100px;
            background-color: #ffffffff;
            border: 2px solid #333;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 2em;
            font-weight: bold;
            overflow: hidden;
        }

        /* Manga esquerda: cantos arredondados só na esquerda */
        .manga-esquerda {
            top: -5px;
            left: -130px;
            transform: rotate(-20deg);
            transform-origin: 100% 50%;

            border-top-left-radius: 10px;
            border-bottom-left-radius: 10px;

            /* Zera os cantos da direita */
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        /* Manga direita: cantos arredondados só na direita */
        .manga-direita {
            top: -5px;
            right: -130px;
            transform: rotate(20deg);
            transform-origin: 0% 50%;

            border-top-right-radius: 10px;
            border-bottom-right-radius: 10px;

            /* Zera os cantos da esquerda */
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }


        /* Estilos para as divs que contêm a grade de áreas da frente e da costa da camisa. */
        .div-frente,
        .div-costa {
            background-color: #fff;
            flex-grow: 1;
            /* Permite que a grade ocupe o espaço vertical restante dentro do contêiner da camisa. */
            display: grid;
            /* Define um layout de grade. */
            grid-template-columns: repeat(3, 1fr);
            /* Cria 3 colunas com larguras iguais. */
            grid-template-rows: repeat(3, 1fr);
            /* Cria 3 linhas com alturas iguais. */
            gap: 5px;
            border-radius: 5px;

            /* Espaçamento de 5px entre as células da grade. */
            padding: 10px;
            /* Espaçamento interno dentro da área da grade. */
            box-sizing: border-box;
            /* Garante que o padding seja incluído na largura/altura total dos elementos. */
            position: relative;
            /* Permite que esta div seja um contexto de empilhamento para seu z-index. */
            z-index: 1;
            /* Coloca esta área (corpo da camisa) acima das mangas. */
        }

        /* Estilos para cada célula individual dentro das áreas da frente e da costa (os "quadrados" da grade). */
        .frente-area,
        .costa-area {
            border: 1px dashed #ccc;
            background-color: #ffffffff;
            /* Cor de fundo cinza clara para as áreas, indicando que são espaços preenchíveis. */
            display: flex;
            /* Usa Flexbox para centralizar o conteúdo dentro de cada área. */
            flex-direction: column;
            /* Empilha o texto e a imagem verticalmente, se ambos estiverem presentes. */
            justify-content: center;
            /* Centraliza o conteúdo horizontalmente. */
            align-items: center;
            /* Centraliza o conteúdo verticalmente. */
            font-size: 1.2em;
            /* Tamanho de fonte padrão para o conteúdo de fallback. */
            font-weight: bold;
            /* Texto em negrito. */
            border-radius: 4px;
            /* Cantos levemente arredondados para as áreas. */
            min-height: 50px;
            /* Garante uma altura mínima para cada área, mesmo que vazia. */
            overflow: hidden;
            /* Esconde qualquer conteúdo que transborde das bordas da área. */
            padding: 5px;
            /* Adiciona um pequeno espaçamento interno para o conteúdo. */
            text-align: center;
            /* Centraliza o texto dentro da área. */
        }


        /* Estilos para a área específica que queremos ocultar (frente_pos2). */
        .frente-pos2-hidden {
            visibility: hidden;
            /* Torna o elemento invisível, mas ele ainda ocupa seu espaço na grade. */
            background-color: transparent;
            /* Remove a cor de fundo. */
            border: none;
            /* Remove a borda tracejada. */
            padding: 0;
            /* Remove o preenchimento. */
            min-height: 0;
            /* Garante que não tenha altura mínima forçada. */
        }




        /* Estilos para as imagens de customização que são inseridas nas áreas. */
        .custom-image {
            max-width: 100%;
            /* Garante que a imagem não seja maior que a largura da sua área pai. */
            max-height: 100%;
            /* Garante que a imagem não seja maior que a altura da sua área pai. */
            object-fit: contain;
            /* Reduz (ou amplia) a imagem para caber dentro da área, mantendo a proporção. */
        }

        /* Estilos para a div que simula o Pescoço/Gola da camisa. */
        .div-pescoco {
            position: absolute;
            /* Posiciona a gola de forma absoluta em relação ao 'camisa-container'. */
            top: -20px;
            /* Move a gola para cima, para fora do contorno principal da camisa. */
            left: 50%;
            /* Começa a gola no centro horizontal. */
            transform: translateX(-50%);
            /* Ajusta para centralizar a gola perfeitamente. */
            width: 100px;
            /* Largura da gola. */
            height: 40px;
            /* Altura da gola. */
            background-color: #fff;
            /* Fundo branco para a gola. */
            border: 2px solid #333;
            /* Borda escura para a gola. */
            border-top-left-radius: 50%;
            /* Arredonda o canto superior esquerdo para formar a curva da gola. */
            border-top-right-radius: 50%;
            /* Arredonda o canto superior direito para formar a curva da gola. */
            border-bottom: none;
            /* Remove a borda inferior para que ela se conecte visualmente ao corpo. */
            z-index: 1;
            /* Garante que a gola fique acima do corpo da camisa, mas abaixo das mangas. */
        }
    </style>
@endsection
