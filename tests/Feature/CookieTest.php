<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CookieTest extends TestCase
{
    public function testCreateCookie (): void
    {
        $this->get('cookie/create')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertJson([
                'status'    => 200,
                'message'   => 'success login'
            ])
            ->assertCookie('login_with', 'google')
            ->assertCookie('rolle_user', 'admin');
    }

    public function testGetCookie (): void
    {
        $this->withCookie('login_with', 'google')
            ->get('cookie/get')
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertJsonStructure([
                'page',
                'status',
                'message',
                'login_with'
            ])
            ->assertJson([
                'page'          => 'dashboard',
                'status'        => 200,
                'message'       => 'success',
                'login_with'    => 'google'
            ]);

        $this->withCookie('login_with', 'facebook')
            ->get('cookie/get')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertJsonStructure([
                'page',
                'status',
                'message',
                'login_with'
            ])
            ->assertJson([
                'page'          => 'dashboard',
                'status'        => 200,
                'message'       => 'success',
                'login_with'    => 'facebook'
            ]);

        $this->withCookie('login_with', 'microsoft')
            ->get('cookie/get')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertJsonStructure([
                'page',
                'status',
                'message',
                'login_with'
            ])
            ->assertJson([
                'page'          => 'dashboard',
                'status'        => 200,
                'message'       => 'success',
                'login_with'    => 'microsoft'
            ]);
    }
}
