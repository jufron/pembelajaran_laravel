<?php

namespace App\Http\Controllers;

use App\Services\Todolist\TodolistService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    private TodolistService $todolistService;

    public function __construct(TodolistService $todolistService)
    {
        $this->todolistService = $todolistService;
    }

    public function dashboard (): Response | View
    {
        return response()->view('dashboard', [
            'title'     => 'dashboard',
            'todolist'  => $this->todolistService->getTodo()
        ]);
    }
}
