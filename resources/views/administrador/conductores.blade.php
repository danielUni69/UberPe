@extends('layouts.layout')

@section('title', 'Lista de Conductores')

@section('content')
<style>
    body {
        background-color: #77A300;
        color: white;
    }
    .card-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
        border-radius: 15px;
    }
    .card {
        width: 300px;
        border-radius: 10px;
    }
    .header {
        display: flex;
        align-items: center;
        padding: 10px;
    }
    .avatar {
        width: 70px;
        height: 70px;
        background-color: #B599D5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-right: 10px;
    }
    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .text-start {
        text-align: left;
    }
    .btn-custom {
        background-color:#7e57c2;
        color: white;
        border: none;
        font-size: 12px;
    }
    .btn-light{
        background-color: #a0fb0e;
        color: white;
        border: none;
        font-size: 12px;
    }
    .btn-custom:hover {
        background-color: #5C3C72;
    }
    p {
        margin-bottom: 4px;
        font-size: 12px;
    }
    .card-body strong {
        margin-right: 4px;

    }
</style>
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
                <p><strong>CI:</strong> {{ $conductor->persona->ci }}</p>
                <p><strong>Teléfono:</strong> {{ $conductor->persona->telefono }}</p>
                <p><strong>Email:</strong> {{ $conductor->persona->email }}</p>
                <p><strong>Licencia:</strong> {{ $conductor->licencia }}</p>
                <p><strong>Disponible:</strong> {{ $conductor->disponible ? 'Sí' : 'No' }}</p>
                <p><strong>Vehículo:</strong></p>
               <!--
<ul>
    <li><strong>Marca:</strong> // $conductor->vehiculo->marca }}</li>
    <li><strong>Modelo:</strong> //$conductor->vehiculo->modelo }}</li>
    <li><strong>Placa:</strong> // $conductor->vehiculo->placa }}</li>
    <li><strong>Color:</strong>  //$conductor->vehiculo->color }}</li>
</ul>
-->
                <button class="btn btn-light">Habilitar</button>
                <button class="btn btn-custom">Inhabilitar</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
