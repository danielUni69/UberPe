@extends('layouts.layout')

@section('title', 'Lista de Conductores')

@section('content')
<div class="container text-center">
    <h2 class="mt-4">Lista de Conductores</h2>
    <div class="card-container">
        @foreach($conductores as $conductor)
        <div class="card">
            <div class="header">
                <div class="avatar">
                    <img src="{{ asset('images/conductores/' . $conductor->foto) }}" alt="Avatar">
                </div>
                <div class="text-start">
                    <p><strong>Nombre:</strong> {{ $conductor->nombres }}</p>
                    <p><strong>Apellido:</strong> {{ $conductor->apellidos }}</p>
                </div>
            </div>
            <img src="https://via.placeholder.com/300x150" class="card-img-top" alt="Carro">
            <div class="card-body text-start">
                <p><strong>CI:</strong> {{ $conductor->ci }}</p>
                <p><strong>Teléfono:</strong> {{ $conductor->telefono }}</p>
                <p><strong>Email:</strong> {{ $conductor->email }}</p>
                <p><strong>Licencia:</strong> {{ $conductor->licencia }}</p>
                <p><strong>Disponible:</strong> {{ $conductor->disponible ? 'Sí' : 'No' }}</p>
                <p><strong>Vehículo:</strong></p>
                <ul>
                    <li><strong>Marca:</strong> {{ $conductor->marca ?? 'No registrado' }}</li>
                    <li><strong>Modelo:</strong> {{ $conductor->modelo ?? 'No registrado' }}</li>
                    <li><strong>Placa:</strong> {{ $conductor->placa ?? 'No registrado' }}</li>
                    <li><strong>Color:</strong> {{ $conductor->color ?? 'No registrado' }}</li>
                </ul>
                <button class="btn btn-light">Habilitar</button>
                <button class="btn btn-custom">Inhabilitar</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
