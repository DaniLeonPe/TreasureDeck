<?php

namespace Database\Factories;

use App\Models\Expansion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpansionFactory extends Factory
{
    protected $model = Expansion::class;

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word,
        ];
    }
}
