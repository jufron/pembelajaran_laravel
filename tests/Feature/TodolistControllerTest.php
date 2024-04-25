<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodolistControllerTest extends TestCase
{
    public function testTodolistPageAndDataTodolist (): void
    {
        $data_todolist = [
            ['id'    => 1, 'todo'  => 'james'],
            ['id'    => 2, 'todo'  => 'sinta'],
            ['id'    => 3, 'todo'  => 'dodi']
        ];

        $this->withSession([
            'user'  => 'james',
            'todolist'  => $data_todolist
        ])
        ->get('todolist', [
            'title'     => 'Todolist',
            'todolist'  => $data_todolist
        ])
        ->assertSuccessful()
        ->assertOk()
        ->assertStatus(200)
        ->assertViewIs('todolist.todolist')
        ->assertSee('Todolist')
        ->assertSee('1')
        ->assertSee('2')
        ->assertSee('3')
        ->assertSee('james')
        ->assertSee('sinta')
        ->assertSee('dodi');
    }

    public function testTodolistPageAndDataEmpty (): void
    {
        $data_todolist = [];

        $this->withSession([
            'user'  => 'james',
            'todolist'  => $data_todolist
        ])
        ->get('todolist', [
            'title'     => 'Todolist',
            'todolist'  => $data_todolist
        ])
        ->assertSuccessful()
        ->assertOk()
        ->assertStatus(200)
        ->assertViewIs('todolist.todolist')
        ->assertSee('Todolist')
        ->assertSee('Data Empty');
    }

    public function testTodolistPageDataAccessWithoutLogin (): void
    {
        $data_todolist = [];

        $this->withSession([
            'todolist'  => $data_todolist
        ])
        ->get('todolist', [
            'title'     => 'Todolist',
            'todolist'  => $data_todolist
        ])
        ->assertRedirect('/')
        ->assertRedirectToRoute('home')
        ->assertStatus(302);
    }

    public function testPageTodolistCreate (): void
    {
        $this->withSession([
            'user'  => 'james'
        ])
        ->get('todolist/create', [
            'title' => 'Create'
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertViewHas([
            'title' => 'Create'
        ])
        ->assertViewIs('todolist.create-todolist')
        ->assertSeeText('Create');
    }

    public function testSaveDataTodolistSuccess (): void
    {
        $this->withSession([
            'user'  => 'james',
            'todolist'  => []
        ])
        ->post('todolist', [
            'todo'  => 'ini todo 1'
        ])
        ->assertStatus(302)
        ->assertRedirect('todolist')
        ->assertRedirectToRoute('todolist');
    }

    public function testSaveDataTodolistFailed (): void
    {
        $this->post('todolist', [
            'todo'  => 'ini data todo 1'
        ])
        ->assertRedirect('/')
        ->assertRedirectToRoute('home')
        ->assertStatus(302);
    }

    public function testPageTodolistEdit (): void
    {
        $todolist = [
            ['id'   => '1', 'todo'    => 'todo 1'],
            ['id'   => '2', 'todo'    => 'todo 2'],
            ['id'   => '3', 'todo'    => 'todo 3'],
            ['id'   => '4', 'todo'    => 'todo 4'],
        ];

        $this->withSession([
            'user'  => 'james',
            'todolist'  => $todolist
        ])
        ->get('todolist/edit/4', [
            'title'  => 'Edit'
        ])
        ->assertSuccessful()
        ->assertStatus(200)
        ->assertOk()
        ->assertSeeText('Edit');
    }

    public function testUpdate (): void
    {
        $todolist = [
            ['id'   => '1', 'todo'    => 'todo 1'],
            ['id'   => '2', 'todo'    => 'todo 2'],
            ['id'   => '3', 'todo'    => 'todo 3'],
            ['id'   => '4', 'todo'    => 'todo 4'],
        ];

        $this->withSession([
            'user'      => 'james',
            'todolist'  => $todolist
        ])
        ->patch('todolist/3', [
            'todo'      => 'todo 3 di update'
        ])
        ->assertRedirect('todolist')
        ->assertRedirectToRoute('todolist');

        $todolist = session()->get('todolist');

        $this->assertEquals('todo 3 di update', $todolist[2]['todo']);
        $this->assertNotNull($todolist);
        $this->assertIsArray($todolist);
    }

    public function testRemoveTodolistSuccess (): void
    {
        $todolist = [
            ['id'   => '1', 'todo'    => 'todo 1'],
            ['id'   => '2', 'todo'    => 'todo 2'],
            ['id'   => '3', 'todo'    => 'todo 3'],
            ['id'   => '4', 'todo'    => 'todo 4'],
        ];

        $this->withSession([
            'user'      => 'james',
            'todolist'  => $todolist
        ])
        ->delete('todolist/1')
        ->assertRedirect('todolist')
        ->assertRedirectToRoute('todolist');

        $this->assertCount(3, session()->get('todolist'));
        $this->assertEquals(3, count(session()->get('todolist')));
    }
}
