@extends('layouts.layout')

@section('title', 'Inicio')

@section('content')
    <div class="flex">
        <div class="map-container w-2/3 z-0">
            <div id="map" class="map-container"></div>
        </div>
        <div class="solicitudes-container w-1/3 p-4">
            @livewire('solicitudes-viaje')
        </div>
    </div>
    <!-- Bottom controls -->
    <div class="bottom-bar flex-col justify-between items-center">
        <div>
            @livewire('cambiar-estado')
            @livewire('pasajero-live')
            <div class="input-group mt-3"> 
                @livewire('finalizar-viaje')
                <button class="btn btn-danger">Reportar incidente</button>
                @livewire('cancelar-viaje')
            </div>
        </div>        
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    var map = L.map('map').setView([-17.7833, -63.1821], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    Livewire.on('solicitudSeleccionada', (solicitud) => {
        console.log('Solicitud recibida:', solicitud);

        const origen = solicitud.solicitud.origen;
        const destino = solicitud.solicitud.destino;

        if (!origen || !destino) {
            console.error('La solicitud no tiene origen o destino definido.');
            return;
        }

        clearMap();

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
                        alert('No se pudo encontrar el destino. Verifica la dirección e intenta nuevamente.');
                    }
                });
            } else {
                console.error('No se pudo geocodificar el origen:', origen);
                alert('No se pudo encontrar el origen. Verifica la dirección e intenta nuevamente.');
            }
        });
    });

    Livewire.on('viajeCancelado', () => {
        clearMap();
    });
    Livewire.on('viajeFinalizado', () => {
        console.log('hola pe')
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
        console.log('Geocodificando dirección:', address);
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la solicitud: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                console.log('Respuesta de geocodificación:', data);
                if (data.length > 0) {
                    callback({ lat: parseFloat(data[0].lat), lng: parseFloat(data[0].lon) });
                } else {
                    console.error('No se encontraron resultados para:', address);
                    callback(null);
                }
            })
            .catch(error => {
                console.error('Error en la geocodificación:', error);
                callback(null);
            });
    }

    function drawRoute(origin, destination) {
        fetch(`https://router.project-osrm.org/route/v1/driving/${origin.lng},${origin.lat};${destination.lng},${destination.lat}?overview=full&geometries=geojson`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la solicitud de ruta: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.routes && data.routes.length > 0) {
                    var routeCoordinates = data.routes[0].geometry.coordinates;
                    var latLngs = routeCoordinates.map(coord => [coord[1], coord[0]]);
                    L.polyline(latLngs, { color: 'blue' }).addTo(map);
                    map.fitBounds(L.polyline(latLngs).getBounds());
                } else {
                    console.error('No se pudo calcular la ruta.');
                    alert('No se pudo calcular la ruta. Verifica las direcciones e intenta nuevamente.');
                }
            })
            .catch(error => {
                console.error('Error al dibujar la ruta:', error);
                alert('Hubo un error al calcular la ruta. Intenta nuevamente.');
            });
    }
});
</script>
@endsection