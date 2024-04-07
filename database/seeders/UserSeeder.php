<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'              => 'erik',
            'email'             => 'erik@gmail.com',
            'password'          => Hash::make('12345678'),
            'email_verified_at' => now(),
            'token'             => 'secret'
        ]);

        User::create([
            'name'              => 'dodi',
            'email'             => 'dodi@gmail.com',
            'password'          => Hash::make('12345678'),
            'email_verified_at' => now(),
            'token'             => 'secret'
        ]);

        User::create([
            'name'              => 'james',
            'email'             => 'james@gmail.com',
            'password'          => Hash::make('12345678'),
            'email_verified_at' => now(),
            'token'             => 'secret'
        ]);

        User::create([
            'name'              => 'super-admin',
            'email'             => 'super-admin@gmail.com',
            'password'          => Hash::make('12345678'),
            'email_verified_at' => now(),
            'token'             => 'secret'
        ]);
    }
}
