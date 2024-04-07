<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user1 = User::query()->where('email', 'erik@gmail.com')->get()->first();
        $user2 = User::query()->where('email', 'dodi@gmail.com')->get()->first();

        Todo::create([
            'title'         => 'belajar laravel migration',
            'description'    => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Placeat, deleniti.',
            'user_id'       => $user1->id
        ]);

        Todo::create([
            'title'         => 'belajar laravel blade template',
            'description'    => 'Lorem ipsum dolor sit, amet consectetur adipisicing elit. Placeat, deleniti.',
            'user_id'       => $user1->id
        ]);

        Todo::create([
            'title'         => 'belajar laravel polices',
            'description'   => 'Lorem ipsum, dolor sit amet consectetur adipisicing elit. Magni, quas.',
            'user_id'       => $user2->id
        ]);
    }
}
