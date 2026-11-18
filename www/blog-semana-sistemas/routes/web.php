<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Página principal
Route::get('/', function () {
    return view('home');
})->name('home');

// Rutas para visitantes
Route::middleware('guest')->group(function () {

    // Login
    Route::get('/login', [AuthController::class, 'mostrarLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.procesar');

    // Registro
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])
        ->name('registro');

    Route::post('/registro', [AuthController::class, 'registrar'])
        ->name('registro.procesar');
});

// Rutas para usuarios autenticados
Route::middleware('auth')->group(function () {

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Publicaciones
    Route::get('/publicaciones', function () {
        return view('publicaciones.index');
    })->name('publicaciones.index');

    // Panel administrativo
    Route::get('/admin', function () {

        abort_unless(auth()->user()->isAdmin(), 403);

        return view('admin.dashboard');

    })->name('admin.dashboard');
});
