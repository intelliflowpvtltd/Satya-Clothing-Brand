<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Men', 'slug' => 'men', 'description' => 'Men fashion collection', 'is_active' => true],
            ['name' => 'Women', 'slug' => 'women', 'description' => 'Women fashion collection', 'is_active' => true],
            ['name' => 'Kids', 'slug' => 'kids', 'description' => 'Kids fashion collection', 'is_active' => true],
            ['name' => 'Accessories', 'slug' => 'accessories', 'description' => 'Fashion accessories', 'is_active' => true],
            ['name' => 'Footwear', 'slug' => 'footwear', 'description' => 'Shoes and footwear', 'is_active' => true],
            ['name' => 'Ethnic Wear', 'slug' => 'ethnic-wear', 'description' => 'Traditional ethnic clothing', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
