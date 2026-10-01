@extends('layouts.app')

@section('title', 'Contas Bancárias')

@php
use App\Helpers\CryptHelper;
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 font-poppins">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <x-page-header title="Contas Bancárias">
            <x-header-action href="{{ route('conta_bancaria.create') }}">
                Nova Conta
            </x-header-action>
        </x-page-header>

        <div class="px-6 sm:px-8 pt-6">
            <x-alert-flash />
        </div>

        <div class="px-6 sm:px-8 pt-4 pb-6">
            <form method="GET" action="{{ route('conta_bancaria.index') }}">
                <x-filter-card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                        <div>
                            <label for="banco" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Banco</label>
                            <input type="text" id="banco" name="banco" value="{{ request('banco') }}" placeholder="Nome do banco..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="codigo" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Código</label>
                            <input type="text" id="codigo" name="codigo" value="{{ request('codigo') }}" placeholder="Ex.: 001"
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="agencia" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Agência</label>
                            <input type="text" id="agencia" name="agencia" value="{{ request('agencia') }}" placeholder="Agência..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div>
                            <label for="conta" class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-2">Conta</label>
                            <input type="text" id="conta" name="conta" value="{{ request('conta') }}" placeholder="Número da conta..."
                                class="w-full h-11 px-3 text-sm text-gray-700 bg-white border border-gray-300 rounded-lg outline-none transition focus:border-orange-500 focus:ring-2 focus:ring-orange-100">
                        </div>

                        <div class="flex items-end">
                            <x-primary-button class="w-full h-11">
                                <x-icons.search />
                                Buscar
                            </x-primary-button>
                        </div>
                    </div>

                    <div class="flex justify-end mt-4 pt-4 border-t border-gray-200">
                        <a href="{{ route('conta_bancaria.index') }}"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 hover:text-gray-800 transition">
                            <x-icons.reset />
                            Limpar filtros
                        </a>
                    </div>
                </x-filter-card>
            </form>
        </div>

        <div class="px-6 sm:px-8 pb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h2 class="text-lg font-bold text-gray-800">Lista de contas bancárias</h2>
                <div class="inline-flex items-center gap-2 self-start px-3 py-2 rounded-lg bg-orange-50 border border-orange-100">
                    <span class="w-2 h-2 rounded-full" style="background-color:#EA792D;"></span>
                    <span class="text-xs font-semibold text-orange-700">{{ $contas->total() }} conta(s)</span>
                </div>
            </div>

            @if ($contas->isEmpty())
            @if (request('banco') || request('codigo') || request('agencia') || request('conta'))
            <x-empty-state
                title="Nenhuma conta encontrada"
                message="Não existem contas bancárias correspondentes aos filtros informados."
                route="conta_bancaria.index"
                button-text="Limpar filtros" />
            @else
            <x-empty-state
                title="Nenhuma conta cadastrada"
                message="Ainda não existem contas bancárias cadastradas."
                route="conta_bancaria.create"
                button-text="Cadastrar conta" />
            @endif
            @else
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead style="background-color:#343A40;">
                            <tr>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Banco</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Código</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Agência</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Conta</th>
                                <th class="px-5 py-4 text-left text-[11px] font-bold text-white uppercase tracking-wider">Dígito</th>
                                <th class="px-5 py-4 text-right text-[11px] font-bold text-white uppercase tracking-wider">Saldo</th>
                                <th class="px-2 py-4 text-center text-[11px] font-bold text-white uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @foreach ($contas as $conta)
                            <tr class="group hover:bg-orange-50/40 transition">
                                <td class="px-5 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center w-9 h-9 rounded-lg bg-gray-100 text-gray-600 group-hover:bg-orange-100 group-hover:text-orange-600 transition">
                                            <x-icons.document />
                                        </div>
                                        <span class="text-sm font-bold text-gray-800">
                                            {{ $conta->conta_nome_banco }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $conta->conta_cod_banco ?: 'N/D' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $conta->conta_agencia ?: 'N/D' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $conta->numero_conta_corrente ?: 'N/D' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 whitespace-nowrap">
                                    <span class="text-sm text-gray-700">
                                        {{ $conta->numero_digito_corrente ?: 'N/D' }}
                                    </span>
                                </td>

                                <td class="px-5 py-5 text-right whitespace-nowrap">
                                    <span class="text-sm font-bold text-green-600">
                                        R$ {{ number_format($conta->saldoConta->saldo_conta_valor ?? 0, 2, ',', '.') }}
                                    </span>
                                </td>

                                <td class="px-2 py-5 text-center whitespace-nowrap">
                                    <x-table-actions
                                        :show-route="route('conta_bancaria.show', CryptHelper::encrypt($conta->id_conta))"
                                        :edit-route="route('conta_bancaria.edit', CryptHelper::encrypt($conta->id_conta))"
                                        :saldo-route="url('/conta_bancaria/'.CryptHelper::encrypt($conta->id_conta).'/saldo')"
                                        :saldo-id="CryptHelper::encrypt($conta->id_conta)"
                                        :saldo-conta="$conta->conta_nome_banco"
                                        :delete-action="(!$conta->formasPagamento->count() && !$conta->fluxos->count() && !$conta->saldoConta) ? route('conta_bancaria.destroy', CryptHelper::encrypt($conta->id_conta)) : null"
                                        delete-id="formExcluirConta{{ $conta->id_conta }}"
                                        delete-modal="modalExcluirConta" />
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-5">
                <x-pagination-compact :paginator="$contas" />
            </div>
            @endif
        </div>
    </div>
</div>

<div id="modalSaldo" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 mx-4">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Adicionar Saldo</h2>
        <form id="formSaldo" method="POST">
            @csrf
            <div class="mb-5">
                <label for="valorSaldo" class="block text-sm font-semibold text-gray-700 mb-2">Valor</label>
                <input type="text" id="valorSaldo" name="valor" required placeholder="0,00" autocomplete="off"
                    class="w-full h-11 px-4 text-sm text-gray-700 border border-gray-300 rounded-lg outline-none focus:border-green-500 focus:ring-2 focus:ring-green-100">
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="btnCancelarModal"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                    Cancelar
                </button>
                <button type="submit"
                    class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<x-modal-confirmacao
    id="modalExcluirConta"
    titulo="Excluir conta bancária"
    mensagem="Deseja realmente apagar esta conta bancária?"
    textoConfirmar="Excluir" />

<div id="modalConfirmarSaldo" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6 mx-4">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Confirmar adição de saldo</h2>
        <p class="text-gray-700 mb-6">
            Deseja realmente adicionar
            <span id="valorConfirmacaoSaldo" class="font-bold text-green-600"></span>
            na conta:
            <span id="nomeConfirmacaoConta" class="font-bold text-gray-900"></span>?
        </p>
        <div class="flex justify-end gap-3">
            <button type="button" id="btnCancelarConfirmacaoSaldo"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition">
                Cancelar
            </button>
            <button type="button" id="btnConfirmarAdicionarSaldo"
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition">
                Confirmar
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalSaldo');
        const form = document.getElementById('formSaldo');
        const valorInput = document.getElementById('valorSaldo');
        const btnCancelarModal = document.getElementById('btnCancelarModal');
        const modalConfirmarSaldo = document.getElementById('modalConfirmarSaldo');
        const valorConfirmacaoSaldo = document.getElementById('valorConfirmacaoSaldo');
        const nomeConfirmacaoConta = document.getElementById('nomeConfirmacaoConta');
        const btnCancelarConfirmacaoSaldo = document.getElementById('btnCancelarConfirmacaoSaldo');
        const btnConfirmarAdicionarSaldo = document.getElementById('btnConfirmarAdicionarSaldo');

        document.querySelectorAll('.btnAbrirSaldo').forEach(button => {
            button.addEventListener('click', function() {
                form.action = this.dataset.saldoRoute;
                nomeConfirmacaoConta.textContent = this.dataset.conta;
                valorInput.value = '';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                valorInput.focus();
            });
        });

        function fecharModalSaldo() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        function fecharConfirmacaoSaldo() {
            modalConfirmarSaldo.classList.add('hidden');
            modalConfirmarSaldo.classList.remove('flex');
            btnConfirmarAdicionarSaldo.disabled = false;
            btnConfirmarAdicionarSaldo.textContent = 'Confirmar';
        }

        btnCancelarModal.addEventListener('click', fecharModalSaldo);

        modal.addEventListener('click', function(e) {
            if (e.target === modal) fecharModalSaldo();
        });

        valorInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value === '') {
                e.target.value = '';
                return;
            }
            value = (parseInt(value) / 100).toFixed(2);
            value = value.replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            e.target.value = value;
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const valor = valorInput.value;
            if (!valor || valor === '0,00') {
                alert('Informe um valor válido.');
                return;
            }
            valorConfirmacaoSaldo.textContent = 'R$ ' + valor;
            modalConfirmarSaldo.classList.remove('hidden');
            modalConfirmarSaldo.classList.add('flex');
        });

        btnCancelarConfirmacaoSaldo.addEventListener('click', fecharConfirmacaoSaldo);

        btnConfirmarAdicionarSaldo.addEventListener('click', function() {
            if (this.disabled) return;
            this.disabled = true;
            this.textContent = 'Salvando...';
            valorInput.value = valorInput.value.replace(/\./g, '').replace(',', '.');
            form.submit();
        });

        modalConfirmarSaldo.addEventListener('click', function(e) {
            if (e.target === modalConfirmarSaldo) fecharConfirmacaoSaldo();
        });
    });
</script>
@endpush
@endsection