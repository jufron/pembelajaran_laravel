<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'username'  => 'jufron',
            'password'  => Hash::Make('12345678'),
            'name'      => 'jufron',
            'token'     => '1234567890'
        ]);
    }
}
