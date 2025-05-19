<?php

namespace Database\Factories;

use App\Models\Card;
use Illuminate\Database\Eloquent\Factories\Factory;

class CardFactory extends Factory
{
    protected $model = Card::class;

    public function definition()
    {
        return [
              'name' => $this->faker->word(),
            'collector_number' => $this->faker->unique()->numerify('###'),
            'rarity' => $this->faker->randomElement(['common', 'uncommon', 'rare', 'mythic']),
            'expansion_id' => \App\Models\Expansion::factory(),

        ];
    }
}
