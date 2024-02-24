<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Service>
 */
class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'libelle' => fake()->unique()->randomElement(['Service Développement logiciel', 'Service Marketing', 'Service Ressources humaines']),
            'description' => fake()->sentence,
            'direction_id' => fake()->randomElement([1, 2])
        ];
    }
}
