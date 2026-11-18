<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'nombre' => 'Ponencias',
                'slug' => 'ponencias',
            ],
            [
                'nombre' => 'Talleres',
                'slug' => 'talleres',
            ],
            [
                'nombre' => 'Bingo',
                'slug' => 'bingo',
            ],
            [
                'nombre' => 'Feria de proyectos',
                'slug' => 'feria-de-proyectos',
            ],
            [
                'nombre' => 'Comelona de pizza',
                'slug' => 'comelona-de-pizza',
            ],
            [
                'nombre' => 'Torneo de fútbol sala',
                'slug' => 'torneo-de-futbol-sala',
            ],
            [
                'nombre' => 'Parrillada',
                'slug' => 'parrillada',
            ],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->updateOrInsert(
                ['slug' => $categoria['slug']],
                [
                    'nombre' => $categoria['nombre'],
                    'updated_at' => now(),
                ]
            );
        }
    }
}
