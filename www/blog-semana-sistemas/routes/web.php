<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\PublicacionController;
use App\Http\Controllers\Admin\ImagenPublicacionController;
use App\Http\Controllers\Admin\EvidenciaController;
use App\Http\Controllers\Admin\UsuarioController;

// Pagina principal
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

    // Cerrar sesion
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Publicaciones
    Route::get('/publicaciones', function () {
        return view('public.index');
    })->name('public.index');
});

// Panel administrativo (protegido por auth y middleware admin)
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // CRUD de Categorias
        Route::resource('categorias', CategoriaController::class)
            ->except(['show'])
            ->parameters(['categorias' => 'categoria']);

        // CRUD de Publicaciones
        Route::resource('publicaciones', PublicacionController::class)
            ->except(['show'])
            ->parameters(['publicaciones' => 'publicacion']);

        // Administracion de imagenes de Publicaciones
        Route::get('imagenes', [ImagenPublicacionController::class, 'index'])->name('imagenes.index');
        Route::post('imagenes', [ImagenPublicacionController::class, 'store'])->name('imagenes.store');
        Route::put('imagenes/{imagen}', [ImagenPublicacionController::class, 'update'])->name('imagenes.update');
        Route::patch('imagenes/{imagen}/principal', [ImagenPublicacionController::class, 'marcarPrincipal'])->name('imagenes.principal');
        Route::delete('imagenes/{imagen}', [ImagenPublicacionController::class, 'destroy'])->name('imagenes.destroy');

        // Administración de Evidencias
        Route::get('evidencias', [EvidenciaController::class, 'index'])->name('evidencias.index');
        Route::post('evidencias', [EvidenciaController::class, 'store'])->name('evidencias.store');
        Route::put('evidencias/{evidencia}', [EvidenciaController::class, 'update'])->name('evidencias.update');
        Route::delete('evidencias/{evidencia}', [EvidenciaController::class, 'destroy'])->name('evidencias.destroy');
        // Gestión de Usuarios y Roles
        Route::resource('usuarios', UsuarioController::class)
            ->except(['show'])
            ->parameters(['usuarios' => 'usuario']);
        Route::patch('usuarios/{usuario}/rol', [UsuarioController::class, 'cambiarRol'])
            ->name('usuarios.rol');
    });