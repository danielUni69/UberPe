<div wire:poll.5s="estado_viaje">
    <h3>Estado del Viaje: {{ $estado }}</h3>
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