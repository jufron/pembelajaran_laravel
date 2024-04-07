<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Todo;
use App\Models\User;
use Database\Seeders\ContactSeeder;
use Database\Seeders\TodoSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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

    public function test_user_provider () : void
    {
        $this->seed(UserSeeder::class);

        $this->get('simple-api/user/current', [
            'accept'    => 'application/json'
        ])
        ->assertUnauthorized()
        ->assertStatus(401);

        $this->get('simple-api/user/current', [
            'API_KEY'   => 'secret'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertSee('hello james');
    }

    public function test_user_gate () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class
        ]);

        $user = User::query()
                    ->where('email', 'erik@gmail.com')
                    ->get()
                    ->first();

        Auth::login($user);

        $contact = Contact::query()
                          ->where('user_id', $user->id)
                          ->get()
                          ->first();

        $this->assertTrue(Gate::allows('get-contact', $contact));
        $this->assertTrue(Gate::allows('create-contact', $contact));
        $this->assertTrue(Gate::allows('update-contact', $contact));
        $this->assertTrue(Gate::allows('delete-contact', $contact));
    }

    public function test_gate_method ()
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class
        ]);

        $user1 = User::query()->where('email', 'erik@gmail.com')
                              ->get()
                              ->first();

        // syarat menggunakan gate harus user sudah login jika tidak maka semuanya dianggap false
        Auth::login($user1);

        $contact1 = Contact::query()
                          ->where('user_id', $user1->id)
                          ->get()
                          ->first();

        // user1 and contact 1
        $this->assertTrue(Gate::allows(['create-contact', 'update-contact'], $contact1));
        $this->assertFalse(Gate::none(['create-contact', 'delete-contact'], $contact1));
        $this->assertTrue(Gate::any(['create-contact', 'get-contact', 'delete-contact'], $contact1));
        $this->assertTrue(Gate::denies(['create-contact', 'get-contact', 'delete-contact'], $contact1));

     }

    public function test_gate_user () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class
        ]);

        $user1 = User::query()->where('email', 'erik@gmail.com')
                              ->get()
                              ->first();

        $gate = Gate::forUser($user1);

        $contact1 = Contact::query()
                    ->where('user_id', $user1->id)
                    ->get()
                    ->first();

        $this->assertTrue($gate->allows(['create-contact', 'get-contact'], $contact1));
        $this->assertTrue($gate->denies(['update-contact', 'delete-contact'], $contact1));
    }

    public function test_gate_response () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class
        ]);

        // berhak menghapus data
        $user1 = User::query()->where('email', 'james@gmail.com')
                        ->get()
                        ->first();

        // tidak berhak menghapus data
        $user2 = User::query()->where('email', 'dodi@gmail.com')
                    ->get()
                    ->first();

        Auth::login($user1);

        $response1 = Gate::inspect('delete-contact');
        $this->assertTrue($response1->allowed());

        Auth::logout();

        Auth::login($user2);

        $response2 = Gate::inspect('delete-contact');
        $this->assertFalse($response2->allowed());
    }

    public function test_policy () : void
    {
        $this->seed([
            UserSeeder::class,
            TodoSeeder::class
        ]);

        $user1 = User::query()->where('email', 'erik@gmail.com')->get()->first();
        $user2 = User::query()->where('email', 'dodi@gmail.com')->get()->first();

        Auth::login($user1);

        $todo1 = Todo::query()->where('user_id', $user1->id)->get()->first();
        $todo2 = Todo::query()->where('user_id', $user2->id)->get()->first();

        $this->assertTrue(Gate::allows('view', $todo1));
        // $this->assertTrue(Gate::allows('viewAny', Todo::class));
        // $this->assertTrue(Gate::allows('create', Todo::class));
        $this->assertTrue(Gate::allows('delete', $todo1));
        $this->assertTrue(Gate::allows('update', $todo1));

        $this->assertFalse(Gate::allows('view', $todo2));
        // $this->assertFalse(Gate::allows('create', Todo::class));
        $this->assertFalse(Gate::allows('update', $todo2));
        $this->assertFalse(Gate::allows('delete', $todo2));

        var_dump(Gate::allows('create', $todo2));
    }
}
