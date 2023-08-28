<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SessionTest extends TestCase
{
    public function test_session_set (): void
    {
        $this->get('session/set')
             ->assertStatus(200)
             ->assertSeeText('session di set')
             ->assertSessionHas('user', 'james')
             ->assertSessionHas('login_with', 'google');
    }

    public function test_session_get (): void
    {
        $this->withSession([
            'user'          => 'james',
            'login_with'    => 'google'
        ])
        ->get('session/get')
        ->assertStatus(200)
        ->assertSeeText('data session user : james login with : google');
    }
}
