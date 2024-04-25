<?php


namespace App\Repositories;

use App\Contracts\TodolistService;
use Illuminate\Support\Facades\Session;

class TodoServiceImplementation implements TodolistService {

    public function saveTodo(string $todo): void
    {
        if (!Session::has('todolist')) {
            Session::put('todolist', []);
        }
        $id = uniqid();
        Session::push('todolist', [
            'id'    => $id,
            'todo'  => $todo
        ]);
    }

    public function getTodo(): array
    {
        return Session::get('todolist', []);
    }

    public function editTodolist(string $id): array | null
    {
        $todolist = collect(Session::get('todolist'));

        foreach($todolist as $todo) {
            if ($todo['id'] == $id) {
                return $todo;
            }  
        }
        return null;
    }

    public function updateTodolist ($dataTodo, $id)
    {
        $todolist = session()->get('todolist');

        foreach ($todolist as $index => $value) {
            if ($value['id'] === $id) {
                $todolist[$index]['todo'] = $dataTodo;
                break;
            }
        }
        session()->put('todolist', $todolist);
    }

    public function removeTodo(string $todoID)
    {
        $todolist = Session::get('todolist');
        foreach ($todolist as $index => $value) {
            if ($value['id'] === $todoID) {
                array_splice($todolist, $index, 1);
                break;
            }
        }
        Session::put('todolist', $todolist);
    }
}
