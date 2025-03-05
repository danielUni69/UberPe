<div wire:poll.5s="actualizarSolicitudes">
    <h3>Solicitudes de Viaje</h3>
    <ul>
        @foreach($solicitudes as $solicitud)
            <li wire:click="seleccionarSolicitud({{ $solicitud->id_viaje }})" class="solicitud-item">
                <p><strong>Pasajero:</strong> {{ $solicitud->pasajero->nombres }} {{ $solicitud->pasajero->apellidos }}</p>
                <p><strong>Origen:</strong> {{ $solicitud->origen }}</p>
                <p><strong>Destino:</strong> {{ $solicitud->destino }}</p>
                <p><strong>Tarifa:</strong> {{ $solicitud->tarifa }} Bs</p>
                <button wire:click="aceptarViaje({{ $solicitud->id_viaje }})" class="btn btn-success">Aceptar Viaje</button>
            </li>
        @endforeach
    </ul>
</div>