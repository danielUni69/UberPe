<div wire:poll.5s="actualizarSolicitudes">
    <h3 class="text-lg font-semibold mb-4">Solicitudes de Viaje</h3>
    <ul class="space-y-4">
        @foreach($solicitudes as $solicitud)
            <li wire:click="seleccionarSolicitud({{ $solicitud->id_viaje }})" class="solicitud-item p-4 border rounded-lg shadow-sm hover:bg-gray-50 cursor-pointer">
                <!-- Texto cortado con puntos suspensivos -->
                <p class="truncate"><strong>Pasajero:</strong> {{ $solicitud->pasajero->nombres }} {{ $solicitud->pasajero->apellidos }}</p>
                <p class="truncate"><strong>Origen:</strong> {{ $solicitud->origen }}</p>
                <p class="truncate"><strong>Destino:</strong> {{ $solicitud->destino }}</p>
                <p class="truncate"><strong>Tarifa:</strong> {{ $solicitud->tarifa }} Bs</p>
                <p class="truncate"><strong>Metodo:</strong> {{ $solicitud->metodo }}</p>
                <p class="truncate"><strong>Metodo:</strong> {{ $solicitud->descripcion }}</p>
                <!-- Botón para aceptar el viaje -->
                <button wire:click="aceptarViaje({{ $solicitud->id_viaje }})" class="mt-2 w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600 transition-colors">
                    Aceptar Viaje
                </button>
            </li>
        @endforeach
    </ul>
</div>