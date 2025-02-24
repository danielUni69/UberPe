@extends('layouts.layout')

@section('title', 'Inicio')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-primary mb-6">Registrar Conductor</h1>

        <!-- Formulario multi-paso -->
        <form method="POST" action="{{ route('conductor.registro') }}" class="bg-white p-6 rounded-lg shadow-lg">
            @csrf

            <!-- Indicador de pasos -->
            <div class="flex justify-center mb-8">
                <div class="step-indicator bg-secondary text-white w-8 h-8 rounded-full flex items-center justify-center mx-2"
                    data-step="1">1</div>
                <div class="step-indicator bg-gray-300 text-gray-600 w-8 h-8 rounded-full flex items-center justify-center mx-2"
                    data-step="2">2</div>
                <div class="step-indicator bg-gray-300 text-gray-600 w-8 h-8 rounded-full flex items-center justify-center mx-2"
                    data-step="3">3</div>
            </div>

            <!-- Paso 1 - Datos Personales -->
            <div class="form-step active" data-step="1">
                <h2 class="text-xl font-semibold text-secondary mb-4">Datos Personales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="ci" placeholder="Cédula" class="p-2 border border-primary rounded">
                    <input type="text" name="nombres" placeholder="Nombres" class="p-2 border border-primary rounded">
                    <input type="text" name="apellidos" placeholder="Apellidos"
                        class="p-2 border border-primary rounded">
                    <input type="text" name="telefono" placeholder="Teléfono" class="p-2 border border-primary rounded">
                    <input type="email" name="email" placeholder="Email" class="p-2 border border-primary rounded">
                    <input type="text" name="usuario" placeholder="Usuario" class="p-2 border border-primary rounded">
                    <input type="password" name="password" placeholder="Contraseña"
                        class="p-2 border border-primary rounded">
                    <input type="number" name="billetera" placeholder="Billetera"
                        class="p-2 border border-primary rounded">
                </div>
            </div>

            <!-- Controles de navegación -->
            <div class="flex justify-between mt-6">
                <button type="button"
                    class="prev-step bg-accent text-white px-4 py-2 rounded hover:bg-secondary disabled:bg-gray-300"
                    disabled>Anterior</button>
                <button type="button"
                    class="next-step bg-accent text-white px-4 py-2 rounded hover:bg-secondary">Siguiente</button>
                <button type="submit"
                    class="final-step bg-primary text-black px-4 py-2 rounded hover:bg-secondary hidden">Guardar</button>
            </div>
        </form>
    </div>


@endsection
