@extends('layouts.layout')

@section('title', 'Inicio')

@section('content')
    <div class="map-container">
        <iframe width="100%" height="100%" frameborder="0" style="border:0"
            src="https://www.google.com/maps/embed/v1/place?key=TU_API_KEY&q=Potosi,Bolivia" allowfullscreen>
        </iframe>
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
@endsection
