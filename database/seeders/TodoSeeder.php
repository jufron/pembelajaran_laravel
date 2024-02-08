<?php

namespace Database\Seeders;

use App\Models\Todo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Todo::create([
            'todolist'  => 'ini todo baru'
        ]);
        Todo::create([
            'todolist'  => 'ini todo baru 1'
        ]);
    }
}
