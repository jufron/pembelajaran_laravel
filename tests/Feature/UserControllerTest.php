<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    public function test_login_view(): void
    {
        $this->get('/login')
             ->assertSeeText('login')
             ->assertStatus(200);
    }

    public function test_login_action_success (): void
    {
        $this->post('login', [
            'username'  => 'james',
            'password'  => '12345678'
        ])->assertRedirect('/dashboard')
          ->assertRedirectToRoute('dashboard')
          ->assertStatus(302)
          ->assertSessionMissing('error');
    }

    public function test_login_validate_require (): void
    {
        $this->post('login')
             ->assertSessionHasErrors(['username', 'password']);
    }

    public function test_login_failed (): void
    {
        $this->post('login', [
            'username'  => 'sinta',
            'password'  => '87654321'
        ])
          ->assertRedirect('login')
          ->assertRedirectToRoute('login');
        //   ->assertSeeText('username atau password anda salah');
    }

    public function test_logout (): void
    {
        $this->withSession([
            'auth'  => 'james'
        ])->post('logout')
          ->assertStatus(302)
          ->assertRedirectToRoute('home');
    }

    public function test_middleware_auth_if_access_back_login_page (): void
    {
        $this->withSession([
            'auth'  => 'james'
        ])
          ->get('login')
          ->assertStatus(302)
          ->assertRedirect('dashboard')
          ->assertRedirectToRoute('dashboard');
    }

    public function test_middleware_guest_if_access_dashboard_without_login (): void
    {
        $this->get('dashboard')
             ->assertRedirect('login')
             ->assertRedirectToRoute('login')
             ->assertStatus(302);
    }
}
