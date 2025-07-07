<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Alert>
 */
class AlertFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
        'user_id' => User::factory(), // Crea usuario si no existe
        'titulo' => $this->faker->sentence(4),
        'descripcion' => $this->faker->paragraph(),
        'tipo_alerta' => $this->faker->randomElement(['robo', 'incendio', 'accidente']),
        'estado' => $this->faker->randomElement(['pendiente', 'validada']),
        'latitud' => $this->faker->latitude(-90, 90),
        'longitud' => $this->faker->longitude(-180, 180),
        'archivo' => null,
    ];
    }
}
