<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Direction>
 */
class DirectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'libelle' => fake()->unique()->randomElement(['Direction Systeme Informatique', 'Direction des Opérations', 'Direction des Ressources humaines']),
            'description' => fake()->sentence,
            'agence_id' => fake()->randomElement([1, 2, 3])
        ];
    }
}
