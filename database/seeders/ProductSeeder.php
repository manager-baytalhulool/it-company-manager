<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name'            => 'Laravel E‑commerce Platform',
                'description'     => 'A complete e‑commerce solution built with Laravel, including shopping cart, payment gateway integration, and admin dashboard.',
                'demo_url'        => 'https://demo.example.com/laravel-ecommerce',
                'download_url'    => 'https://downloads.example.com/laravel-ecommerce.zip',
                'reviews_count'   => 45,
                'average_rating'  => 4.7,
            ],
            [
                'name'            => 'Vue.js Project Management Tool',
                'description'     => 'A project management SPA with task boards, real‑time updates, and team collaboration features, built with Vue 3 and Laravel API.',
                'demo_url'        => 'https://demo.example.com/vue-pm',
                'download_url'    => 'https://downloads.example.com/vue-pm.zip',
                'reviews_count'   => 32,
                'average_rating'  => 4.5,
            ],
            [
                'name'            => 'React Native Mobile App Starter',
                'description'     => 'A production‑ready starter kit for React Native apps, including authentication, navigation, and theming out of the box.',
                'demo_url'        => null,
                'download_url'    => 'https://downloads.example.com/react-native-starter.zip',
                'reviews_count'   => 18,
                'average_rating'  => 4.8,
            ],
            [
                'name'            => 'WordPress SEO Plugin',
                'description'     => 'Boost your WordPress site’s SEO with this all‑in‑one plugin – includes meta tags, sitemaps, and schema markup.',
                'demo_url'        => 'https://demo.example.com/wp-seo',
                'download_url'    => 'https://downloads.example.com/wp-seo.zip',
                'reviews_count'   => 27,
                'average_rating'  => 4.3,
            ],
            [
                'name'            => 'Python Data Analysis Library',
                'description'     => 'A lightweight Python library for data manipulation and analysis, designed for simplicity and performance.',
                'demo_url'        => 'https://demo.example.com/pydata',
                'download_url'    => 'https://downloads.example.com/pydata.tar.gz',
                'reviews_count'   => 12,
                'average_rating'  => 4.9,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('Products seeded successfully.');
    }
}
