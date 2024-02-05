<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Contact;
use Database\Seeders\ContactSearchSeeder;
use Database\Seeders\ContactSeeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;

class ContactTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Contact::query()->delete();
        User::query()->delete();
    }

    private function createDataUser () : void
    {
        User::create([
            'username'  => 'jufron',
            'password'  => Hash::Make('12345678'),
            'name'      => 'jufron',
            'token'     => '1234567890'
        ]);
    }

    public function testCreateDataContactSuccess () : void
    {
        $this->createDataUser();

        $this->post(uri:'api/contacts',
        data: [
            'firstName' => 'jufron',
            'lastname'  => 'tamo ama',
            'email'     => 'jufrontamoama@gmail.com',
            'phone'     => '082147554549'
        ],
        headers: [
            'authorization' => '1234567890'
        ])
        ->assertStatus(201)
        ->assertCreated()
        ->assertJson([
            'data'  => [
                'firstName' => 'jufron',
                'lastName'  => 'tamo ama',
                'email'     => 'jufrontamoama@gmail.com',
                'phone'     => '082147554549'
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'firstName',
                'lastName',
                'email',
                'phone'
            ]
        ]);

        $data = auth()->user()->contacts;
        $this->assertNotNull($data);
    }

    public function testCreateDataContactFailedValidation () : void
    {
        $this->createDataUser();

        $this->post(uri:'api/contacts',
        data: [
            'firstName' => '',
            'lastName'  => 'tamo ama',
            'email'     => 'jufrontamoama',
            'phone'     => '082147554549'
        ],
        headers: [
            'authorization' => '1234567890'
        ])
        ->assertStatus(422)
        ->assertUnprocessable()
        ->assertJson([
            'errors'  => [
                'firstName' => [
                    'The first name field is required.'
                ],
                'email'     => [
                    'The email field must be a valid email address.'
                ]
            ]
        ])
        ->assertJsonStructure([
            'errors' => [
                'firstName',
                'email'
            ]
        ]);
    }

    public function testCreateDataContactFailedUnauthorize () : void
    {
        $this->createDataUser();

        $this->post(uri:'api/contacts',
        data: [
            'firstName' => 'jufron',
            'lastName'  => 'tamo ama',
            'email'     => 'jufrontamoama@gmail.com',
            'phone'     => '082147554549'
        ],
        headers: [
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

    public function testGetDataWhereIdSuccess () : void
    {
        $this->seed(ContactSeeder::class);

        $contactID = User::query()->where('username', 'jufron')->first()->contacts()->first()->id;

        $this->get("api/contacts/$contactID", [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJson([
            'data'  => [
                'id'        => $contactID,
                'firstName' => 'jufron',
                'lastName'  => 'tamo ama',
                'email'     => 'jufrontamoama@gmail.com',
                'phone'     => '082147554549'
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'firstName',
                'lastName',
                'email',
                'phone'
            ]
        ]);
    }

    public function testGetDataWhereIdNotFound () : void
    {
        $this->seed(ContactSeeder::class);

        $this->get('api/contacts/1', [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(404)
        ->assertNotFound()
        ->assertJson([
            'errors'    => [
                'message'   => [
                    'resource not found'
                ]
            ]
        ])
        ->assertJsonStructure([
            'errors'    => [
                'message'
            ]
        ]);
    }

    public function testGetDataWhereIdInvalidAuthorization () : void
    {
        $this->seed(ContactSeeder::class);

        $contactID = User::query()->where('username', 'jufron')->first()->contacts()->first()->id;

        $this->get("api/contacts/$contactID", [
            'authorization'     => '123456789'
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

    public function testUpdateDataSuccess () : void
    {
        $this->seed(ContactSeeder::class);

        $contactID = User::query()->where('username', 'jufron')->first()->contacts()->first()->id;

        $this->put(uri: "api/contacts/$contactID", data: [
            'firstName'     => 'jufron',
            'lastname'      => 'di update',
            'email'         => 'jufrontamoama@gmail.com',
            'phone'         => '082147554549'
        ], headers: [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJson([
            'data'  => [
                'id'        => $contactID,
                'firstName' => 'jufron',
                'lastName'  => 'di update',
                'email'     => 'jufrontamoama@gmail.com',
                'phone'     => '082147554549'
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'id',
                'firstName',
                'lastName',
                'email',
                'phone'
            ]
        ]);
    }

    public function testUpdateDataNotFound () : void
    {
        $this->seed(ContactSeeder::class);

        $this->put(uri:'api/contacts/1', headers: [
            'authorization'     => '1234567890'
        ], data: [
            'firstName'     => 'jufron',
            'lastname'      => 'di update',
            'email'         => '',
            'phone'         => ''
        ])
        ->assertStatus(404)
        ->assertNotFound()
        ->assertJson([
            'errors'    => [
                'message'   => [
                    'resource not found'
                ]
            ]
        ])
        ->assertJsonStructure([
            'errors'    => [
                'message',
            ]
        ]);
    }

    public function testUpdateDataInvalidValidation () : void
    {
        $this->seed(ContactSeeder::class);

        $contactID = User::query()->where('username', 'jufron')->first()->contacts()->first()->id;

        $this->put("api/contacts/$contactID", headers:[
            'authorization'     => '1234567890'
        ], data: [
            'firstName'     => '',
            'lastname'      => 'di update',
            'email'         => 'jufrontamoama@gmail.com',
            'phone'         => '082147554549'
        ])
        ->assertStatus(422)
        ->assertUnprocessable()
        ->assertJson([
             "errors" => [
                 "firstName" => [
                    "The first name field is required."
                 ]
             ]
        ])
        ->assertJsonStructure([
            'errors'    => [
                'firstName'
            ]
        ]);
    }

    public function testUpdateDataUnauthorization () : void
    {
        $this->seed(ContactSeeder::class);

        $contactID = User::query()->where('username', 'jufron')->first()->contacts()->first()->id;

        $this->put("api/contacts/$contactID", headers:[
            'authorization'     => '123456789'
        ], data: [
            'firstName'     => 'jufron',
            'lastname'      => 'di update',
            'email'         => 'jufrontamoama@gmail.com',
            'phone'         => '082147554549'
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

    public function testDeleteSuccess (): void
    {
        $this->seed(ContactSeeder::class);

        $contactID = User::query()->where('username', 'jufron')
                    ->first()->contacts()->first()->id;

        $response = $this->delete(uri:"api/contacts/$contactID", headers:[
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJson([
            'data'  => true
        ])
        ->assertJsonStructure([
            'data'
        ]);

        $jsonData = $response->json();

        $this->assertTrue($jsonData['data']);
        $this->assertNotFalse($jsonData['data']);
    }

    public function testDeleteNotFound () : void
    {
        $this->seed(ContactSeeder::class);

        $response = $this->delete(uri:"api/contacts/1", headers:[
            'authorization'     => '1234567890'
        ])
        ->assertStatus(404)
        ->assertNotFound()
        ->assertJson([
            'errors' => [
                'message' => [
                    'resource not found'
                ]
            ]
        ])
        ->assertJsonStructure([
            'errors' => [
                'message'
            ]
        ]);
    }

    public function testDeleteUnauthorize () : void
    {
        $this->seed(ContactSeeder::class);

        $contactID = User::query()->where('username', 'jufron')
                    ->first()->contacts()->first()->id;

        $response = $this->delete(uri:"api/contacts/$contactID", headers:[
            'authorization'     => '123456789'
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

    public function testSearchByName () : void
    {
        $this->seed(ContactSearchSeeder::class);

        $response = $this->get('api/contacts?name=jufron&size=20', [
            'authorization' => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        // Log::info(json_encode($response, JSON_PRETTY_PRINT));

        $this->assertEquals(20, count($response['data']));
        $this->assertCount(20, $response['data']);
        $this->assertEquals(20, $response['meta']['total']);

        $data = $response['data'][0];

        $this->assertArrayHasKey('firstName', $data);
        $this->assertArrayHasKey('lastName', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('phone', $data);
    }

    public function testSearchByFirstName () : void
    {
        $this->seed(ContactSearchSeeder::class);

        $response = $this->get('api/contacts?name=jufron5', [
            'authorization' => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        // Log::info(json_encode($response, JSON_PRETTY_PRINT));

        $this->assertEquals(1, count($response['data']));
        $this->assertCount(1, $response['data']);
        $this->assertEquals(1, $response['meta']['total']);

        $data = $response['data'][0];

        $this->assertArrayHasKey('firstName', $data);
        $this->assertArrayHasKey('lastName', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('phone', $data);

        $this->assertEquals('jufron5', $data['firstName']);
        $this->assertEquals('tamo ama5', $data['lastName']);
        $this->assertEquals('jufrontamoama5@gmail.com', $data['email']);
        $this->assertEquals('08214755455', $data['phone']);
    }

    public function testSearchByLastName () : void
    {
        $this->seed(ContactSearchSeeder::class);

        $response = $this->get('api/contacts?name=tamo%20ama4', [
            'authorization' => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        // Log::info(json_encode($response, JSON_PRETTY_PRINT));

        $this->assertEquals(1, count($response['data']));
        $this->assertCount(1, $response['data']);
        $this->assertEquals(1, $response['meta']['total']);

        $data = $response['data'][0];

        $this->assertArrayHasKey('firstName', $data);
        $this->assertArrayHasKey('lastName', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('phone', $data);

        $this->assertEquals('jufron4', $data['firstName']);
        $this->assertEquals('tamo ama4', $data['lastName']);
        $this->assertEquals('jufrontamoama4@gmail.com', $data['email']);
        $this->assertEquals('08214755454', $data['phone']);
    }

    public function testSearchByEmail () : void
    {
        $this->seed(ContactSearchSeeder::class);

        $response = $this->get('api/contacts?email=jufrontamoama4@gmail.com', [
            'authorization' => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        // Log::info(json_encode($response, JSON_PRETTY_PRINT));

        $this->assertEquals(1, count($response['data']));
        $this->assertCount(1, $response['data']);
        $this->assertEquals(1, $response['meta']['total']);

        $data = $response['data'][0];

        $this->assertArrayHasKey('firstName', $data);
        $this->assertArrayHasKey('lastName', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('phone', $data);

        $this->assertEquals('jufron4', $data['firstName']);
        $this->assertEquals('tamo ama4', $data['lastName']);
        $this->assertEquals('jufrontamoama4@gmail.com', $data['email']);
        $this->assertEquals('08214755454', $data['phone']);
    }

    public function testSearchByPhone () : void
    {
        $this->seed(ContactSearchSeeder::class);

        $response = $this->get('api/contacts?phone=08214755453', [
            'authorization' => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        // Log::info(json_encode($response, JSON_PRETTY_PRINT));

        $this->assertEquals(1, count($response['data']));
        $this->assertCount(1, $response['data']);
        $this->assertEquals(1, $response['meta']['total']);

        $data = $response['data'][0];

        $this->assertArrayHasKey('firstName', $data);
        $this->assertArrayHasKey('lastName', $data);
        $this->assertArrayHasKey('email', $data);
        $this->assertArrayHasKey('phone', $data);

        $this->assertEquals('jufron3', $data['firstName']);
        $this->assertEquals('tamo ama3', $data['lastName']);
        $this->assertEquals('jufrontamoama3@gmail.com', $data['email']);
        $this->assertEquals('08214755453', $data['phone']);
    }

    public function testSearchWithPage () : void
    {
        $this->seed(ContactSearchSeeder::class);

        $response = $this->get('api/contacts?page=2&size=5', [
            'authorization' => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        $this->assertEquals(5, count($response['data']));
        $this->assertCount(5, $response['data']);
        $this->assertEquals(20, $response['meta']['total']);
        $this->assertEquals(2, $response['meta']['current_page']);
    }

    public function testSearchNotFound () : void
    {
        $this->seed(ContactSearchSeeder::class);

        $response = $this->get('api/contacts?name=sinta', [
            'authorization' => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        // Log::info(json_encode($response, JSON_PRETTY_PRINT));

        $this->assertEquals(0, count($response['data']));
        $this->assertCount(0, $response['data']);
        $this->assertEquals(0, $response['meta']['total']);
    }
}
