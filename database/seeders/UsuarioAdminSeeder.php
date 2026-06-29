<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsuarioAdminSeeder extends Seeder
{
    public function run(): void
    {
        // En PostgreSQL, para limpiar una tabla con IDs serial y llaves foráneas:
        // 'cascade' borra registros relacionados y 'restart identity' reinicia el contador del ID.
        DB::statement('TRUNCATE TABLE usuario RESTART IDENTITY CASCADE');

        User::create([
            'nombre' => 'Admin',
            'apellido' => 'Hidrosuroeste',
            'cedula' => '12345678',
            'correo' => 'admin@hidrosuroeste.com',
            'password' => Hash::make('admin123'),
            'rol' => 'Administrador',
            'fecha_nacimiento' => '1990-01-01',
        ]);
    }
}