@extends('layouts.layout')

@section('title', 'Inicio')

@section('content')
    <div class="flex">
        <div class="map-container w-2/3">
            <div id="map" class="map-container"></div>
        </div>
        <div class="solicitudes-container w-1/3 p-4">
            @livewire('solicitudes-viaje')
        </div>
    </div>
    <!-- Bottom controls -->
    <div class="bottom-bar flex justify-between items-center">
        <div>
            @livewire('cambiar-estado')
            @livewire('pasajero-live')
            <div class="input-group mt-3"> 
                <button class="btn btn-success">Finalizar viaje</button>
                <button class="btn btn-danger">Reportar incidente</button>
                @livewire('cancelar-viaje')
            </div>
        </div>
        <div class="flex text-xl">
            <p class="font-bold text-green-700 pr-3">TARIFA: </p>
            <p class="font-bold"> 90bs</p>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView([-17.7833, -63.1821], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Escuchar el evento de solicitud seleccionada
    Livewire.on('solicitudSeleccionada', (solicitud) => {
        console.log('Solicitud recibida:', solicitud);

        const origen = solicitud.solicitud.origen;
        const destino = solicitud.solicitud.destino;

        if (!origen || !destino) {
            console.error('La solicitud no tiene origen o destino definido.');
            return;
        }

        // Limpiar el mapa
        clearMap();

        // Geocodificar origen y destino
        geocode(origen, function (coordsOrigen) {
            if (coordsOrigen) {
                L.marker(coordsOrigen).addTo(map).bindPopup('Origen: ' + origen).openPopup();
                map.setView(coordsOrigen, 13);

                geocode(destino, function (coordsDestino) {
                    if (coordsDestino) {
                        L.marker(coordsDestino).addTo(map).bindPopup('Destino: ' + destino);
                        drawRoute(coordsOrigen, coordsDestino);
                    } else {
                        console.error('No se pudo geocodificar el destino:', destino);
                    }
                });
            } else {
                console.error('No se pudo geocodificar el origen:', origen);
            }
        });
    });

    // Escuchar el evento de cancelación de viaje
    Livewire.on('viajeCancelado', () => {
        clearMap();
    });

    function clearMap() {
        map.eachLayer(function (layer) {
            if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                map.removeLayer(layer);
            }
        });
    }

    function geocode(address, callback) {
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    callback({ lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) });
                } else {
                    callback(null);
                }
            });
    }

    function drawRoute(origin, destination) {
        fetch(`https://router.project-osrm.org/route/v1/driving/${origin.lng},${origin.lat};${destination.lng},${destination.lat}?overview=full&geometries=geojson`)
            .then(response => response.json())
            .then(data => {
                var routeCoordinates = data.routes[0].geometry.coordinates;
                var latLngs = routeCoordinates.map(coord => [coord[1], coord[0]]);
                L.polyline(latLngs, { color: 'blue' }).addTo(map);
                map.fitBounds(L.polyline(latLngs).getBounds());
            });
    }
});
</script>
@endsection