<?php

namespace Tests\Feature;

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
    }

    public function test_todolist_service_called (): void
    {
        $this->assertInstanceOf(TodolistService::class, $this->todolistService);
        $this->assertNotNull(TodolistService::class);
    }

    public function test_save_data_todolist (): void
    {
        $this->todolistService->saveTodo('1', 'todo 1');
        $this->todolistService->saveTodo('2', 'todo 2');

        $todolist = Session::get('todolist');

        $this->assertEquals('1', $todolist[0]['id']);
        $this->assertEquals('todo 1', $todolist[0]['todo']);
        $this->assertEquals('2', $todolist[1]['id']);
        $this->assertEquals('todo 2', $todolist[1]['todo']);

        self::assertArrayHasKey('id', $todolist[0]);
        self::assertArrayHasKey('todo', $todolist[1]);

        // foreach ($todolist as $to) {
        //     self::assertEquals('2', $to['id']);
        //     self::assertEquals('todo 2', $to['todo']);
        // }
    }

    public function test_get_data_todolist_empty (): void
    {
        $todo = $this->todolistService->getTodo();
        $this->assertIsArray($todo);
        $this->assertEquals([], $todo);
    }

    public function test_get_data_todolist (): void
    {
        $this->todolistService->saveTodo('1', 'todo 1');
        $todo = $this->todolistService->getTodo();

        foreach($todo as $to) {
            $this->assertEquals('1', $to['id']);
            $this->assertEquals('todo 1', $to['todo']);
        }
    }

    public function test_delete_data_todolist_where_id_todo (): void
    {
        $this->todolistService->saveTodo('1', 'todo 1');
        $this->todolistService->saveTodo('2', 'todo 2');
        $this->todolistService->saveTodo('3', 'todo 3');

        $this->todolistService->deleteTodo('1');

        $todolist = $this->todolistService->getTodo();

        $this->assertEquals(2, count($todolist));

        $this->assertArrayHasKey(0, $todolist);
        $this->assertEquals('2', $todolist[0]['id']);
        $this->assertEquals('todo 2', $todolist[0]['todo']);
        $this->assertEquals('3', $todolist[1]['id']);
        $this->assertEquals('todo 3', $todolist[1]['todo']);

        $this->todolistService->deleteTodo('2');

        $todolist_current = $this->todolistService->getTodo();

        $this->assertEquals(1, count($todolist_current));
    }
}
