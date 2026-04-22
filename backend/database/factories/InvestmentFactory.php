<?php

namespace Database\Factories;

use App\Models\Investment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Investment>
 */
class InvestmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $invested = fake()->randomFloat(2, 100, 100000);

        return [
            'name' => fake()->words(2, true),
            'amount_invested' => $invested,
            'current_value' => fake()->randomFloat(2, 0, $invested * 1.5),
        ];
    }
}
