<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Account;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $accounts = Account::all();
        $currencies = Currency::all();

        $projects = [
            [
                'name' => 'E-Commerce Website Development',
                'amount' => 15000.00,
                'original_amount' => 15000.00,
                'paid' => 5000.00,
                'is_available' => true,
                'is_duplicable' => false,
                'is_sellable' => false,
                'live_url' => 'https://shop.example.com',
                'demo_url' => 'https://demo-shop.example.com',
                'started_at' => '2025-01-15',
                'is_live' => true,
            ],
            [
                'name' => 'Mobile App for Inventory Management',
                'amount' => 25000.00,
                'original_amount' => 25000.00,
                'paid' => 10000.00,
                'is_available' => true,
                'is_duplicable' => true,
                'is_sellable' => true,
                'live_url' => null,
                'demo_url' => 'https://demo-inventory.example.com',
                'started_at' => '2025-02-01',
                'is_live' => false,
            ],
            [
                'name' => 'Corporate Website Redesign',
                'amount' => 8000.00,
                'original_amount' => 8000.00,
                'paid' => 8000.00,
                'is_available' => true,
                'is_duplicable' => false,
                'is_sellable' => false,
                'live_url' => 'https://corporate.example.com',
                'demo_url' => null,
                'started_at' => '2024-12-10',
                'is_live' => true,
            ],
            [
                'name' => 'Custom CRM Solution',
                'amount' => 35000.00,
                'original_amount' => 35000.00,
                'paid' => 0.00,
                'is_available' => false,
                'is_duplicable' => true,
                'is_sellable' => true,
                'live_url' => null,
                'demo_url' => 'https://demo-crm.example.com',
                'started_at' => '2025-03-01',
                'is_live' => false,
            ],
            [
                'name' => 'API Integration Project',
                'amount' => 12000.00,
                'original_amount' => 12000.00,
                'paid' => 6000.00,
                'is_available' => true,
                'is_duplicable' => false,
                'is_sellable' => false,
                'live_url' => null,
                'demo_url' => 'https://demo-api.example.com',
                'started_at' => '2025-01-20',
                'is_live' => false,
            ],
        ];

        foreach ($projects as $index => $projectData) {
            Project::create(array_merge($projectData, [
                'account_id' => $accounts[$index % $accounts->count()]->id,
                'currency_id' => $currencies->random()->id,
            ]));
        }

        $this->command->info('5 projects seeded successfully.');
    }
}
