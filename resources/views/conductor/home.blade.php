@extends('layouts.layout')

@section('title', 'Inicio')

@section('content')
    <div class="map-container">
        <iframe width="100%" height="100%" frameborder="0" style="border:0"
            src="https://www.google.com/maps/embed/v1/place?key=TU_API_KEY&q=Potosi,Bolivia" allowfullscreen>
        </iframe>
    </div>

    <!-- Bottom controls -->
    <div class="bottom-bar">
        <img src="{{ asset('images/car.png') }}" alt="Auto" class="car-image">
        <div class="payment-options text-center">
            <p class="mb-1">Tipo de pago</p>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment" id="qr" value="qr">
                <label class="form-check-label" for="qr">QR</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment" id="efectivo" value="efectivo">
                <label class="form-check-label" for="efectivo">Efectivo</label>
            </div>
        </div>
        <div class="input-group mt-3">
            <input type="text" class="form-control" placeholder="Ofrezca su tarifa">
            <span class="input-group-text">Bs</span>
        </div>
        <div class="bg-blue-500 w-32 h-32 text-white">hola</div>
        <div class="input-group mt-3">
            <input type="text" class="form-control" placeholder="Ir a">
            <button class="btn btn-success">Buscar Conductor</button>
            <button class="btn btn-danger">Cancelar Viaje</button>
        </div>
    </div>
@endsection
