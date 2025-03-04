@extends('layouts.layout')

@section('title', 'Registrar Administrador')

@section('content')

<div class="min-h-screen flex items-center justify-center bg-[#5b8c1a] py-10">
    <div class="w-full max-w-4xl bg-[#c5ff7d] p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold text-center mb-6">Registrar Administrador</h2>

        <form action="{{ route('admin.registro') }}" method="GET">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Columna Izquierda -->
                <div>
                    <div class="mb-4">
                        <label class="block font-medium">Nombre(s)</label>
                        <input type="text" name="nombres" value="{{ old('nombres') }}"
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('nombres') border-red-500 @enderror">
                        @error('nombres') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Apellido(s)</label>
                        <input type="text" name="apellidos" value="{{ old('apellidos') }}"
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('apellidos') border-red-500 @enderror">
                        @error('apellidos') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">CI</label>
                        <input type="text" name="ci" value="{{ old('ci') }}"
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('ci') border-red-500 @enderror">
                        @error('ci') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('email') border-red-500 @enderror">
                        @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Columna Derecha -->
                <div>
                    <div class="mb-4">
                        <label class="block font-medium">Teléfono</label>
                        <input type="text" name="telefono" value="{{ old('telefono') }}"
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('telefono') border-red-500 @enderror">
                        @error('telefono') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Usuario</label>
                        <input type="text" name="usuario" value="{{ old('usuario') }}"
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('usuario') border-red-500 @enderror">
                        @error('usuario') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Contraseña</label>
                        <input type="password" name="password"
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('password') border-red-500 @enderror">
                        @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block font-medium">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation"
                            class="w-full p-2 border rounded focus:ring focus:ring-green-300 @error('password_confirmation') border-red-500 @enderror">
                        @error('password_confirmation') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Botones -->
            <div class="flex gap-4 mt-6">
                <a href="{{ route('admin.home') }}" class="w-1/2 text-center bg-gray-600 text-white py-2 rounded hover:bg-red-700 transition">Cancelar</a>
                <button type="submit" class="w-1/2 bg-black text-white py-2 rounded hover:bg-green-800 transition">Registrar</button>
            </div>
        </form>
    </div>
</div>

@endsection
