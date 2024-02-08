<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Tests\TestCase;
use Database\Seeders\UserSeeder;
use App\Services\Todolist\TodolistService;
use Database\Seeders\TodoSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TodolistControllerTest extends TestCase
{
    private TodolistService $todolistService;

    public function setUp (): void
    {
        parent::setUp();
        $this->todolistService = app()->make(TodolistService::class);

        if (User::query()->count() >= 1) {
            User::query()->delete();
        }

        if (Todo::query()->delete() >= 1) {
            Todo::query()->delete();
        }
    }

    private function LoginUser () : void
    {
        $this->seed(UserSeeder::class);
        $this->post('login', [
            'email'     => 'jufrontamoama@gmail.com',
            'password'  => '12345678'
        ])->assertRedirect('/dashboard')
          ->assertRedirectToRoute('dashboard')
          ->assertStatus(302)
          ->assertFound()
          ->assertSessionMissing('error');
    }

    public function test_access_add_todolist_page (): void
    {
        $this->withSession([
            'auth'  => 'jufrontamoama@gmail.com'
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
            'auth'  => 'jufrontamoama@gmail.com'
        ])
        ->post('todolist', [
            'todo'  => 'data todo baru yang dibuat'
        ])
        ->assertStatus(302)
        ->assertRedirect('dashboard')
        ->assertRedirectToRoute('dashboard');

        $todolist = $this->todolistService->getTodo();

        $this->assertEquals('1', count($todolist));

        $this->assertEquals('data todo baru yang dibuat', $todolist[0]['todolist']);
        $this->assertIsString($todolist[0]['todolist']);
        $this->assertIsArray($todolist);
    }

    public function test_create_new_data_todolist_failed (): void
    {
        $this->withSession([
            'auth'  => 'jufrontamoama@gmail.com'
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
        $this->seed(TodoSeeder::class);

        $todolistId = Todo::query()->first()->id;

        $this->withSession([
            'auth'  => 'jufrontamoama@gmail.com'
        ])
        ->delete("todolist/$todolistId")
        ->assertStatus(302)
        ->assertRedirect('dashboard')
        ->assertRedirectToRoute('dashboard');

        $todolist = $this->todolistService->getTodo();

        $this->assertEquals('1', count($todolist));
        $this->assertEquals('ini todo baru 1', $todolist[0]['todolist']);
        $this->assertIsString($todolist[0]['todolist']);
        $this->assertIsArray($todolist);
    }

    public function test_remove_data_todolist_failed (): void
    {
        $this->withSession([
            'auth'  => 'jufrontamoama@gmail.com'
        ])
        ->delete('todolist/3')
        ->assertStatus(404);
    }
}
