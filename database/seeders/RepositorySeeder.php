<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Product;
use App\Models\Repository;
use Illuminate\Database\Seeder;

class RepositorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();
        $products = Product::all();

        $repositories = [
            // Product Repositories
            [
                'name' => 'laravel-ecommerce-platform',
                'url' => 'https://github.com/example/laravel-ecommerce',
                'provider' => 'github',
                'repositable_type' => Product::class,
                'repositable_id' => $products->firstWhere('name', 'Laravel E‑commerce Platform')?->id,
            ],
            [
                'name' => 'vue-project-management',
                'url' => 'https://gitlab.com/example/vue-pm',
                'provider' => 'gitlab',
                'repositable_type' => Product::class,
                'repositable_id' => $products->firstWhere('name', 'Vue.js Project Management Tool')?->id,
            ],
            [
                'name' => 'react-native-starter',
                'url' => 'https://github.com/example/react-native-starter',
                'provider' => 'github',
                'repositable_type' => Product::class,
                'repositable_id' => $products->firstWhere('name', 'React Native Mobile App Starter')?->id,
            ],
            [
                'name' => 'wp-seo-plugin',
                'url' => 'https://bitbucket.org/example/wp-seo',
                'provider' => 'bitbucket',
                'repositable_type' => Product::class,
                'repositable_id' => $products->firstWhere('name', 'WordPress SEO Plugin')?->id,
            ],
            [
                'name' => 'python-data-lib',
                'url' => 'https://github.com/example/pydata',
                'provider' => 'github',
                'repositable_type' => Product::class,
                'repositable_id' => $products->firstWhere('name', 'Python Data Analysis Library')?->id,
            ],

            // Project Repositories
            [
                'name' => 'ecommerce-website-dev',
                'url' => 'https://github.com/acme-corp/shop-project',
                'provider' => 'github',
                'repositable_type' => Project::class,
                'repositable_id' => $projects->firstWhere('name', 'E-Commerce Website Development')?->id,
            ],
            [
                'name' => 'inventory-mobile-app',
                'url' => 'https://gitlab.com/tech-solutions/inventory-app',
                'provider' => 'gitlab',
                'repositable_type' => Project::class,
                'repositable_id' => $projects->firstWhere('name', 'Mobile App for Inventory Management')?->id,
            ],
            [
                'name' => 'corporate-website-redesign',
                'url' => 'https://github.com/global-enterprises/corporate-site',
                'provider' => 'github',
                'repositable_type' => Project::class,
                'repositable_id' => $projects->firstWhere('name', 'Corporate Website Redesign')?->id,
            ],
            [
                'name' => 'custom-crm-solution',
                'url' => 'https://bitbucket.org/euro-tech/crm-system',
                'provider' => 'bitbucket',
                'repositable_type' => Project::class,
                'repositable_id' => $projects->firstWhere('name', 'Custom CRM Solution')?->id,
            ],
            [
                'name' => 'api-integration',
                'url' => 'https://dev.azure.com/paksoftware/api-project',
                'provider' => 'azure-devops',
                'repositable_type' => Project::class,
                'repositable_id' => $projects->firstWhere('name', 'API Integration Project')?->id,
            ],
        ];

        foreach ($repositories as $repository) {
            if ($repository['repositable_id']) {
                Repository::create($repository);
            }
        }
    }
}
