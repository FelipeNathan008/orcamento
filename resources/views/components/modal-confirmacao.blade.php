@props([
    'id' => 'modalConfirmacao',
    'titulo' => 'Confirmação',
    'mensagem' => 'Deseja realmente continuar?',
    'textoConfirmar' => 'Confirmar',
    'textoCancelar' => 'Cancelar'
])

<div id="{{ $id }}"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 px-4">

    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">

        <div class="p-6 sm:p-7">

            <div class="flex items-start gap-4">

                <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-orange-100 text-orange-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="1.8">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 9v4m0 4h.01M10.3 3.7l-8 14A2 2 0 004 21h16a2 2 0 001.7-3.3l-8-14a2 2 0 00-3.4 0z" />
                    </svg>
                </div>

                <div class="min-w-0">
                    <h2 class="text-lg sm:text-xl font-bold text-gray-800">
                        {{ $titulo }}
                    </h2>

                    <p class="text-sm text-gray-500 mt-1.5 leading-relaxed">
                        {{ $mensagem }}
                    </p>
                </div>

            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-7 pt-5 border-t border-gray-100">

                <button type="button"
                    onclick="fecharModal('{{ $id }}')"
                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-semibold text-gray-700 bg-gray-100 border border-gray-200 hover:bg-gray-200 transition">
                    {{ $textoCancelar }}
                </button>

                <button type="button"
                    id="{{ $id }}Confirmar"
                    class="inline-flex items-center justify-center px-5 py-2.5 rounded-lg text-sm font-semibold text-white shadow-sm hover:shadow-md hover:brightness-95 transition"
                    style="background-color:#EA792D;">
                    {{ $textoConfirmar }}
                </button>

            </div>

        </div>

    </div>

</div>

@once
@push('scripts')
<script>
    window.abrirModal = function(id, callback) {

        const modal = document.getElementById(id);
        const confirmar = document.getElementById(id + 'Confirmar');

        const textoOriginal = confirmar.innerHTML;

        confirmar.onclick = function() {

            confirmar.disabled = true;
            confirmar.innerHTML = 'Confirmando...';

            callback();

            setTimeout(() => {
                confirmar.disabled = false;
                confirmar.innerHTML = textoOriginal;
            }, 1500);

        };

        modal.classList.remove('hidden');
        modal.classList.add('flex');

    }

    window.fecharModal = function(id) {

        const modal = document.getElementById(id);

        modal.classList.add('hidden');
        modal.classList.remove('flex');

    }
</script>
@endpush
@endonce
