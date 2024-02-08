<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        if (User::query()->count() >= 1) {
            User::query()->delete();
        }
    }

    public function test_login_view(): void
    {
        $this->get('/login')
             ->assertSeeText('login')
             ->assertStatus(200)
             ->assertOk()
             ->assertSuccessful()
             ->assertViewHas('title')
             ->assertViewIs('auth.login');
    }

    public function test_login_action_success (): void
    {
        $this->seed(UserSeeder::class);
        $this->post('login', [
            'email'     => 'jufrontamoama@gmail.com',
            'password'  => '12345678'
        ])->assertRedirect('/dashboard')
          ->assertRedirectToRoute('dashboard')
          ->assertStatus(302)
          ->assertFound()
          ->assertSessionMissing('error');
    }

    public function test_login_validate_require (): void
    {
        $this->post('login')
             ->assertSessionHasErrors(['email', 'password']);
    }

    public function test_login_failed (): void
    {
        $this->post('login', [
            'email'  => 'jufrontamoama@gmail.com',
            'password'  => '12345678'
        ])
          ->assertRedirect('login')
          ->assertRedirectToRoute('login');
        //   ->assertSeeText('username atau password anda salah');
    }

    public function test_logout (): void
    {
        $this->withSession([
            'auth'  => 'jufrontamoama@gmail.com'
        ])->post('logout')
          ->assertStatus(302)
          ->assertRedirectToRoute('home');
    }

    public function test_middleware_auth_if_access_back_login_page (): void
    {
        $this->withSession([
            'auth'  => 'jufrontamoama@gmail.com'
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
             ->assertStatus(302)
             ->assertFound();
    }
}
