<?php

namespace Database\Seeders;

use App\Models\VirtualAcount;
use App\Models\Wallet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VirtualAcountSeeder extends Seeder
{
    public function run(): void
    {
        Wallet::query()
            ->where('costumer_id', '001')
            ->get()
            ->first()
            ->virtualAcount()
            ->create([
                'bank'          => 'BCA',
                'va_number'     => '1025478642134',
            ]);

        Wallet::query()
            ->where('costumer_id', '002')
            ->get()
            ->first()
            ->virtualAcount()
            ->create([
                'bank'          => 'BNI',
                'va_number'     => '192573109384721',
            ]);
    }
}
