@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<div class="bg-gray-100 p-6">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold">Ganancias Totales</h2>
                <p class="text-2xl font-bold mt-2">$15,230</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold">Viajes Completados</h2>
                <p class="text-2xl font-bold mt-2">1,254</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold">Pasajeros Activos</h2>
                <p class="text-2xl font-bold mt-2">3,542</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold">Conductores Disponibles</h2>
                <p class="text-2xl font-bold mt-2">189</p>
            </div>
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-xl font-semibold">Incidencias Reportadas</h2>
                <p class="text-2xl font-bold mt-2 text-red-500">12</p>
            </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md mt-6">
            <h2 class="text-xl font-semibold">Métodos de Pago Usados</h2>
            <ul class="mt-2">
                <li class="flex justify-between border-b py-2">
                    <span>Tarjeta de Crédito</span>
                    <span class="font-bold">65%</span>
                </li>
                <li class="flex justify-between border-b py-2">
                    <span>Efectivo</span>
                    <span class="font-bold">25%</span>
                </li>
                <li class="flex justify-between py-2">
                    <span>Billetera Virtual</span>
                    <span class="font-bold">10%</span>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection