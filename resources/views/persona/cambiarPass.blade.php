@extends('layouts.layout')

@section('title', 'Cambiar Contraseña')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Cambiar Contraseña</h2>
    <form action="{{ route('persona.cambiarPass') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="current_password" class="form-label">Contraseña Actual</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">Nueva Contraseña</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirmar Nueva Contraseña</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-success">Cambiar Contraseña</button>
    </form>
</div>
@endsection