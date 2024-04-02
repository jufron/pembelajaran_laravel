<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'name'          => 'food',
            'description'   => 'it.s food'
        ]);

        Category::create([
            'name'          => 'gedget',
            'description'   => 'it.s gedget'
        ]);

        Category::create([
            'name'          => 'fashin',
            'description'   => 'it.s fashin'
        ]);
    }
}
