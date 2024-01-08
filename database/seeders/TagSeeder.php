<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Tag;
use App\Models\Vocher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tag::create([
            'id'    => '123',
            'name'  => 'hypertext preproccessor'
        ]);

        Tag::create([
            'id'    => '113',
            'name'  => 'javascript'
        ]);
    }
}
