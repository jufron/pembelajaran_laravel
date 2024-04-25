<?php

namespace Tests\Feature;

use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewAllTest extends TestCase
{
    public function testViewLogin (): void
    {
        $this->view('auth.login', [
                'title' => 'Login'
            ])
            ->assertViewHas([
                'title'     => 'Login'
            ])
            ->assertSeeText('Login');

        $this->get('login')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertViewIs('auth.login')
            ->assertViewHas([
                'title' => 'Login'
            ])
            ->assertSeeText('Login');
    }

    public function testViewTodolist (): void
    {
        $this->view('todolist.todolist', [
            'title'     => 'Todolist',
            'todolist'  => []
        ])
        ->assertViewHas([
            'title'     => 'Todolist',
            'todolist'  => []
        ])
        ->assertSeeText('Todolist');

        $this->withSession([
            'user'  => 'james'
        ])
        ->get('todolist')
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertViewIs('todolist.todolist')
        ->assertViewHas([
            'title'     => 'Todolist',
            'todolist'  => []
        ])
        ->assertSeeText('Todolist');
    }

    public function testViewCreateTodo (): void
    {
        $this->view('todolist.create-todolist', [
            'title' => 'create'
        ])
        ->assertViewHas([
            'title' => 'create'
        ])
        ->assertSeeText('create');

        $this->withSession([
            'user'  => 'james'
        ])
        ->get('todolist/create')
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertViewIs('todolist.create-todolist')
        ->assertViewHas([
            'title' => 'Create'
        ])
        ->assertSeeText('Create');
    }

    public function testViewEditTodo (): void
    {
        $todolist = [
            ['id'   => '1', 'todo'    => 'todo 1'],
            ['id'   => '2', 'todo'    => 'todo 2'],
            ['id'   => '3', 'todo'    => 'todo 3'],
            ['id'   => '4', 'todo'    => 'todo 4'],
        ];

        session()->push('todolist', ['id'   => '1', 'todo'    => 'todo 1']);
        session()->push('todolist', ['id'   => '2', 'todo'    => 'todo 2']);
        session()->push('todolist', ['id'   => '3', 'todo'    => 'todo 3']);

        $this->view('todolist.edit-todolist', [
            'title'         => 'Edit',
            'todolist'      => $todolist[2]
        ])
        ->assertViewHas([
            'title'     => 'Edit',
            'todolist'  => $todolist[2]
        ])
        ->assertSeeText('Edit');

        $response = $this->withSession([
            'user'      => 'james',
            'session'   => $todolist
        ]);

        $response->get('todolist/edit/3')
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertViewIs('todolist.edit-todolist')
        ->assertViewHas([
            'title'     => 'Edit',
            'todolist'  => $todolist[2]
        ])
        ->assertSeeText('Edit');
    }
}
