<?php

namespace Database\Factories;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nit' => $this->faker->unique()->numerify('########'),
            'nombre' => $this->faker->name,
            'direccion' => $this->faker->address,
            'telefono' => $this->faker->phoneNumber,
            'estado' => $this->faker->randomElement(['Activo', 'Inactivo'])
        ];
    }
}
