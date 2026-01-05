<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition(): array
    {
        return [
            // Self-referencing key ko simple rakhne ke liye null rakha hai
            'parent_id' => null, 
            
            // Jab account banega toh uske liye ek currency bhi generate hogi
            'currency_id' => Currency::all()->random()->id, 
            
            'name' => fake()->company(), // Company Name
            'phone' => fake()->phoneNumber(),
            'person' => fake()->name(), // Contact Person ka naam
            'address' => fake()->address(),
            
            // Financial fields
            'balance' => fake()->randomFloat(2, -5000, 50000), // Balance negative bhi ho sakta hai
            'amount' => fake()->randomFloat(2, 0, 100000),
            'original_amount' => fake()->randomFloat(2, 0, 100000),
            
            'projects_count' => fake()->numberBetween(0, 50), // 0 se 50 ke beech random count
            
            // Location fields
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
        ];
    }
}