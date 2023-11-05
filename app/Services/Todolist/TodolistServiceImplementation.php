<?php

namespace App\Services\Todolist;

use Illuminate\Support\Facades\Session;

class TodolistServiceImplementation implements TodolistService {

    public function saveTodo(string $id, string $todo): void
    {
        if (!Session::exists('todolist')) {
            Session::put('todolist', []);
        }

        Session::push('todolist', [
            'id'    => $id,
            'todo'  => $todo
        ]);
    }

    public function getTodo(): array
    {
        return Session::get('todolist', []);
    }

    public function deleteTodo(string $id)
    {
        $todolist = Session::get('todolist', []);

        $state = false;

        foreach($todolist as $key => $todo) {
            if ($todo['id'] === $id) {
                unset($todolist[$key]);
                $state = true;
                break;
            }
        }

        if (!$state) {
            abort(404);
        }

        Session::put('todolist', array_values($todolist));
    }
}
