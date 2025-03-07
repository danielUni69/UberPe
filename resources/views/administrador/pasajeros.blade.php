@extends('layouts.layout')

@section('title', 'Lista de Psajeros')

@section('content')

<div class="container">
    <h1>Lista de Pasajeros</h1>
    <table class="table">
        <thead>
            <tr>
                <th>CI</th>
                <th>Nombres</th>
                <th>Apellidos</th>
                <th>Teléfono</th>
                <th>Email</th>

            </tr>
        </thead>
        <tbody>
            @foreach($pasajeros as $pasajero)
            <tr>
                <td>{{ $pasajero->ci }}</td>
                <td>{{ $pasajero->nombres }}</td>
                <td>{{ $pasajero->apellidos }}</td>
                <td>{{ $pasajero->telefono }}</td>
                <td>{{ $pasajero->email }}</td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
