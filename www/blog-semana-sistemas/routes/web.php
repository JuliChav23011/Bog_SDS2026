<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoriaController;
use App\Http\Controllers\Admin\PublicacionController;
use App\Http\Controllers\Admin\ImagenPublicacionController;
use App\Http\Controllers\Admin\EvidenciaController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Public\CategoriaPublicaController;

use App\Models\Publicacion;
use App\Models\Categoria;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'mostrarLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.procesar');

    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])
        ->name('registro');

    Route::post('/registro', [AuthController::class, 'registrar'])
        ->name('registro.procesar');

});

Route::middleware('auth')->group(function () {

    // Cerrar sesión
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/publicaciones', function () {

        // Categorías creadas desde el CRUD
        $categorias = Categoria::orderBy('nombre')->get();

        // Publicaciones disponibles
        $publicaciones = Publicacion::with([
            'categoria',
            'autores',
            'imagenes' => function ($query) {
                $query->orderByDesc('es_principal')
                      ->orderBy('orden')
                      ->orderBy('id');
            }
        ])
        ->where('estado', 'publicado')
        ->orderByDesc('fecha_publicacion')
        ->orderByDesc('id')
        ->get();

        return view('public.index', compact(
            'categorias',
            'publicaciones'
        ));

    })->name('public.index');

    Route::get('/publicaciones/contribuidores', function () {
        return view('public.contribuidores');
    })->name('contribuidores.index');

    Route::get('/publicaciones/ponencias',
        [CategoriaPublicaController::class, 'ponencias'])
        ->name('public.ponencias');

    Route::get('/publicaciones/talleres',
        [CategoriaPublicaController::class, 'talleres'])
        ->name('public.talleres');

    Route::get('/publicaciones/bingo',
        [CategoriaPublicaController::class, 'bingo'])
        ->name('public.bingo');

    Route::get('/publicaciones/feria-proyectos',
        [CategoriaPublicaController::class, 'feriaProyectos'])
        ->name('public.expo-system');

    Route::get('/publicaciones/comelona-pizza',
        [CategoriaPublicaController::class, 'comelonaPizza'])
        ->name('public.pizza');

    Route::get('/publicaciones/futbol-sala',
        [CategoriaPublicaController::class, 'futbolSala'])
        ->name('public.futsal');

    Route::get('/publicaciones/parrillada',
        [CategoriaPublicaController::class, 'parrillada'])
        ->name('public.parrillada');



    Route::get('/publicaciones/categoria/{slug}', function (string $slug) {

        $categoria = Categoria::where('slug', $slug)
            ->firstOrFail();

        $publicaciones = Publicacion::with([
            'categoria',
            'autores',
            'imagenes' => function ($query) {
                $query->orderByDesc('es_principal')
                      ->orderBy('orden')
                      ->orderBy('id');
            }
        ])
        ->where('categoria_id', $categoria->id)
        ->where('estado', 'publicado')
        ->orderByDesc('fecha_publicacion')
        ->orderByDesc('id')
        ->get();

        return view('public.categoria', compact(
            'categoria',
            'publicaciones'
        ));

    })->name('public.categoria');

});


Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');


       
        Route::resource('categorias', CategoriaController::class)
            ->except(['show'])
            ->parameters(['categorias' => 'categoria']);


        Route::resource('publicaciones', PublicacionController::class)
            ->except(['show'])
            ->parameters(['publicaciones' => 'publicacion']);


        Route::get('imagenes', [ImagenPublicacionController::class, 'index'])
            ->name('imagenes.index');

        Route::post('imagenes', [ImagenPublicacionController::class, 'store'])
            ->name('imagenes.store');

        Route::put('imagenes/{imagen}', [ImagenPublicacionController::class, 'update'])
            ->name('imagenes.update');

        Route::patch(
            'imagenes/{imagen}/principal',
            [ImagenPublicacionController::class, 'marcarPrincipal']
        )->name('imagenes.principal');

        Route::delete(
            'imagenes/{imagen}',
            [ImagenPublicacionController::class, 'destroy']
        )->name('imagenes.destroy');


        Route::get('evidencias', [EvidenciaController::class, 'index'])
            ->name('evidencias.index');

        Route::post('evidencias', [EvidenciaController::class, 'store'])
            ->name('evidencias.store');

        Route::put('evidencias/{evidencia}', [EvidenciaController::class, 'update'])
            ->name('evidencias.update');

        Route::delete('evidencias/{evidencia}', [EvidenciaController::class, 'destroy'])
            ->name('evidencias.destroy');


        Route::resource('usuarios', UsuarioController::class)
            ->except(['show'])
            ->parameters(['usuarios' => 'usuario']);

        Route::patch(
            'usuarios/{usuario}/rol',
            [UsuarioController::class, 'cambiarRol']
        )->name('usuarios.rol');

    });
