<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            ['id' => '100', 'name' => 'samsung galaxy s23 ultra', 'description' => 'ini adalah samsung galaxy s23 ultra', 'price' => 18000000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '200', 'name' => 'apple iphone 14 pro', 'description' => 'ini adalah smartphone apple iphone 14 pro', 'price' => 22000000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '300', 'name' => 'xiaomi 12', 'description' => 'ini adalah smartphone xiaomi 12', 'price' => 9000000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '400', 'name' => 'redmi note 12 pro', 'description' => 'ini adalah smartphone redmi note 12 pro', 'price' => 4100000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '500', 'name' => 'poco f5', 'description' => 'ini adalah smartphone poco f5', 'price' => 5500000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '600', 'name' => 'xiaomi 13T ', 'description' => 'ini adalah smartphone xiaomi 13T', 'price' => 7000000, 'category_id' => '100', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '700', 'name' => 'TV samsung 45 inch', 'description' => 'ini adalah televisi samsung', 'price' => 7000000, 'category_id' => '300', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '800', 'name' => 'TV xiaomi 65 inch', 'description' => 'ini adalah televisi xiaomi', 'price' => 9000000, 'category_id' => '300', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '900', 'name' => 'TV polytron android 45 inch', 'description' => 'ini adalah televisi polytron andorid tv', 'price' => 5000000, 'category_id' => '300', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '1000', 'name' => 'bakso', 'description' => 'ini adalah makanan bakso', 'price' => 10000, 'category_id' => '500', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '1100', 'name' => 'mie ayam', 'description' => 'ini adalah makanan mie ayam', 'price' => 15000, 'category_id' => '500', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '1200', 'name' => 'soto', 'description' => 'ini adalah makanan soto', 'price' => 15000, 'category_id' => '500', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '1300', 'name' => 'sate', 'description' => 'ini adalah makanan sate', 'price' => 20000, 'category_id' => '500', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '1400', 'name' => 'nasi goreng', 'description' => 'ini adalah makanan nasi goreng', 'price' => 15000, 'category_id' => '500', 'created_at' => '2023-11-24 15:30:45'],
        ]);
    }
}
