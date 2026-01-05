<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepositoryFactory extends Factory
{
    protected $model = Repository::class;

    public function definition(): array
    {
        // Providers ke options
        $providers = ['gitlab', 'github', 'bitbucket', 'azure-devops'];
        
        // Project ke existing IDs se random ID lein
        $projectIds = Project::pluck('id')->toArray();
        $projectId = !empty($projectIds) ? fake()->randomElement($projectIds) : 1;
        
        return [
            'project_id' => $projectId,
            'name' => fake()->words(3, true), // e.g., "ecommerce-website-backend"
            'url' => fake()->url(), // Random repository URL
            'provider' => fake()->randomElement($providers), // Random provider
            'is_private' => fake()->boolean(70), // 70% chance private
        ];
    }
}