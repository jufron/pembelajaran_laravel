<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function testLoginPageForController (): void
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertViewIs('auth.login')
            ->assertViewHas([
                'title' => 'Login'
            ])
            ->assertSeeText('Login');
    }

    public function testLoginSuccess (): void
    {
        $this->post('login', [
            'username'  => 'james',
            'password'  => 'rahasia'
        ])
        ->assertStatus(302)
        ->assertRedirect('/')
        ->assertRedirectToRoute('home');
    }

    public function testLoginFailedEmptyInput (): void
    {
        $this->post('login', [
            'username'  => '',
            'password'  => ''
        ])
        ->assertStatus(302)
        ->assertSeeText('user or password is required');
    }

    public function testLoginFailedWrongUsername (): void
    {
        $this->post('login', [
            'username'  => 'sinta',
            'password'  => 'rahasia'
        ])
        ->assertStatus(302)
        ->assertSee('user or password is wrong');
    }

    public function testLoginFailedWrongPassword (): void
    {
        $this->post('login', [
            'username'  => 'james',
            'password'  => 'salah'
        ])
        ->assertStatus(302)
        ->assertSee('user or password is wrong');
    }

    public function testAlredyLoginUser (): void
    {
        $this->withSession([
            'user'  => 'james'
        ])
        ->post('login', [])
        ->assertStatus(302)
        ->assertRedirect('/')
        ->assertRedirectToRoute('home');
    }

    public function testLogout (): void
    {
        $this->withSession([
            'user'  => 'james'
        ])
        ->post('logout')
        ->assertRedirect('/')
        ->assertRedirectToRoute('home');
    }
}
