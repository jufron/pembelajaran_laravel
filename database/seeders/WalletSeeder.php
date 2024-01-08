<?php

namespace Database\Seeders;

use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            ['costumer_id' => '001', 'amount' => 1000000],
            ['costumer_id' => '002', 'amount' => 1200000],
            ['costumer_id' => '003', 'amount' => 5000000],
            ['costumer_id' => '004', 'amount' => 2000000],
            ['costumer_id' => '005', 'amount' => 500000],
        ])->each( function ($item) {
            Wallet::create($item);
        });
    }
}
