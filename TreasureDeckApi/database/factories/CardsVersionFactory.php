<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CardsVersion>
 */
class CardsVersionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       return [
            'card_id' => \App\Models\Card::factory(),
            'image_url' => $this->faker->imageUrl(),
            'min_price' => $this->faker->randomFloat(2, 1, 10),
            'avg_price' => $this->faker->randomFloat(2, 1, 20),
            'versions' => $this->faker->word(),
        ];
    }
}
