<?php

namespace App\Services\Todolist;

use App\Models\Todo;
use Illuminate\Support\Facades\Session;

class TodolistServiceImplementation implements TodolistService {

    public function saveTodo(string $todo): void
    {
        Todo::create([
            'todolist'  => $todo
        ]);
    }

    public function getTodo(): array
    {
        return Todo::query()->get()->toArray();
    }

    public function deleteTodo(string $id)
    {
        Todo::query()->findOrFail($id)->delete();
    }
}
