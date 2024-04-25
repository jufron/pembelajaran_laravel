<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\TodolistService;
use Illuminate\Http\Response;

class TodolistController extends Controller
{
    private TodolistService $todolistService;

    public function __construct(TodolistService $todolistService)
    {
        $this->todolistService = $todolistService;
    }

    public function todolist (): Response
    {
        return response()->view('todolist.todolist', [
            'title'     => 'Todolist',
            'todolist'  => $this->todolistService->getTodo()
        ]);
    }

    public function todolistCreate (): Response
    {
        return response()->view('todolist.create-todolist', [
            'title' => 'Create'
        ]);
    }

    public function addTodo (Request $request)
    {
        $todo = $request->input('todo');

        if (empty($todo)) {
            return response()->view('todolist.create-todolist', [
                'title' => 'Create',
                'error' => 'todo is required'
            ]);
        }

        $this->todolistService->saveTodo($todo);
        return redirect()->route('todolist');
    }

    public function edit ($id): Response
    {
        $todolist = $this->todolistService->editTodolist($id);

        if ($todolist !== null) {
            return response()->view('todolist.edit-todolist', [
                'title'     => 'Edit',
                'todolist'  => $todolist
            ]);
        }
        return abort(404);
    }

    public function update (Request $request, $id)
    {
        $todo = $request->input('todo');
        if (empty($todo)) {
            return response()->view('todolist.edit-todolist', [
                'title'     => 'Edit',
                'error'     => 'todo is required'
            ]);
        }
        $this->todolistService->updateTodolist($request->input('todo'), $id);
        return redirect()->route('todolist');
    }

    public function removeTodo ($id)
    {
        $this->todolistService->removeTodo($id);
        return redirect()->route('todolist');
    }
}