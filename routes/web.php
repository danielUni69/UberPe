<?php

use App\Http\Controllers\PersonaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PersonaController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PersonaController::class, 'login'])->name('login');
Route::get('/viaje', [PersonaController::class, 'index'])->name('viaje');
Route::get('/registro', [PersonaController::class, 'showRegistroForm'])->name('registro');
Route::post('/registro', [PersonaController::class, 'store'])->name('registro');
