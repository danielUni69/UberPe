<div wire:poll.5s="estado_viaje">
    <div class="flex justify-between items-center">
        <h3>Estado del Viaje: {{ $estado }}</h3>
        @if(Auth::user()->rol != "Pasajero")
            <div class="flex text-xl">
                <p class="font-bold text-green-700 pr-3">TARIFA: </p>
                <p class="font-bold"> {{ $tarifa }} bs</p>
            </div>
            <div class="flex text-xl">
                <p class="font-bold text-green-700 pr-3">Metodo de pago: </p>
                <p class="font-bold"> {{ $metodo }} </p>
            </div>
        @endif
    </div>
</div>
    
    @push('scripts')
    <script>
        // Remove the previous event listener approach
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('viajeAceptado', (event) => {
                Swal.fire({
                    title: 'Notificación',
                    text: 'Se acepto el viaje correctamente',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        });
    </script>
    @endpush
</div>