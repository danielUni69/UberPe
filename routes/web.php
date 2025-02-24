<?php

use App\Http\Controllers\ConductorController;
use App\Http\Controllers\PersonaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PersonaController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PersonaController::class, 'login'])->name('login');
Route::get('/home', [PersonaController::class, 'index'])->name('home');
Route::get('/registro', [PersonaController::class, 'showRegistroForm'])->name('registro');
Route::post('/registro', [PersonaController::class, 'store'])->name('registro');
Route::get('/conductor/registrar', [ConductorController::class, 'showRegistroForm'])->name('conductor.registro');
Route::post('/conductor/registrar', [ConductorController::class, 'store'])->name('conductor.registro');
Route::get('/persona/editar', [PersonaController::class, 'showEditarForm'])->name('persona.editar');
Route::post('/persona/editar', [PersonaController::class, 'update'])->name('persona.editar');
Route::get('/conductor/editar', [ConductorController::class, 'showEditConductorForm'])->name('conductor.editar');
