<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Publicacion;

class CategoriaPublicaController extends Controller
{
    private function mostrarCategoria(string $nombre, string $vista)
    {
        $categoria = Categoria::whereRaw(
            'LOWER(nombre) = LOWER(?)',
            [$nombre]
        )->firstOrFail();

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

        return view($vista, compact('categoria', 'publicaciones'));
    }

    public function ponencias()
    {
        return $this->mostrarCategoria('Ponencia', 'public.ponencias');
    }

    public function talleres()
    {
        return $this->mostrarCategoria('Talleres', 'public.talleres');
    }

    public function bingo()
    {
        return $this->mostrarCategoria('Bingo', 'public.bingo');
    }

    public function feriaProyectos()
    {
        return $this->mostrarCategoria(
            'Feria de proyectos',
            'public.expo-system'
        );
    }

    public function comelonaPizza()
    {
        return $this->mostrarCategoria(
            'Comelona de pizza',
            'public.pizza'
        );
    }

    public function futbolSala()
    {
        return $this->mostrarCategoria(
            'Torneo de fútbol sala',
            'public.futsal'
        );
    }


    public function parrillada()
    {
        return $this->mostrarCategoria('Parrillada', 'public.parrillada');
    }
}
