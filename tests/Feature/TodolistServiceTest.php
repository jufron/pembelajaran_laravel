<?php

namespace Tests\Feature;

use App\Contracts\TodolistService;
use GuzzleHttp\Promise\Each;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class TodolistServiceTest extends TestCase
{
    private TodolistService $todolistService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->todolistService = app()->make(TodolistService::class);
    }

    public function testTodolistServiceNotNull (): void
    {
        $this->assertNotNull($this->todolistService);
    }

    public function testSaveTodo (): void
    {
        $this->todolistService->saveTodo('ini todo 1');

        $all_todo = $this->todolistService->getTodo();

        $this->assertIsArray($all_todo);
        $this->assertNotNull($all_todo);

        collect($all_todo)->each( function ($item) {
            $this->assertEquals('ini todo 1', $item['todo']);
        });
    }

    public function testGetTodolistEmpty (): void
    {
        $this->assertEquals([], $this->todolistService->getTodo());
    }

    public function testEditSuccessGetData (): void
    {
        $this->todolistService->saveTodo('todo 1');
        $this->todolistService->saveTodo('todo 2');
        $this->todolistService->saveTodo('todo 3');

        $todolist = $this->todolistService->getTodo();

        $oneData = $this->todolistService->editTodolist($todolist[2]['id']);

        $this->assertIsArray($oneData);
        $this->assertEquals('todo 3', $oneData['todo']);
    }

    public function testEditFailedGetData (): void
    {
        $this->todolistService->saveTodo('todo 1');
        $this->todolistService->saveTodo('todo 2');
        $this->todolistService->saveTodo('todo 3');

        $oneData = $this->todolistService->editTodolist('1234');

        $this->assertEquals(null, $oneData);
        $this->assertNull($oneData);
        $this->assertEmpty($oneData);
    }

    public function testUpdateSuccess (): void
    {
        $this->todolistService->saveTodo('todo 1');
        $this->todolistService->saveTodo('todo 2');

        $todo = collect($this->todolistService->getTodo())->first();
        $this->todolistService->updateTodolist('todo update', $todo['id']);

        $todolist = $this->todolistService->getTodo();

        $this->assertCount(2, $todolist);
        $this->assertEquals('todo update', $todolist[0]['todo']);
        $this->assertSame('todo update', $todolist[0]['todo']);
    }

    public function testUpdateFail (): void
    {
        $this->todolistService->saveTodo('todo 1');
        $this->todolistService->saveTodo('todo 2');

        $this->todolistService->updateTodolist('todo update', '1234');
        $todolist = $this->todolistService->getTodo();

        $this->assertCount(2, $todolist);
        $this->assertNotEquals('todo update', $todolist[0]['todo']);
        $this->assertNotSame('todo update', $todolist[0]['todo']);
    }

    public function testSetTodolistNotEmpty (): void
    {
        $this->todolistService->saveTodo('james');
        $this->todolistService->saveTodo('sinta');

        $todolist = $this->todolistService->getTodo();

        $this->assertEquals('james', $todolist[0]['todo']);
        $this->assertEquals('sinta', $todolist[1]['todo']);
    }

    public function testRemoveTodo (): void
    {
        Session::push('todolist', ['id'   => '1', 'todo'    => 'todo 1']);
        Session::push('todolist', ['id'   => '2', 'todo'    => 'todo 2']);
        Session::push('todolist', ['id'   => '3', 'todo'    => 'todo 3']);
        Session::push('todolist', ['id'   => '4', 'todo'    => 'todo 4']);

        $this->assertCount(4, $this->todolistService->getTodo());
        $this->todolistService->removeTodo('1');

        $this->assertCount(3, $this->todolistService->getTodo());
        $this->todolistService->removeTodo('2');

        $this->assertCount(2, $this->todolistService->getTodo());
        $this->todolistService->removeTodo('3');

        $this->assertCount(1, $this->todolistService->getTodo());
    }
}
