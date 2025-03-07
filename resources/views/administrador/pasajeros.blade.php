@extends('layouts.layout')

@section('title', 'Lista de Pasajeros')

@section('content')
<style>
    body {
        background-color: #77A300;
        color: white;
    }
    .card-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 20px;
        margin-top: 30px;
        border-radius: 15px;
    }
    .card {
        width: 300px;
        border-radius: 10px;
        background-color: white;
        color: #333;
        padding: 15px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
        background-color: #7e57c2;
        color: white;
        border: none;
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .btn-light {
        background-color: #a0fb0e;
        color: white;
        border: none;
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 5px;
    }
    .btn-custom:hover {
        background-color: #5C3C72;
    }
    .btn-light:hover {
        background-color: #85d60a;
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
    <h2 class="mt-4">Lista de Pasajeros</h2>
    <div class="card-container">
        @foreach($pasajeros as $pasajero)
        <div class="card">






            <div class="card-body text-start">
                <p><strong>Nombre:</strong> {{ $pasajero->nombres }}</p>
                <p><strong>Apellido:</strong> {{ $pasajero->apellidos }}</p>
                <p><strong>CI:</strong> {{ $pasajero->ci }}</p>
                <p><strong>Teléfono:</strong> {{ $pasajero->telefono }}</p>
                <p><strong>Email:</strong> {{ $pasajero->email }}</p>


            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
