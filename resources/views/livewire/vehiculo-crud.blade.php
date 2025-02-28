<div class="min-h-screen flex items-center justify-center bg-[#5b8c1a] py-10">
    <div class="w-full max-w-4xl bg-[#c5ff7d] p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center mb-6">Gestión de Vehículos</h2>
        
        <!-- Formulario -->
        <form wire:submit.prevent="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna Izquierda -->
                <div>
                    <div class="mb-4">
                        <label class="block font-medium">Marca</label>
                        <input type="text" wire:model="marca" class="w-full p-2 border rounded focus:ring focus:ring-green-300">
                        @error('marca') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Modelo</label>
                        <input type="text" wire:model="modelo" class="w-full p-2 border rounded focus:ring focus:ring-green-300">
                        @error('modelo') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div>
                    <div class="mb-4">
                        <label class="block font-medium">Placa</label>
                        <input type="text" wire:model="placa" class="w-full p-2 border rounded focus:ring focus:ring-green-300">
                        @error('placa') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Color</label>
                        <input type="text" wire:model="color" class="w-full p-2 border rounded focus:ring focus:ring-green-300">
                        @error('color') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Foto -->
            <div class="mb-4">
                <label class="block font-medium">Foto</label>
                <input type="file" wire:model="foto" class="w-full p-2 border rounded focus:ring focus:ring-green-300">
                @error('foto') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                
                @if ($foto_temp)
                    <div class="mt-4 flex justify-center">
                        <img src="{{ asset('storage/' . $foto_temp) }}" class="rounded-lg w-32 h-32 object-cover border shadow" alt="Foto del vehículo">
                    </div>
                @endif
            </div>

            <!-- Botones -->
            <div class="flex gap-4 mt-6">
                <button type="button" wire:click="resetForm" class="w-1/2 bg-gray-600 text-white py-2 rounded hover:bg-red-700 transition">Cancelar</button>
                <button type="submit" class="w-1/2 bg-black text-white py-2 rounded hover:bg-green-800 transition">
                    {{ $isEdit ? 'Actualizar' : 'Guardar' }}
                </button>
            </div>
        </form>
    </div>
</div>
