<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Address;
use App\Models\Contact;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        User::query()->delete();
        Contact::query()->delete();
        Address::query()->delete();
    }

    private function sedDataSeed () : void
    {
        $this->seed(UserSeeder::class);
    }

    public function testUserRegisterSuccess(): void
    {
        $this->post('api/users', [
          'username'    => 'jufron',
          'password'    => '12345678',
          'name'        => 'jufron'
        ])
        ->assertStatus(201)
        ->assertSuccessful()
        ->assertJson([
            'data'  => [
                'username'  => 'jufron',
                'name'      => 'jufron'
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'username',
                'name'
            ]
        ]);
    }

    public function testUserRegisterFailed() : void
    {
        $this->post('api/users', [
          'username'    => '',
          'password'    => '',
          'name'        => ''
        ])
        ->assertStatus(422)
        ->assertJsonStructure([
            'errors' => [
                'username',
                'password',
                'name'
            ]
        ])
        ->assertJson([
            'errors' => [
                'username' => [
                    'The username field is required.'
                ],
                'password' => [
                    'The password field is required.'
                ],
                'name'     => [
                    'The name field is required.'
                ]
            ]
        ]);
    }

    public function testUserRegisterUsernameAlredyExcsist () : void
    {
        $this->testUserRegisterSuccess();
        $this->post('api/users', [
          'username'    => 'jufron',
          'password'    => '12345678',
          'name'        => 'jufron'
        ])
        ->assertStatus(422)
        ->assertJsonStructure([
            'errors' => [
                'username'
            ]
        ])
        ->assertJson([
            'errors' => [
                'username' => [
                    'The username has already been taken.'
                ]
            ]
        ]);
    }

    public function testLoginSuccess () :void
    {
        $this->sedDataSeed();

        $this->post('api/users/login', [
            'username'  => 'jufron',
            'password'  => '12345678'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'data' => [
                'username',
                'name',
                'token'
            ]
        ])
        ->assertJson([
            'data'  => [
                'username'  => 'jufron',
                'name'      => 'jufron'
            ]
        ]);

        $user = User::query()->where('username', 'jufron')->get()->first();
        $this->assertNotSame('default_token_unique', $user->password);
    }

    public function testLoginInvalidUsername () : void
    {
        $this->sedDataSeed();

        $this->post('api/users/login', [
            'username'  => 'sinta',
            'password'  => '12345678'
        ])
        ->assertStatus(401)
        ->assertJsonStructure([
            'errors' => [
                'message'
            ]
        ])
        ->assertJson([
          'errors' => [
                'message' => [
                    'The username or password wrong.'
                ]
            ]
        ]);
    }

    public function testLoginInvalidPassword () : void
    {
        $this->sedDataSeed();

        $this->post('api/users/login', [
            'username'  => 'james',
            'password'  => '1234567890'
        ])
        ->assertStatus(401)
        ->assertJsonStructure([
            'errors' => [
                'message'
            ]
        ])
        ->assertJson([
          'errors' => [
                'message' => [
                    'The username or password wrong.'
                ]
            ]
        ]);

    }

    public function testGetSuccess () : void
    {
        $this->sedDataSeed();

        $this->get('api/users/current', [
            'authorization' => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJson([
            'data' => [
                'username'  => 'jufron',
                'name'      => 'jufron'
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'username',
                'name'
            ]
        ]);
    }

    public function testGetUnAuthorization () : void
    {
        $this->sedDataSeed();

        $this->get('api/users/current')
        ->assertStatus(401)
        ->assertJson([
            'errors' => [
                'message' => [
                    'unauthorize'
                ]
            ]
        ])
        ->assertJsonStructure([
            'errors' => [
                'message'
            ]
        ]);
    }

    public function testGetInvalidToken () : void
    {
        $this->sedDataSeed();

        $this->get('api/users/current', [
            'authorization' => '0987654321'
        ])
        ->assertStatus(401)
        ->assertJson([
            'errors' => [
                'message' => [
                    'unauthorize'
                ]
            ]
        ])
        ->assertJsonStructure([
            'errors' => [
                'message'
            ]
        ]);
    }

    public function testUpdateName () : void
    {
        $this->sedDataSeed();

        $oldUser = User::query()->where('name', 'jufron')->first();
        $this->assertEquals('jufron', $oldUser->name);

        $this->patch('api/users/current', [
            'name'          => 'dodi'
        ], [
            'authorization' => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJson([
            'data' => [
                'username'  => 'jufron',
                'name'      => 'dodi'
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'username',
                'name'
            ]
        ]);

        $newUser = User::query()->where('name', 'dodi')->first();
        $this->assertEquals('dodi', $newUser->name);
        $this->assertNotSame($oldUser->name, $newUser->name);
    }

    public function testUpdatePasswrd () : void
    {
        $this->sedDataSeed();

        $userOld = User::query()->where('username', 'jufron')->first();
        $this->assertTrue(Hash::check(12345678, $userOld->password));

        $this->patch('api/users/current', [
            'password'          => '87654321'
        ], [
            'authorization' => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJson([
            'data' => [
                'username'  => 'jufron',
                'name'      => 'jufron'
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'username',
                'name'
            ]
        ]);

        $userNew = User::query()->where('username', 'jufron')->get()->first();
        $this->assertTrue(Hash::check('87654321', $userNew->password));
    }

    public function testUpdateInvalidValidation () : void
    {
        $this->sedDataSeed();
        // filed name to long min 50 but expect 100 length
        $this->patch('api/users/current', [
            'name'          => 'Lorem ipsum dolor, sit amet consectetur adipisicing elit. Adipisci minima voluptates perferendis unde molestiae expedita debitis asperiores similique, excepturi impedit quidem dolorem nihil sit, nulla, optio mollitia voluptatum suscipit architecto. Officiis dolorum recusandae natus eos tenetur et a, id, mollitia eveniet dicta obcaecati soluta deleniti explicabo vero, exercitationem maxime? Impedit sunt id, laborum recusandae, dicta doloribus suscipit ab, explicabo reprehenderit consequatur molestias quaerat! Ex ducimus voluptate, dignissimos commodi, earum repudiandae culpa maxime rem debitis repellendus quas omnis sequi ipsum maiores provident quaerat. Nisi molestias dolorem facere. Blanditiis mollitia quo temporibus illo veritatis quibusdam illum totam iure doloremque nemo. Placeat, earum.'
        ], [
            'authorization' => '1234567890'
        ])
        ->assertStatus(422)
        ->assertUnprocessable()
        ->assertJson([
            'errors' => [
                'name' => [
                    'The name field must not be greater than 50 characters.'
                ]
            ]
        ])
        ->assertJsonStructure([
            'errors' => [
                'name'
            ]
        ]);
    }

    public function testUpdateInvalidAuthorization () : void
    {
        $this->sedDataSeed();

        $this->patch('api/users/current', [
            'name'          => 'dodi'
        ], [
            'authorization' => '123456789'
        ])
        ->assertStatus(401)
        ->assertUnauthorized()
        ->assertJson([
            'errors' => [
                'message' => [
                    'unauthorize'
                ]
            ]
        ])
        ->assertJsonStructure([
            'errors' => [
                'message'
            ]
        ]);
    }

    public function testLogoutSuccess () : void
    {
        $this->sedDataSeed();
        $this->delete('api/users/logout',
        [],
        ['authorization' => '1234567890'])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJson([
            'data' => true
        ])
        ->assertJsonStructure([
            'data'
        ]);

        $user = User::query()->where('username', 'jufron')->first();
        $this->assertNull($user->token);
    }

    public function testLogoutUnauthorized () : void
    {
        $this->sedDataSeed();

        $this->delete('api/users/logout',
        [],
        ['authorization' => '123456789'])
        ->assertStatus(401)
        ->assertUnauthorized()
        ->assertJson([
            'errors' => [
                'message' => [
                    'unauthorize'
                ]
            ]
        ])
        ->assertJsonStructure([
            'errors' => [
                'message'
            ]
        ]);
    }
}
