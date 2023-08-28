<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContohMiddlewareTest extends TestCase
{
    public function test_middleware_failed (): void
    {
        $this->get('middleware/test')
             ->assertStatus(302)
             ->assertRedirect('/');
    }

    public function test_middleware_success (): void
    {
        $this->withHeader('API_KEY', 'laravel')
             ->get('middleware/test')
             ->assertStatus(200)
             ->assertSeeText('halaman untuk middleware');
    }
}
