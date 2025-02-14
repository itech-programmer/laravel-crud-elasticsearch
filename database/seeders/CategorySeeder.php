<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'title' => 'Smartphones',
                'description' => 'Latest smartphone models',
                'image' => 'categories/smartphones.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Laptops',
                'description' => 'Modern and powerful laptops',
                'image' => 'categories/laptops.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'TVs',
                'description' => '4K and 8K resolution TVs',
                'image' => 'categories/tvs.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Headphones',
                'description' => 'Wireless and wired headphones',
                'image' => 'categories/headphones.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Gaming Consoles',
                'description' => 'Next-generation gaming consoles',
                'image' => 'categories/consoles.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('categories')->insert($categories);
    }
}