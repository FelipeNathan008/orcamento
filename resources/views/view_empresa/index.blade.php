@extends('layouts.app')

@section('title', 'Lista de Empresas')

@section('content')
@php
use App\Helpers\CryptHelper;
@endphp
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Empresas Cadastradas">
            <x-header-action href="{{ route('empresa.create') }}">
                Nova Empresa
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('empresa.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div>
                            <label for="nome" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Nome</label>
                            <input type="text" id="nome" name="nome" value="{{ request('nome') }}" placeholder="Nome da empresa..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="cnpj" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">CNPJ</label>
                            <input type="text" id="cnpj" name="cnpj" value="{{ request('cnpj') }}" placeholder="00.000.000/0000-00"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="cidade" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Cidade</label>
                            <input type="text" id="cidade" name="cidade" value="{{ request('cidade') }}" placeholder="Cidade..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="uf" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">UF</label>
                            <select id="uf" name="uf" class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                                <option value="">Todos os Estados</option>
                                @php
                                $estados = [
                                'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
                                'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
                                'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
                                'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
                                'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
                                'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
                                'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
                                ];
                                @endphp

                                @foreach ($estados as $sigla => $nome)
                                <option value="{{ $sigla }}" {{ request('uf') == $sigla ? 'selected' : '' }}>{{ $nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-end">
                            <x-primary-button class="w-full h-11">
                                <x-icons.search />
                                Buscar
                            </x-primary-button>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('empresa.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de empresas</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $empresas->total() }} empresa(s)</span>
                </div>
            </div>

            @if ($empresas->isEmpty())
            @if (request('nome') || request('cnpj') || request('cidade') || request('uf'))
            <x-empty-state
                title="Nenhuma empresa encontrada"
                message="Não existem empresas correspondentes aos filtros informados."
                route="empresa.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhuma empresa cadastrada"
                message="Ainda não existem empresas cadastradas no sistema."
                route="empresa.create"
                button-text="Cadastrar empresa" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Nome</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">CNPJ</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Cidade/UF</th>
                                <th class="px-5 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-100" id="companyTableBody">
                            @foreach ($empresas as $empresa)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-bold text-gray-800">{{ $empresa->emp_nome }}</span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-700">{{ $empresa->emp_cnpj }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">{{ $empresa->emp_cidade }} - {{ $empresa->emp_uf }}</span>
                                </td>

                                <td class="px-5 py-5 text-center whitespace-nowrap">

                                    <x-table-actions
                                        :show-route="route('empresa.show', CryptHelper::encrypt($empresa->id_emp))"
                                        :edit-route="route('empresa.edit', CryptHelper::encrypt($empresa->id_emp))"
                                        :delete-action="route('empresa.destroy', CryptHelper::encrypt($empresa->id_emp))"
                                        delete-id="formExcluirEmpresa{{ $empresa->id_emp }}"
                                        delete-modal="modalExcluirEmpresa" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$empresas" />
            </div>
            @endif
        </div>
    </div>

</div>

<x-modal-confirmacao
    id="modalExcluirEmpresa"
    titulo="Excluir empresa"
    mensagem="Deseja realmente apagar esta empresa?"
    textoConfirmar="Excluir" />

@push('scripts')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    (function() {
        const key = 'scroll:' + location.pathname + location.search;
        const saved = sessionStorage.getItem(key);

        if (saved !== null) {
            window.scrollTo(0, parseInt(saved, 10));
            sessionStorage.removeItem(key);
        }

        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');

            if (!link) {
                return;
            }

            if (link.closest('tbody') || link.href.includes('/create')) {
                sessionStorage.setItem(key, window.scrollY);
            }
        });
    })();

    $(document).ready(function() {
        $('#cnpj').mask('AA.AAA.AAA/AAAA-AA', {
            translation: {
                'A': {
                    pattern: /[A-Za-z0-9]/
                }
            }
        });
    });
</script>
@endpush

@endsection