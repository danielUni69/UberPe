@extends('layouts.layout')

@section('title', 'Inicio')

@section('content')

<div class="map-container" id="map"></div>
<!-- Bottom controls -->
<div class="bottom-bar">
@livewire('pasajero-live')
    <div class="payment-options text-center">
        <p class="mb-1">Tipo de pago</p>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="metodo_pago" id="billetera" value="Billetera">
            <label class="form-check-label" for="billetera">Billetera</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="metodo_pago" id="efectivo" value="Efectivo">
            <label class="form-check-label" for="efectivo">Efectivo</label>
        </div>
    </div>
    <div>
        <div class="input-group mt-3">
            <input type="text" class="form-control" id="tarifa" name="tarifa" placeholder="Ofrezca su tarifa">
            <span class="input-group-text">Bs</span>
        </div>
    </div>
    
    <div class="input-group mt-3">
        <input type="text" id="origen" name="origen" class="form-control" placeholder="Origen">
        <button class="btn btn-warning" id="clearOrigen">Eliminar Origen</button>
        <input type="text" id="destino" name="destino" class="form-control" placeholder="Ir a">
        <button class="btn btn-warning" id="clearDestino">Eliminar Destino</button>
        <button class="btn btn-success" id="buscarConductor">Buscar Conductor</button>
        <button class="btn btn-danger" id="cancelarViaje">Cancelar Viaje</button>
        <button class="btn btn-primary" id="locateMe">Localizarme</button>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Inicialización del mapa
        var map = L.map('map').setView([-17.7833, -63.1821], 13); // Coordenadas iniciales (Santa Cruz, Bolivia)

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

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
                            icon: L.icon({
                                iconUrl: '{{ asset("images/user-location.png") }}', // Icono personalizado
                                iconSize: [32, 32]
                            })
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
                        origenMarker = L.marker(e.latlng).addTo(map);
                        document.getElementById('origen').value = address; // Mostrar la dirección en el input de origen
                    } else if (!destinoMarker) {
                        destinoMarker = L.marker(e.latlng).addTo(map);
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
                        origenMarker = L.marker(coords).addTo(map);
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
                        destinoMarker = L.marker(coords).addTo(map);
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

            // Validar que los campos estén completos
            if (!origen || !destino || !metodo_pago || !tarifa) {
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
                    tarifa: tarifa
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