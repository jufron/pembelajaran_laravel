<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->where('username', 'jufron')->first();
        $contact = $user->contacts()->first();

        for ($i=1; $i <= 5; $i++) {
            $contact->addresses()->create([
                'street'        => 'jl tasek '. $i,
                'rt'            => '01'. $i,
                'rw'            => '00'. $i,
                'city'          => 'kota kupang '. $i,
                'province'      => 'nusa tenggara timur' . $i,
                'country'       => 'indonesia' . $i,
                'postal_code'   => '851' . $i
            ]);
        }

    }
}
