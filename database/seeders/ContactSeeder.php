<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Contact;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::query()->where('username', 'jufron')->first()) {
            User::create([
                'username'  => 'jufron',
                'password'  => Hash::Make('12345678'),
                'name'      => 'jufron',
                'token'     => '1234567890'
            ]);
        }

        $user = User::query()->where('username', 'jufron')->first();
        $user->contacts()->create([
            'firstName' => 'jufron',
            'lastName'  => 'tamo ama',
            'email'     => 'jufrontamoama@gmail.com',
            'phone'     => '082147554549'
        ]);
    }
}
