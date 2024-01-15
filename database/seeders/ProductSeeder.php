<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $all_products = collect([
            ['name'  => 'product 1'],
            ['name'  => 'product 2'],
            ['name'  => 'product 3']
        ]);

        Category::all()->each(function (Category $category, int $key) use ($all_products) {
            $category->products()->create([
                'name'  => $all_products[$key]['name'],
                'price' => rand(10000, 500000),
                'stock' => 999
            ]);
        });
    }
}
