<?php

use App\Core\Persona;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\PersonaController;
use App\Http\Controllers\VehiculoController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PersonaController::class, 'showLoginForm'])->name('login');
Route::post('/login', [PersonaController::class, 'login'])->name('login');
Route::get('/home', [PersonaController::class, 'index'])->name('home');
Route::get('/dashboard', [ConductorController::class, 'index'])->name('home-conductor');
Route::get('/registro', [PersonaController::class, 'showRegistroForm'])->name('registro');
Route::post('/registro', [PersonaController::class, 'store'])->name('registro');
Route::get('/conductor/registrar', [ConductorController::class, 'showRegistroForm'])->name('conductor.registro');
Route::post('/conductor/registrar', [ConductorController::class, 'store'])->name('conductor.registro');
Route::get('/persona/editar', [PersonaController::class, 'showEditarForm'])->name('persona.editar');
Route::put('/persona/editar', [PersonaController::class, 'update'])->name('persona.editar');
Route::post('/persona/editar', [PersonaController::class, 'update'])->name('persona.editar');
Route::get('/conductor/editar', [ConductorController::class, 'showEditConductorForm'])->name('conductor.editar');
Route::post('/conductor/editar', [ConductorController::class, 'update'])->name('conductor.editar');
Route::get('/cambiar-contrasena', [PersonaController::class, 'showCambiarPass'])->name('cambiar-contrasena');
Route::post('/cambiar-contrasena', [PersonaController::class, 'cambiarPass'])->name('cambiar-contrasena');
Route::get('/vehiculo', [VehiculoController::class, 'index'])->name('vehiculo.index');
Route::get('/admin/home', [AdministradorController::class, 'index'])->name('admin.home');
Route::put('/admin/editar', [PersonaController::class, 'update'])->name('admin.editar');
Route::get('/admin/editar', [PersonaController::class, 'showEditarForm'])->name('admin.editar');
Route::get('/admin/conductores', [PersonaController::class, 'showConductores'])->name('admin.conductores');
Route::get('/admin/pasajeros', [AdministradorController::class, 'showPasajeros'])->name('admin.pasajeros');
Route::get('/admin/registro', [AdministradorController::class, 'showRegistroForm'])->name('admin.registro');
Route::post('/admin/registro', [AdministradorController::class, 'store'])->name('admin.registro');
Route::post('/logout', [PersonaController::class, 'logout'])->name('logout');
