<?php

namespace Database\Factories;

use App\Models\Participante;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participante>
 */
class ParticipanteFactory extends Factory
{
    protected $model = Participante::class;

    public function definition(): array
    {
        $provincias = [
            'Cercado','Sajama','Sur Carangas','Litoral','Nor Carangas','Carangas','Saucari',
            'San Pedro de Totora','Sabaya','Ladislao Cabrera','Poopó','Eduardo Avaroa',
            'Pantaleón Dalence','Tomás Barrón','Mejillones','Sebastián Pagador',
        ];

        $municipios = [
            'Antequera','Belén de Andamarca','Caracollo','Carangas','Challapata','Chipaya','Choquecota','Coipasa','Corque',
            'Cruz de Machacamarca','Curahuara de Carangas','El Choro','Escara','Esmeralda','Eucaliptus','Huachacalla','Huanuni',
            'Huayllamarca','La Rivera','Machacamarca','Cercado','Pampa Aullagas','Paria','Pazña','Poopó','Sabaya',
            'Salinas de Garci Mendoza','Santiago de Andamarca','Santiago de Huari','Santuario de Quillacas','Todos Santos',
            'Toledo','Totora','Turco','Yunguyo de Litoral',
        ];

        $exps = ['OR','LP','CB','SC','PT','TJ','BN','PD','CH'];

        return [
            'user_id'        => User::factory(),
            'nombre_completo' => $this->faker->name(),
            'ci'             => (string) $this->faker->numberBetween(1000000, 99999999),
            'celular'        => (string) $this->faker->numberBetween(60000000, 79999999),
            'ci_exp'         => $this->faker->optional()->randomElement($exps),
            'fecha_nac'      => $this->faker->optional()->date(),
            'genero'         => $this->faker->optional()->randomElement(['Femenino','Masculino']),
            'email'          => $this->faker->optional()->safeEmail(),
            'provincia'      => $this->faker->optional()->randomElement($provincias),
            'municipio'      => $this->faker->optional()->randomElement($municipios),
            'zona'           => $this->faker->optional()->randomElement(['Urbana','Rural']),
            'direccion'      => $this->faker->optional()->streetAddress(),
            'ocupacion'      => $this->faker->optional()->jobTitle(),
            'organizacion'   => $this->faker->optional()->company(),
            'observaciones'  => $this->faker->optional()->sentence(10),
        ];
    }
}
