<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Annonces>
 */
class AnnoncesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->sentence(15),
            'location' => $this->faker->word(),
            'user_id' => 2,
            'disponibilite' => $this->faker->date(),
            'amenities' => json_encode([
                'wifi' => $this->faker->boolean(),
                'parking' =>  $this->faker->boolean(),
                'piscine' => $this->faker->boolean(),
                'air_conditioning' => $this->faker->boolean(),
            ])
        ];
    }
}
