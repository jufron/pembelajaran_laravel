<?php

namespace App\Http\Controllers;

use App\Services\Todolist\TodolistService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class TodolistController extends Controller
{
    private TodolistService $todolistService;

    public function __construct(TodolistService $todolistService)
    {
        $this->todolistService = $todolistService;
    }

    public function todolist (): Response | View
    {
        return response()->view('todolist.add_todolist', [
            'title' => 'Tambah Todolist Baru'
        ]);
    }

    public function addTodolist (Request $request): Response | RedirectResponse
    {
        $request->validate([
            'todo'  => ['required']
        ]);

        $this->todolistService->saveTodo($request->input('todo'));
        return response()->redirectToRoute('dashboard');
    }

    public function removeTodolist (string $id): Response | RedirectResponse
    {
        $this->todolistService->deleteTodo($id);
        return response()->redirectToRoute('dashboard');
    }
}
