<?php

namespace Database\Seeders;

use App\Models\Costumer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CostumerSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            [
                'id'        => '001',
                'name'      => 'james',
                'email'     => 'james@gmail.com'
            ],
            [
                'id'        => '002',
                'name'      => 'sinta',
                'email'     => 'sinta@gmail.com'
            ],
            [
                'id'        => '003',
                'name'      => 'erik',
                'email'     => 'erik@gmail.com'
            ],
            [
                'id'        => '004',
                'name'      => 'putri',
                'email'     => 'putri@gmail.com'
            ],
            [
                'id'        => '005',
                'name'      => 'andi',
                'email'     => 'andi@gmail.com'
            ]
        ])->each( function ($item) {
            Costumer::create($item);
        });
    }
}
