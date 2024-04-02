<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        // if user seeder excsist
        if (User::query()->count() >= 1) {
            User::query()->delete();
        }
    }

    public function test_authentication () : void
    {
        $this->seed(UserSeeder::class);

        $response = Auth::attempt([
            'email'         => 'erik@gmail.com',
            'password'      => '12345678'
        ]);

        $this->assertTrue($response);
        $this->assertNotFalse($response);

        $userLogin = Auth::user();

        $this->assertNotNull($userLogin);
        $this->assertEquals('erik@gmail.com', $userLogin->email);
    }

    public function test_login () : void
    {
        // test fail
        $this->post('user/login', [])
            ->assertSeeText('wrong credencial');

        $this->seed(UserSeeder::class);

        // test success
        $this->post('user/login', [
            'email'     => 'erik@gmail.com',
            'password'  => '12345678'
        ])
        ->assertRedirect()
        ->assertRedirectToRoute('dashboard');
    }

    public function test_current_api_successfull () : void
    {
        $this->seed(UserSeeder::class);
        $this->get('api/user/current', [
            'API_KEY' => 'secret'
        ])
        ->assertOk()
        ->assertSuccessful()
        ->assertStatus(200)
        ->assertSee('hello : erik@gmail.com');
    }

    public function test_current_failed () : void
    {
        $this->seed(UserSeeder::class);
        $this->get('api/user/current', [
            'accept'    => 'applicatin/json',
            "API_KEY"   => 'token_fail'
        ])
        ->assertUnauthorized()
        ->assertStatus(401);
    }
}
