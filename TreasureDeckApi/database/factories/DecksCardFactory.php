<?php

namespace Database\Factories;

use App\Models\CardsVersion;
use App\Models\Deck;
use Illuminate\Database\Eloquent\Factories\Factory;


class DecksCardFactory  extends Factory
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
            'card_version_id' => CardsVersion::factory(),
            'quantity' => $this->faker->numberBetween(1, 4),
        ];
    }
}
