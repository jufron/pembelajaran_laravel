<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    public function testGuest (): void
    {
        $this->get('/')
            ->assertStatus(302)
            ->assertRedirectToRoute('todolist.login')
            ->assertRedirect('login');
    }

    public function testMember (): void
    {
        $this->withSession([
            'user'      => 'james'
        ])
        ->get('/')
        ->assertStatus(302)
        ->assertRedirectToRoute('todolist')
        ->assertRedirect('todolist');
    }
}
