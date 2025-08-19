<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario admin
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'ci' => '00000001',
            'nombre_completo' => 'Administrador General',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'rol' => 'superadministrador',
        ]);
        User::create([
            'name' => 'Jefe',
            'username' => 'jefe',
            'ci' => '00000002',
            'nombre_completo' => 'Jefe General',
            'email' => 'jefe@example.com',
            'password' => Hash::make('12345678'),
            'rol' => 'jefe',
        ]);
        User::create([
            'name' => 'Admin',
            'username' => 'tecnico',
            'ci' => '00000003',
            'nombre_completo' => 'Tecnico General',
            'email' => 'tecnico@example.com',
            'password' => Hash::make('12345678'),
            'rol' => 'tecnico',
        ]);

        // 15 usuarios fake
        User::factory()->count(15)->create();
    }
}
