@extends('layouts.layout')

@section('title', 'Inicio')

@section('content')
    <div class="map-container">
        <div id="map"></div>
    </div>

    <!-- Bottom controls -->
    <div class="bottom-bar flex justify-between items-center">
        <div>
          @livewire('cambiar-estado')
            <div class="input-group mt-3"> 
                <button class="btn btn-success">Finalizar viaje</button>
                <button class="btn btn-danger">Reportar incidente</button>
            </div>
        </div>
        <div class="flex text-xl">
            <p class="font-bold text-green-700 pr-3">TARIFA: </p>
            <p class="font-bold"> 90bs</p>
        </div>
        
    </div>
<script>
  document.getElementById("toggleSwitch").addEventListener("change", function() {
    const textElement = document.getElementById("toggleText");
    textElement.textContent = this.checked ? "Activado" : "Desactivado";
  });
</script>

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
