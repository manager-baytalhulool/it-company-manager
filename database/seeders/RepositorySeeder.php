<?php

namespace Database\Seeders;

use App\Models\Repository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RepositorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kitne repositories create karne hain
        $count = 50;
        
        // Factory use karke repositories generate karein
        Repository::factory()->count($count)->create();
        
        // Optional: Console pe message show karein
        $this->command->info("{$count} repositories successfully created!");
    }
}