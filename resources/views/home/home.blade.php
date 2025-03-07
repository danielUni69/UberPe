@extends('layouts.layout')

@section('title', 'Inicio')

@section('content')
<div class="map-container h-56 rounded-lg overflow-hidden shadow-md" id="map"></div>

<div class="bottom-bar compact p-3 bg-white shadow-xl rounded-2xl mt-2">
    @livewire('pasajero-live')

    <!-- Ubicación -->
    <div class="location-inputs space-y-2">
        <div class="relative flex items-center">
            <span class="absolute left-3 text-gray-500">📍</span>
            <input type="text" id="origen" name="origen" class="form-control text-sm flex-1 rounded-full pl-10 pr-14 py-2 shadow-sm focus:ring focus:ring-blue-200" placeholder="¿Dónde estás?">
            <div class="absolute right-2 flex gap-1">
                <button class="btn btn-outline-secondary btn-xs px-2" id="clearOrigen">✕</button>
                <button class="btn btn-outline-primary btn-xs px-2" id="locateMe">📍</button>
            </div>
        </div>
        <div class="relative flex items-center">
            <span class="absolute left-3 text-gray-500">📌</span>
            <input type="text" id="destino" name="destino" class="form-control text-sm flex-1 rounded-full pl-10 pr-10 py-2 shadow-sm focus:ring focus:ring-blue-200" placeholder="¿A dónde vas?">
            <button class="absolute right-2 btn btn-outline-secondary btn-xs  px-2" id="clearDestino">✕</button>
        </div>
    </div>

    <!-- Métodos de pago y tarifa -->
    <div class="flex items-center justify-center my-3 text-sm">
        <div class="payment-options flex gap-2">
            <label class="flex items-center gap-1 cursor-pointer bg-gray-100 px-3 py-1 rounded-full shadow-sm hover:bg-gray-200 transition">
                <input type="radio" name="metodo_pago" id="billetera" value="Billetera" class="hidden">
                <span class="checkmark w-4 h-4 border border-gray-500 rounded-full flex items-center justify-center">
                    <span class="hidden bg-blue-500 w-2 h-2 rounded-full"></span>
                </span>
                💳 Billetera
            </label>
            <label class="flex items-center gap-1 cursor-pointer bg-gray-100 px-3 py-1 rounded-full shadow-sm hover:bg-gray-200 transition">
                <input type="radio" name="metodo_pago" id="efectivo" value="Efectivo" class="hidden">
                <span class="checkmark w-4 h-4 border border-gray-500 rounded-full flex items-center justify-center">
                    <span class="hidden bg-blue-500 w-2 h-2 rounded-full"></span>
                </span>
                💵 Efectivo
            </label>
        </div>
            <div class="flex items-center gap-2 bg-green-600 text-white px-3 mr-24 py-1 rounded-full shadow-md">
            <input type="number" class="text-sm w-16 bg-transparent text-center focus:outline-none placeholder-white" id="tarifa" name="tarifa" placeholder="Tarifa">
            <span class="text-white pl-40">Bs</span>
        </div>
    </div>

    <textarea rows="1" name="descripcion" id="descripcion" class="form-control text-xs w-full rounded-lg p-2 shadow-sm focus:ring focus:ring-blue-200" placeholder="Añade una descripción (opcional)"></textarea>

    <div class="flex gap-3 mt-3 items-center justify-center">
        <button class="btn bg-green-600 text-white w-1/3 text-sm p-2 rounded-full mt-2 mb-2 shadow-md hover:bg-blue-700 transition" id="buscarConductor">🚖 Buscar</button>
        <button class="btn bg-red-500 text-white w-1/3 text-sm p-2 rounded-full mt-2 mb-2 shadow-md hover:bg-red-600 transition" id="cancelarViaje">❌ Cancelar</button>
    </div>
</div>

<style>
    /* Checkmark personalizado */
    .payment-options input:checked + .checkmark span {
        display: block;
    }
</style>


