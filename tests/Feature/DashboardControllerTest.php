<?php

namespace Tests\Feature;

use App\Services\Todolist\TodolistService;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    private TodolistService $todolistService;

    public function setUp (): void
    {
        parent::setUp();
        $this->todolistService = app()->make(TodolistService::class);
    }

    public function test_access_dashboard_page_success_with_session_auth (): void
    {
        $this->withSession([
            'auth'  => 'james'
        ])->get('dashboard')
          ->assertStatus(200)
          ->assertSeeText('dashboard');
    }

    public function test_access_dashboard_page_failid_without_session_auth (): void
    {
        $this->get('dashboard')
             ->assertRedirect('login')
             ->assertRedirectToRoute('login')
             ->assertStatus(302);
    }

    public function page_dashboard_with_data_empty ()
    {
        $this->withSession([
                'auth'      => 'james',
                'todolist'  => [
                    [
                        'id'    => '1',
                        'todo'  => 'todo 1'
                    ],
                    [
                        'id'    => '2',
                        'todo'  => 'todo 2'
                    ]
                ]
            ])
            ->get('dashboard')
            ->assertViewIs('dashboard')
            ->assertStatus(200)
            ->assertViewHas('title')
            ->assertViewHas('todolist')
            ->assertSeeText('dashboard');

        $this->assertEquals('james', Session::get('auth'));
        $this->assertEquals('james', Session::get('todolist'));
    }
}
