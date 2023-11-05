<?php

namespace Tests\Feature;

use App\Services\Todolist\TodolistService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodolistControllerTest extends TestCase
{
    private TodolistService $todolistService;

    public function setUp (): void
    {
        parent::setUp();
        $this->todolistService = app()->make(TodolistService::class);
    }

    public function test_access_add_todolist_page (): void
    {
        $this->withSession([
            'auth'  => 'james'
        ])
        ->get('todolist')
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertViewIs('todolist.add_todolist')
        ->assertViewHas('title')
        ->assertSeeText('Tambah Todolist Baru');
    }

    public function test_create_new_data_todolist_success (): void
    {
        $this->withSession([
            'auth'  => 'james'
        ])
        ->post('todolist', [
            'todo'  => 'data todo baru'
        ])
        ->assertStatus(302)
        ->assertRedirect('dashboard')
        ->assertRedirectToRoute('dashboard');

        $todolist = $this->todolistService->getTodo();

        $this->assertEquals('1', count($todolist));

        $this->assertEquals('data todo baru', $todolist[0]['todo']);
        $this->assertIsString($todolist[0]['todo']);
        $this->assertIsArray($todolist);
    }

    public function test_create_new_data_todolist_failed (): void
    {
        $this->withSession([
            'auth'  => 'james'
        ])
        ->post('todolist', [
            'todo'  => ''
        ])
        ->assertSessionHas('errors')
        ->assertSessionHasErrors('todo')
        ->assertStatus(302);
    }

    public function test_remove_data_todolist_success (): void
    {
        $this->withSession([
            'auth'  => 'james',
            'todolist'  => [
                [
                    'id'        => '1',
                    'todo'      => 'data todo baru'
                ],
                [
                    'id'        => '2',
                    'todo'      => 'data todo baru'
                ]
            ]
        ])
        ->delete('todolist/2')
        ->assertStatus(302)
        ->assertRedirect('dashboard')
        ->assertRedirectToRoute('dashboard');

        $todolist = $this->todolistService->getTodo();
        var_dump($todolist);

        $this->assertEquals('1', count($todolist));
        $this->assertEquals('data todo baru', $todolist[0]['todo']);
        $this->assertIsString($todolist[0]['todo']);
        $this->assertIsArray($todolist);
    }

    public function test_remove_data_todolist_failed (): void
    {
        $this->withSession([
            'auth'  => 'james',
            'todolist'  => [
                [
                    'id'        => '1',
                    'todo'      => 'data todo baru'
                ],
                [
                    'id'        => '2',
                    'todo'      => 'data todo baru'
                ]
            ]
        ])
        ->delete('todolist/3')
        ->assertStatus(404);
    }
}