<script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicialización del mapa
            var map = L.map('map').setView([-17.7833, -63.1821], 13); // Coordenadas iniciales (Santa Cruz, Bolivia)

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Definir los iconos personalizados
            var origenIcon = L.icon({
                iconUrl: "{{ asset('/iconos/marcador-de-posicion.png') }}", // Ruta a la imagen del icono de origen
                iconSize: [32, 32], 
                iconAnchor: [16, 32], 
                popupAnchor: [0, -32] 
            });

            var destinoIcon = L.icon({
                iconUrl: "{{ asset('iconos/marcador-de-posicion (1).png') }}",
                iconSize: [32, 32], 
                iconAnchor: [16, 32],
                popupAnchor: [0, -32] 
            });

            var userLocationIcon = L.icon({
                iconUrl: "{{ asset('iconos/marcador-de-posicion (1).png') }}", // Ruta a la imagen del icono de usuario
                iconSize: [32, 32],
                iconAnchor: [16, 32],
                popupAnchor: [0, -32]
            });

            // Variables para los marcadores de origen y destino
            var origenMarker, destinoMarker;
            var userLocationMarker; // Marcador para la ubicación del usuario
            var routeLine; // Línea para mostrar el recorrido

            // Función para geocodificar una dirección
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

            // Función para geocodificación inversa (convertir coordenadas a dirección)
            function reverseGeocode(lat, lng, callback) {
                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            callback(data.display_name); // Devuelve la dirección formateada
                        } else {
                            callback(null);
                        }
                    })
                    .catch(error => {
                        console.error('Error en la geocodificación inversa:', error);
                        callback(null);
                    });
            }

            // Función para centrar el mapa en la ubicación del usuario
            function locateUser() {
                if (!navigator.geolocation) {
                    alert('Tu navegador no soporta geolocalización.');
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        var userLatLng = [position.coords.latitude, position.coords.longitude];

                        // Centrar el mapa en la ubicación del usuario
                        map.setView(userLatLng, 15);

                        // Agregar o actualizar el marcador de la ubicación del usuario
                        if (userLocationMarker) {
                            userLocationMarker.setLatLng(userLatLng);
                        } else {
                            userLocationMarker = L.marker(userLatLng, {
                                icon: userLocationIcon
                            }).addTo(map);
                        }

                        // Obtener la dirección a partir de las coordenadas
                        reverseGeocode(userLatLng[0], userLatLng[1], function (address) {
                            if (address) {
                                document.getElementById('origen').value = address; // Mostrar la dirección en el input de origen
                            } else {
                                alert('No se pudo obtener la dirección para tu ubicación.');
                            }
                        });
                    },
                    function (error) {
                        alert('No se pudo obtener tu ubicación: ' + error.message);
                    }
                );
            }

            // Localizar al usuario automáticamente al iniciar el mapa
            locateUser();

            // Evento para el botón de localización
            document.getElementById('locateMe').addEventListener('click', function () {
                locateUser();
            });

            // Función para dibujar el recorrido entre origen y destino
            function drawRoute(origin, destination) {
                if (routeLine) {
                    map.removeLayer(routeLine); // Eliminar la ruta anterior si existe
                }

                // Usar la API de OSRM para obtener la ruta
                fetch(`https://router.project-osrm.org/route/v1/driving/${origin.lng},${origin.lat};${destination.lng},${destination.lat}?overview=full&geometries=geojson`)
                    .then(response => response.json())
                    .then(data => {
                        var routeCoordinates = data.routes[0].geometry.coordinates;
                        var latLngs = routeCoordinates.map(coord => [coord[1], coord[0]]);

                        // Dibujar la línea en el mapa
                        routeLine = L.polyline(latLngs, { color: 'blue' }).addTo(map);

                        // Ajustar el mapa para que se vea toda la ruta
                        map.fitBounds(routeLine.getBounds());
                    })
                    .catch(error => {
                        console.error('Error al dibujar la ruta:', error);
                    });
            }

            // Función para eliminar un marcador
            function removeMarker(marker, inputId) {
                if (marker) {
                    map.removeLayer(marker);
                    marker = null;
                }
                if (inputId) {
                    document.getElementById(inputId).value = '';
                }
            }

            // Evento para seleccionar origen y destino en el mapa
            map.on('click', function (e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                // Obtener la dirección a partir de las coordenadas
                reverseGeocode(lat, lng, function (address) {
                    if (address) {
                        if (!origenMarker) {
                            origenMarker = L.marker(e.latlng, { icon: origenIcon }).addTo(map); // Usar el icono de origen
                            document.getElementById('origen').value = address; // Mostrar la dirección en el input de origen
                        } else if (!destinoMarker) {
                            destinoMarker = L.marker(e.latlng, { icon: destinoIcon }).addTo(map); // Usar el icono de destino
                            document.getElementById('destino').value = address; // Mostrar la dirección en el input de destino

                            // Dibujar la ruta entre origen y destino
                            drawRoute(origenMarker.getLatLng(), destinoMarker.getLatLng());
                        }
                    } else {
                        alert('No se pudo obtener la dirección para las coordenadas seleccionadas.');
                    }
                });
            });

            // Evento para eliminar el marcador de origen
            document.getElementById('clearOrigen').addEventListener('click', function () {
                removeMarker(origenMarker, 'origen');
                origenMarker = null;
                if (routeLine) {
                    map.removeLayer(routeLine);
                    routeLine = null;
                }
            });

            // Evento para eliminar el marcador de destino
            document.getElementById('clearDestino').addEventListener('click', function () {
                removeMarker(destinoMarker, 'destino');
                destinoMarker = null;
                if (routeLine) {
                    map.removeLayer(routeLine);
                    routeLine = null;
                }
            });

            // Evento para geocodificar el origen cuando se escribe en el input
            document.getElementById('origen').addEventListener('change', function () {
                var address = this.value;
                geocode(address, function (coords) {
                    if (coords) {
                        if (origenMarker) {
                            origenMarker.setLatLng(coords);
                        } else {
                            origenMarker = L.marker(coords, { icon: origenIcon }).addTo(map); // Usar el icono de origen
                        }
                        map.setView(coords, 13);

                        // Si ya hay un destino, dibujar la ruta
                        if (destinoMarker) {
                            drawRoute(coords, destinoMarker.getLatLng());
                        }
                    } else {
                        alert('No se pudo encontrar la dirección de origen.');
                    }
                });
            });

            // Evento para geocodificar el destino cuando se escribe en el input
            document.getElementById('destino').addEventListener('change', function () {
                var address = this.value;
                geocode(address, function (coords) {
                    if (coords) {
                        if (destinoMarker) {
                            destinoMarker.setLatLng(coords);
                        } else {
                            destinoMarker = L.marker(coords, { icon: destinoIcon }).addTo(map); // Usar el icono de destino
                        }
                        map.setView(coords, 13);

                        // Si ya hay un origen, dibujar la ruta
                        if (origenMarker) {
                            drawRoute(origenMarker.getLatLng(), coords);
                        }
                    } else {
                        alert('No se pudo encontrar la dirección de destino.');
                    }
                });
            });

            // Evento para buscar conductor
            document.getElementById('buscarConductor').addEventListener('click', function () {
                var origen = document.getElementById('origen').value;
                var destino = document.getElementById('destino').value;
                var metodo_pago = document.querySelector('input[name="metodo_pago"]:checked').value;
                var tarifa = document.getElementById('tarifa').value;
                var descripcion = document.getElementById('descripcion').value;
                // Validar que los campos estén completos
                if (!origen || !destino || !metodo_pago || !tarifa ) {
                    alert('Por favor, complete todos los campos.');
                    return;
                }

                // Enviar la solicitud al backend
                fetch('/solicitarServicio', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        origen: origen,
                        destino: destino,
                        metodo_pago: metodo_pago,
                        tarifa: tarifa,
                        descripcion: descripcion
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Viaje creado:', data);
                        alert('Viaje solicitado con éxito.');
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hubo un error al solicitar el viaje.');
                    });
            });

            // Evento para cancelar el viaje
            document.getElementById('cancelarViaje').addEventListener('click', function () {
                fetch('/cancelarViaje', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Viaje cancelado:', data);
                        alert('Viaje cancelado con éxito.');

                        removeMarker(origenMarker, 'origen');
                        removeMarker(destinoMarker, 'destino');
                        if (routeLine) {
                            map.removeLayer(routeLine);
                            routeLine = null;
                        }
                        document.getElementById('tarifa').value = '';
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Hubo un error al cancelar el viaje.');
                    });
            });
        });
    </script>
@endsection