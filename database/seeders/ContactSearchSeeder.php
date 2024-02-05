<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactSearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'username'  => 'jufron',
            'password'  => Hash::Make('12345678'),
            'name'      => 'jufron',
            'token'     => '1234567890'
        ]);

        for ($i=1; $i <= 20 ; $i++) {
            $user->contacts()->create([
                'firstName' => 'jufron'.$i,
                'lastName'  => 'tamo ama' .$i,
                'email'     => "jufrontamoama$i@gmail.com",
                'phone'     => '0821475545'.$i
            ]);
        }
    }
}
