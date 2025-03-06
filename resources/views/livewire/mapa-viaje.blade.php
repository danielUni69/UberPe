
<div wire:init="initMap">
    <!-- Mapa -->
    <div id="map"></div>

    <!-- Controles -->
    <div class="bottom-bar">
        <div class="payment-options text-center">
            <p class="mb-1">Tipo de pago</p>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" wire:model="metodo_pago" name="metodo_pago" id="billetera" value="Billetera">
                <label class="form-check-label" for="billetera">Billetera</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" wire:model="metodo_pago" name="metodo_pago" id="efectivo" value="Efectivo">
                <label class="form-check-label" for="efectivo">Efectivo</label>
            </div>
        </div>
        <div class="input-group mt-3">
            <input type="text" wire:model="tarifa" class="form-control" placeholder="Ofrezca su tarifa">
            <span class="input-group-text">Bs</span>
        </div>
        <div class="input-group mt-3">
            <input type="text" wire:model="origen" id="origen" class="form-control" placeholder="Origen">
            <button class="btn btn-warning" id="clearOrigen">Eliminar Origen</button>
            <input type="text" wire:model="destino" id="destino" class="form-control" placeholder="Ir a">
            <button class="btn btn-warning" id="clearDestino">Eliminar Destino</button>
            <button class="btn btn-success" wire:click="solicitarServicio">Buscar Conductor</button>
            <button class="btn btn-danger" wire:click="cancelarViaje">Cancelar Viaje</button>
            <button class="btn btn-primary" id="locateMe">Localizarme</button>
        </div>

        <!-- Mostrar errores -->
        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    
</div>

@script
<script>
     
    console.log('script cargado');

    // Escuchar el evento 'init-map'
    Livewire.on('init-map', function () {
        console.log('Mapa inicializado');

    
        // Inicializar el mapa
        var map = L.map('map').setView([-17.7833, -63.1821], 13); // Coordenadas iniciales (Santa Cruz, Bolivia)
        console.log('Mapa configurado');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);
        // Variables para los marcadores de origen y destino
        let origenMarker, destinoMarker;
        let routeLine; // Línea para mostrar la ruta

        // Función para dibujar la ruta entre origen y destino
        function drawRoute(origin, destination) {
            if (routeLine) {
                map.removeLayer(routeLine); // Eliminar la ruta anterior si existe
            }

            // Usar la API de OSRM para obtener la ruta
            fetch(`https://router.project-osrm.org/route/v1/driving/${origin.lng},${origin.lat};${destination.lng},${destination.lat}?overview=full&geometries=geojson`)
                .then(response => response.json())
                .then(data => {
                    const routeCoordinates = data.routes[0].geometry.coordinates;
                    const latLngs = routeCoordinates.map(coord => [coord[1], coord[0]]);

                    // Dibujar la línea en el mapa
                    routeLine = L.polyline(latLngs, { color: 'blue' }).addTo(map);

                    // Ajustar el mapa para que se vea toda la ruta
                    map.fitBounds(routeLine.getBounds());
                })
                .catch(error => {
                    console.error('Error al dibujar la ruta:', error);
                });
        }

        // Evento para seleccionar origen y destino en el mapa
        map.on('click', function (e) {
            if (!origenMarker) {
                origenMarker = L.marker(e.latlng).addTo(map);
                $wire.set('origen', e.latlng.lat + ', ' + e.latlng.lng); // Actualizar propiedad en Livewire
            } else if (!destinoMarker) {
                destinoMarker = L.marker(e.latlng).addTo(map);
                $wire.set('destino', e.latlng.lat + ', ' + e.latlng.lng); // Actualizar propiedad en Livewire

                // Dibujar la ruta entre origen y destino
                drawRoute(origenMarker.getLatLng(), destinoMarker.getLatLng());
            }
        });

        // Evento para eliminar el marcador de origen
        document.getElementById('clearOrigen').addEventListener('click', function () {
            if (origenMarker) {
                map.removeLayer(origenMarker);
                origenMarker = null;
                $wire.set('origen', ''); // Limpiar propiedad en Livewire
            }
            if (routeLine) {
                map.removeLayer(routeLine);
                routeLine = null;
            }
        });

        // Evento para eliminar el marcador de destino
        document.getElementById('clearDestino').addEventListener('click', function () {
            if (destinoMarker) {
                map.removeLayer(destinoMarker);
                destinoMarker = null;
                $wire.set('destino', ''); // Limpiar propiedad en Livewire
            }
            if (routeLine) {
                map.removeLayer(routeLine);
                routeLine = null;
            }
        });

        // Evento para localizar al usuario
        document.getElementById('locateMe').addEventListener('click', function () {
            if (!navigator.geolocation) {
                alert('Tu navegador no soporta geolocalización.');
                return;
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const userLatLng = [position.coords.latitude, position.coords.longitude];
                    map.setView(userLatLng, 15);

                    if (origenMarker) {
                        origenMarker.setLatLng(userLatLng);
                    } else {
                        origenMarker = L.marker(userLatLng).addTo(map);
                    }
                    $wire.set('origen', userLatLng[0] + ', ' + userLatLng[1]); // Actualizar propiedad en Livewire
                },
                function (error) {
                    alert('No se pudo obtener tu ubicación: ' + error.message);
                }
            );
        });
    });
</script>
@endscript

