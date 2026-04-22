<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => fake()->randomFloat(2, 1, 10000),
            'type' => fake()->randomElement(['income', 'expense']),
            'category' => fake()->optional()->word(),
            'description' => fake()->sentence(),
            'date' => fake()->date(),
        ];
    }
}
