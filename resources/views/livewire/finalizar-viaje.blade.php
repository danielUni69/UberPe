<div>
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
                <h2 class="text-lg font-semibold mb-4">Confirmar Pago</h2>
                <p class="mb-4">¿Deseas confirmar el pago o reportar que no se pagó?</p>
                <div class="flex justify-end space-x-4">
                    <button wire:click="confirmarPago" class="bg-green-500 text-white px-4 py-2 rounded">
                        Confirmar
                    </button>
                    <button wire:click="reportarNopago" class="bg-red-500 text-white px-4 py-2 rounded">
                        Reportar que no se pagó
                    </button>
                </div>
            </div>
        </div>
    @endif

    <button class="btn btn-success" wire:click="finalizarViaje">Finalizar viaje</button>
</div>