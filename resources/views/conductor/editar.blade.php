@extends('layouts.layout')

@section('title', 'Editar Perfil')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-[#5b8c1a] py-10">
    <div class="w-full max-w-4xl bg-[#c5ff7d] p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center mb-6">Editar Perfil</h2>

        <form action="{{ route('conductor.editar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna Izquierda -->
                <div>
                    <div class="mb-4">
                        <label class="block font-medium">Nombre(s)</label>
                        <input type="text" name="nombres" value="{{ old('nombres', $persona->getNombres()) }}" 
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('nombres') border-red-500 @enderror">
                        @error('nombres') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Apellido(s)</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos', $persona->getApellidos()) }}" 
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('apellidos') border-red-500 @enderror">
                        @error('apellidos') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">CI</label>
                        <input type="text" name="ci" value="{{ old('ci', $persona->getCi()) }}" 
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('ci') border-red-500 @enderror">
                        @error('ci') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email', $persona->getEmail()) }}" 
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                </div>

                <!-- Columna Derecha -->
                <div>
                    <div class="mb-4">
                        <label class="block font-medium">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono', $persona->getTelefono()) }}" 
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('telefono') border-red-500 @enderror">
                        @error('telefono') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Usuario</label>
                        <input type="text" name="usuario" value="{{ old('usuario', $persona->getUsuario()) }}" 
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('usuario') border-red-500 @enderror">
                        @error('usuario') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    @if (Auth::user()->rol === 'Conductor')
                        <div class="mb-4">
                            <label class="block font-medium">Número de Licencia</label>
                            <input type="text" name="licencia" value="{{ old('licencia', $conductor->getLicencia()) }}" 
                                class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('licencia') border-red-500 @enderror">
                            @error('licencia') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        </div>
                    @endif
                    <div class="mb-4">
                        <label class="block font-medium">Foto</label>
                        <input type="file" name="foto" accept="image/*" onchange="previewImage(event)" 
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('foto') border-red-500 @enderror">
                        @error('foto') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                        <img id="img-preview" class="mt-4 rounded-lg w-32 h-32 object-cover border shadow" 
                            src="{{ old('foto') ? asset('storage/' . old('foto')) : asset('storage/' . Auth::user()->foto) }}" 
                            alt="Foto de perfil">
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-4 mt-6">
            <a href="{{ route('home-conductor') }}" class="w-1/2 text-center bg-gray-600 text-white py-2 rounded hover:bg-red-700 transition">Cancelar</a>
                <button type="submit" class="w-1/2 bg-black text-white py-2 rounded hover:bg-green-800 transition">Guardar Cambios</button>                
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = () => document.getElementById('img-preview').src = reader.result;
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

@endsection
