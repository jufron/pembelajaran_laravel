<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Services\Todolist\TodolistService;
use App\Services\Todolist\TodolistServiceImplementation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class TodolistServiceTest extends TestCase
{
    private TodolistService $todolistService;

    public function setUp (): void
    {
        parent::setUp();
        $this->todolistService = app()->make(TodolistService::class);

        if (Todo::query()->count() >= 1) {
            Todo::query()->delete();
        }
    }

    public function test_todolist_service_called (): void
    {
        $this->assertInstanceOf(TodolistService::class, $this->todolistService);
        $this->assertNotNull(TodolistService::class);
    }

    public function test_save_data_todolist (): void
    {
        $this->todolistService->saveTodo('todo 1');
        $this->todolistService->saveTodo('todo 2');

        $todo = $this->todolistService->getTodo();

        $this->assertEquals('todo 1', $todo[0]['todolist']);
        $this->assertEquals('todo 2', $todo[1]['todolist']);

        self::assertArrayHasKey('id', $todo[0]);
        self::assertArrayHasKey('todolist', $todo[1]);
    }

    public function test_get_data_todolist_empty (): void
    {
        $todo = $this->todolistService->getTodo();
        $this->assertIsArray($todo);
        $this->assertEquals([], $todo);
    }

    public function test_get_data_todolist (): void
    {
        $this->todolistService->saveTodo('todo 1');
        $todo = $this->todolistService->getTodo();

        foreach($todo as $to) {
            $this->assertEquals('todo 1', $to['todolist']);
        }
    }

    public function test_delete_data_todolist_where_id_todo (): void
    {
        $this->todolistService->saveTodo('todo 1');
        $this->todolistService->saveTodo('todo 2');
        $this->todolistService->saveTodo('todo 3');

        $todoId = Todo::query()->first()->id;
        $this->todolistService->deleteTodo($todoId);

        $todolist = $this->todolistService->getTodo();

        $this->assertEquals(2, count($todolist));

        $this->assertArrayHasKey(0, $todolist);
        $this->assertEquals('todo 2', $todolist[0]['todolist']);
        $this->assertEquals('todo 3', $todolist[1]['todolist']);
    }
}
