<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministradorSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new \RuntimeException(
                'No se permiten administradores de prueba en producción.'
            );
        }

        $administradores = [
            [
                'name' => 'Fabiola',
                'email' => 'fabiola@admin.com',
            ],
            [
                'name' => 'Juli',
                'email' => 'juli@admin.com',
            ],
        ];

        foreach ($administradores as $admin) {
            $usuario = User::firstOrNew([
                'email' => $admin['email'],
            ]);

            $usuario->name = $admin['name'];
            $usuario->role = 'admin';

            if (!$usuario->exists) {
                $usuario->password = Hash::make('123456');
            }

            $usuario->save();
        }
    }
}
