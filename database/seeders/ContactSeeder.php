<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::query()->where('email', 'erik@gmail.com')->get()->first();
        $user2 = User::query()->where('email', 'dodi@gmail.com')->get()->first();

        Contact::create([
            'name'      => 'sinta',
            'email'     => 'sinta@gmail.com',
            'phone'     => '081234567890',
            'address'   => 'oesapa',
            'user_id'   => $user1->id
        ]);
        Contact::create([
            'name'      => 'ida',
            'email'     => 'ida@gmail.com',
            'phone'     => '081234567890',
            'address'   => 'oesapa',
            'user_id'   => $user2->id
        ]);


    }
}
