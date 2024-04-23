<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SimpleMiddlewareTest extends TestCase
{
    public function testMiddlwareFail (): void
    {
        $this->get('coba/middlware')
            ->assertStatus(401)
            ->assertUnauthorized()
            ->assertJson([
                'status'    => 401,
                'message'   => 'access dinaed'
            ])
            ->assertJsonStructure([
                'status',
                'message'
            ]);
    }

    public function testMiddlwareSuccess (): void
    {
        $this->withHeader('token', 'token-valid')
            ->get('coba/middlware')
            ->assertSuccessful()
            ->assertOk()
            ->assertStatus(200)
            ->assertSeeText('success');
    }
}
