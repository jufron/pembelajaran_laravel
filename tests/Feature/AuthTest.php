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

    public function test_current () : void
    {
        // test fail
        // $this->get('user/current')
        // ->assertSeeText('hello guest');

        // $user = User::factory()->create([
        //     'email_verified_at' => now()
        // ]);

        // $this->actingAs($user)->get('user/current')
        // ->assertSeeText('hello : ' . $user->email );

        $this->seed(UserSeeder::class);

        $this->get('user/current', [
            'accept'    => 'application/json'
        ])
        ->assertStatus(401)
        ->assertUnauthorized();

        $this->get('user/current', [
            'API_KEY'   => 'secret'
        ])
        ->assertOk()
        ->assertStatus(200)
        ->assertSee('hello : erik@gmail.com');
    }
}
