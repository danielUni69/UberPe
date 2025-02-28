
@extends('layouts.layout')

@section('title', 'Inicio')

@section('content')
<h1>Solicitar Viaje</h1>

<div id="map"></div>
<!-- Bottom controls -->
<div class="bottom-bar">
    <img src="{{ asset('images/car.png') }}" alt="Auto" class="car-image">
    <div class="payment-options text-center">
        <p class="mb-1">Tipo de pago</p>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="payment" id="qr" value="qr">
            <label class="form-check-label" for="qr">QR</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="payment" id="efectivo" value="efectivo">
            <label class="form-check-label" for="efectivo">Efectivo</label>
        </div>
    </div>
    <div class="input-group mt-3">
        <input type="text" class="form-control" placeholder="Ofrezca su tarifa">
        <span class="input-group-text">Bs</span>
    </div>
    <div class="input-group mt-3">
        <input type="text" class="form-control" placeholder="Ir a">
        <button class="btn btn-success">Buscar Conductor</button>
        <button class="btn btn-danger">Cancelar Viaje</button>
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    // Inicializar el mapa
    const map = L.map('map').setView([40.7128, -74.0060], 13); // Nueva York como centro inicial

    // Usar tiles de OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let origenCoords = null;
    let destinoCoords = null;

    // Obtener la ubicación actual del pasajero
    navigator.geolocation.getCurrentPosition((position) => {
        origenCoords = [position.coords.latitude, position.coords.longitude];

        // Mover el mapa a la ubicación del pasajero
        map.setView(origenCoords, 13);

        // Agregar un marcador en la ubicación del pasajero
        L.marker(origenCoords).addTo(map)
            .bindPopup('Tu ubicación actual')
            .openPopup();
    }, (error) => {
        console.error('Error al obtener la ubicación:', error);
        alert('No se pudo obtener tu ubicación. Por favor, habilita la geolocalización.');
    });

    // Permitir al pasajero seleccionar el destino haciendo clic en el mapa
    map.on('click', (e) => {
        if (destinoCoords) {
            map.removeLayer(destinoMarker); // Eliminar el marcador anterior
        }

        destinoCoords = [e.latlng.lat, e.latlng.lng];

        // Agregar un marcador en el destino
        destinoMarker = L.marker(destinoCoords).addTo(map)
            .bindPopup('Destino seleccionado')
            .openPopup();
    });

    // Solicitar el viaje
    document.getElementById('solicitarViaje').addEventListener('click', async () => {
        if (!origenCoords || !destinoCoords) {
            alert('Por favor, selecciona un destino en el mapa.');
            return;
        }

        // Enviar la solicitud de viaje al servidor
        const response = await fetch('/solicitar-viaje', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                origen: origenCoords,
                destino: destinoCoords
            })
        });

        if (response.ok) {
            alert('Viaje solicitado correctamente.');
        } else {
            alert('Error al solicitar el viaje.');
        }
    });
    
</script>
@endsection
