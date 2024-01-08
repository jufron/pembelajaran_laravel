<?php

namespace Database\Seeders;

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
        collect([
            [
                'id'            => '001',
                'name'          => 'samsung galaxy s21 ulta',
                'description'   => 'ini adalah deskripsi samsung galaxy s21 ultra',
                'price'         => 15000000,
                'stock'         => 999,
                'category_id'   => '001'
            ],
            [
                'id'            => '002',
                'name'          => 'apple iphone 14 pro',
                'description'   => 'ini adalah deskripsi apple iphone 14 pro',
                'price'         => 17000000,
                'stock'         => 999,
                'category_id'   => '001'
            ],
            [
                'id'            => '003',
                'name'          => 'bakso',
                'description'   => 'ini adalah deskripsi bakso',
                'price'         => 15000,
                'stock'         => 999,
                'category_id'   => '003'
            ],
            [
                'id'            => '004',
                'name'          => 'xiaomi tv 50 inch',
                'description'   => 'ini adalah deskripsi xiaomi tv 50 inch',
                'price'         => 4500000,
                'stock'         => 999,
                'category_id'   => '002'
            ]
        ])->each( function ($item) {
            Product::create($item);
        });
    }
}
