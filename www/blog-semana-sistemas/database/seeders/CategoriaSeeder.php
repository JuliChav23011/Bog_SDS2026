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
                'nombre' => 'Ponencia',
                'slug' => 'ponencia',
                'descripcion' => 'Ponencias y conferencias de la Semana de Sistemas 2026.',
            ],
            [
                'nombre' => 'Talleres',
                'slug' => 'talleres',
                'descripcion' => 'Talleres prácticos y actividades de aprendizaje.',
            ],
            [
                'nombre' => 'Bingo',
                'slug' => 'bingo',
                'descripcion' => 'Actividades y momentos destacados del Bingo.',
            ],
            [
                'nombre' => 'Feria de proyectos',
                'slug' => 'expo-system',
                'descripcion' => 'Exposición de proyectos tecnológicos Expo System.',
            ],
            [
                'nombre' => 'Comelona de pizza',
                'slug' => 'pizza',
                'descripcion' => 'Actividades de la Comelona de Pizza.',
            ],
            [
                'nombre' => 'Torneo de fútbol sala',
                'slug' => 'futsal',
                'descripcion' => 'Partidos y actividades del torneo de fútbol sala.',
            ],
            [
                'nombre' => 'Parrillada',
                'slug' => 'parrillada',
                'descripcion' => 'Momentos de convivencia durante la Parrillada.',
            ],
        ];

        
        foreach ($categorias as $categoria) {

            $existe = DB::table('categorias')
                ->where('slug', $categoria['slug'])
                ->exists();

            if ($existe) {

                // Actualizar sin modificar la fecha de creación
                DB::table('categorias')
                    ->where('slug', $categoria['slug'])
                    ->update([
                        'nombre' => $categoria['nombre'],
                        'descripcion' => $categoria['descripcion'],
                        'updated_at' => now(),
                    ]);

            } else {

                // Insertar una categoría nueva
                DB::table('categorias')->insert([
                    'nombre' => $categoria['nombre'],
                    'slug' => $categoria['slug'],
                    'descripcion' => $categoria['descripcion'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            }
        }

    }
}
