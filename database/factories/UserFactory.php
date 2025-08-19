<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        $roles = ['tecnico','jefe','superadministrador'];
        return [
            'name' => $this->faker->firstName(),
            'username' => $this->faker->unique()->userName(),
            'ci' => $this->faker->unique()->numerify('########'),
            'nombre_completo' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // clave por defecto
            'rol' => $this->faker->randomElement($roles),
            'remember_token' => Str::random(10),
        ];
    }
}
