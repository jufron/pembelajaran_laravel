<?php

namespace Database\Seeders;

use App\Models\Vocher;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VocherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $vocher = new Vocher();
        // $vocher->name           = 'example vocher 1';
        // $vocher->vocher_code    = '1978543289123463';
        // $vocher->save();

        collect([
            ['name' => 'example vocher 1'],
            ['name' => 'example vocher 2'],
            ['name' => 'example vocher 3']
        ])->each(function ($item) {
            Vocher::create($item);
        });

    }
}
