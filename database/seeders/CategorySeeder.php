<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('category')->insert([
            ['id' => '100', 'name' => 'smartphone', 'description' => 'ini adalah deskripsi smartphone', 'created_at' => '2023-11-24 15:30:45'],
            ['id' => '200', 'name' => 'laptop', 'description' => 'ini adalah deskripsi laptop',  'created_at' => '2023-11-25 15:30:45'],
            ['id' => '300', 'name' => 'elektronik', 'description' => 'ini adalah deskripsi elektronik',  'created_at' => '2023-11-26 15:30:45'],
            ['id' => '400', 'name' => 'perabotan rumah tangga', 'description' => 'ini adalah deskripsi dari perabotan rumah tangga',  'created_at' => '2023-11-27 15:30:45'],
            ['id' => '500', 'name' => 'makanan', 'description' => 'ini adalah deskripsi makanan',  'created_at' => '2023-11-28 15:30:45']
        ]);
    }
}
