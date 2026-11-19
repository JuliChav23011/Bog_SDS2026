<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\PublicacionController;

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
        return view('public.index');
    })->name('public.index');

    // Panel administrativo
    Route::get('/admin', function () {

        abort_unless(auth()->user()->isAdmin(), 403);

        return view('admin.dashboard');

    })->name('admin.dashboard');
});

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categorias', CategoriaController::class)
            ->except(['show'])
            ->parameters(['categorias' => 'categoria']);
    });

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('categorias', CategoriaController::class)
            ->except(['show'])
            ->parameters(['categorias' => 'categoria']);

        Route::resource('publicaciones', PublicacionController::class)
            ->except(['show'])
            ->parameters(['publicaciones' => 'publicacion']);
    });