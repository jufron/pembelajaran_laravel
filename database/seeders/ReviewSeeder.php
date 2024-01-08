<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Review::create([
            'product_id'    => '001',
            'costumer_id'   => '001',
            'rating'        => 5,
            'comment'       => 'sangant bagus sekali'
        ]);

        Review::create([
            'product_id'    => '002',
            'costumer_id'   => '002',
            'rating'        => 5,
            'comment'       => 'sangat bagus pengiriman cepat'
        ]);
    }
}
