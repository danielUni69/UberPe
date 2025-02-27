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
        <label class="inline-flex items-center cursor-pointer">
  <input type="checkbox" id="toggleSwitch" class="sr-only peer" <?php echo $disponible ? 'checked' : ''; ?>>
  <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600 dark:peer-checked:bg-blue-600"></div>
  <span id="toggleText" class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">
    <?php echo $disponible ? 'Activado' : 'Desactivado'; ?>
  </span>
</label>

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
