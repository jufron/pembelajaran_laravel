<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CookiesTest extends TestCase
{
    public function test_check_cookies (): void
    {
        $this->get('cookies/set')
             ->assertStatus(200)
             ->assertExactJson(['app_name' => 'laravel 10'])
             ->assertCookie('login-with', 'google')
             ->assertCookie('author', 'jufron');
    }

    public function test_get_data_cookies (): void
    {
        $this->withCookies([
            'login-with'    => 'google',
            'author'        => 'jufron'
        ])
        ->get('cookies/get')
        ->assertStatus(200)
        ->assertExactJson([
            'login-with'    => 'google',
            'author'        => 'jufron'
        ]);
    }

    public function test_remove_cookies (): void
    {
        $this->get('cookies/expire')
            ->assertStatus(200)
            ->assertCookieExpired('login-with')
            ->assertCookieExpired('author');
    }
}
