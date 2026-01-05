<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Currency;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'account_id' => 1, // Ya existing ID use karein
            'currency_id' => 1, // Ya existing ID use karein
            
            'name' => fake()->sentence(3), // Random project name, e.g., "Cool Website Design"
            'amount' => fake()->randomFloat(2, 1000, 50000), // 1000 se 50000 ke beech amount
            'original_amount' => fake()->randomFloat(2, 1000, 50000),
            'paid' => fake()->randomFloat(2, 0, 20000), // Paid amount
            
            'is_available' => fake()->boolean(80), // 80% chance true
            'is_duplicable' => fake()->boolean(20), // 20% chance true
            'is_sellable' => fake()->boolean(20), // 20% chance true
            
            'live_url' => fake()->url(), // Random URL
            'demo_url' => fake()->url(), // Random URL
            
            'started_at' => fake()->date(), // Random date
            'is_live' => fake()->boolean(50), // 50% chance true
        ];
    }
}