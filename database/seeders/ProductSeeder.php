<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'title' => 'iPhone 15 Pro',
                'price' => 99990,
                'image' => 'products/iphone15pro.jpg',
                'description' => 'Flagship Apple smartphone with A17 Bionic processor',
                'category_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'MacBook Pro 16',
                'price' => 249990,
                'image' => 'products/macbookpro16.jpg',
                'description' => 'Powerful laptop for professionals with M3 Max chip',
                'category_id' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Samsung Neo QLED 8K',
                'price' => 499990,
                'image' => 'products/samsung_qled.jpg',
                'description' => '8K TV with incredible detail and Quantum HDR',
                'category_id' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'Sony WH-1000XM5',
                'price' => 29990,
                'image' => 'products/sony_wh1000xm5.jpg',
                'description' => 'Premium wireless headphones with noise cancellation',
                'category_id' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'title' => 'PlayStation 5',
                'price' => 59990,
                'image' => 'products/ps5.jpg',
                'description' => 'Next-generation console from Sony with 4K and 120 FPS support',
                'category_id' => 5,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        DB::table('products')->insert($products);
    }
}