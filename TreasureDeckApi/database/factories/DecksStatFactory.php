<?php

namespace Database\Factories;

use App\Models\Deck;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DecksStat>
 */
class DecksStatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'deck_id' => Deck::factory(),
            'wins' => $this->faker->numberBetween(1, 4),
            'losses' => $this->faker->numberBetween(1, 4),
            'dice' => true,
        ];
    }
}
