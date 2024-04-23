<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SessionTest extends TestCase
{
    public function testSessionCreate (): void
    {
        $this->get('session/create')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertJson([
                'status'    => 200,
                'message'   => 'success'
            ])
            ->assertJsonStructure([
                'status',
                'message'
            ])
            ->assertSessionHas('user_id', 'jufron')
            ->assertSessionHas('is_admin', true);
    }

    public function testSessionGet (): void
    {
        $this->withSession([
            'user'      => 'jufron',
            'is_admin'  => true
        ])
        ->get('session/get')
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJson([
            'status'    => 200,
            'message'   => 'success',
            'user'      => 'jufron',
            'is_admin'  => true
        ])
        ->assertJsonStructure([
            'status',
            'message',
            'user',
            'is_admin'
        ])
        ->assertSessionHas('user', 'jufron')
        ->assertSessionHas('is_admin', true);
    }
}
