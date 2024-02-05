<?php

namespace Tests\Feature;

use App\Models\Address;
use App\Models\Contact;
use App\Models\User;
use Database\Seeders\AddressSeeder;
use Database\Seeders\ContactSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AddressTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (User::query()->count() >= 1) {
            User::query()->delete();
        }

        if (Contact::query()->count() >= 1) {
            Contact::query()->delete();
        }

        if (Address::query()->count() >= 1) {
            Address::query()->delete();
        }
    }

    private function createDataSeed ()
    {
        if (!User::query()->where('username', 'jufron')->first()) {
            $this->seed(UserSeeder::class);
        }
        $user = User::query()->where('username', 'jufron')->first();

        if (!$user->contacts()->where('email', 'jufrontamoama@gmail.com')->first()) {
            $this->seed(ContactSeeder::class);
        }

        return $user->contacts()->first()->id;
    }

    public function testCreateDataSuccess () : void
    {
        $id = $this->createDataSeed();

        $this->post("api/contacts/$id/addreses", [
            'street'        => 'jl tasek',
            'rt'            => '011',
            'rw'            => '003',
            'city'          => 'kota kupang',
            'province'      => 'nusa tenggara timur',
            'country'       => 'indonesia',
            'postal_code'   => '85141'
        ], [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(201)
        ->assertCreated()
        ->assertSuccessful()
        ->assertJson([
            'data'  => [
                'street'        => 'jl tasek',
                'rt'            => '011',
                'rw'            => '003',
                'city'          => 'kota kupang',
                'province'      => 'nusa tenggara timur',
                'country'       => 'indonesia',
                'postal_code'   => '85141'
            ]
        ])
        ->assertJsonStructure([
            'data'  => [
                'street',
                'rt',
                'rw',
                'city',
                'province',
                'country',
                'postal_code'
            ]
        ]);
    }

    public function testCreateDataNotFound ()
    {
        $id = $this->createDataSeed();

          $this->post("api/contacts/900/addreses", [
            'street'        => 'jl tasek',
            'rt'            => '011',
            'rw'            => '003',
            'city'          => 'kota kupang',
            'province'      => 'nusa tenggara timur',
            'country'       => 'indonesia',
            'postal_code'   => '85141'
        ], [
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
            'errors'  => [
                'message'
            ]
        ]);
    }

    public function testCreateDataUnauthorize ()
    {
        $id = $this->createDataSeed();

        $this->post("api/contacts/$id/addreses", [
            'street'        => 'jl tasek',
            'rt'            => '011',
            'rw'            => '003',
            'city'          => 'kota kupang',
            'province'      => 'nusa tenggara timur',
            'country'       => 'indonesia',
            'postal_code'   => '85141'
        ], [
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
            'errors'  => [
                'message'
            ]
        ]);

    }

    public function testCreateInvalidValidation () : void
    {
        $id = $this->createDataSeed();

        $this->post("api/contacts/$id/addreses", [
            'street'        => 'jl tasek',
            'rt'            => '011',
            'rw'            => '003',
            'city'          => 'kota kupang',
            'province'      => 'nusa tenggara timur',
            'country'       => 'indonesia',
            'postal_code'   => '851411111111111'
        ], [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(422)
        ->assertUnprocessable()
        ->assertJson([
            'errors' => [
                'postal_code' => [
                    "The postal code field must be between 3 and 10 digits."
                ]
            ]
        ])
        ->assertJsonStructure([
            'errors'  => [
                'postal_code'
            ]
        ]);
    }

    public function testGetaAllDataSuccess () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $contactId = Contact::query()->first()->id;

        $response = $this->get("api/contacts/$contactId/addreses", [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        $this->assertCount(5, $response['data']);
        $this->assertEquals(5, count($response['data']));

        $data = $response['data'][0];

        $this->assertArrayHasKey('street', $data);
        $this->assertArrayHasKey('rt', $data);
        $this->assertArrayHasKey('rw', $data);
        $this->assertArrayHasKey('city', $data);
        $this->assertArrayHasKey('province', $data);
        $this->assertArrayHasKey('postal_code', $data);
    }

    public function testGetaWhereStreet () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $contactId = Contact::query()->first()->id;
        $street = '?street=jl%20tasek%204';

        $response = $this->get("api/contacts/$contactId/addreses$street", [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        Log::info($response);

        $this->assertCount(1, $response['data']);
        $this->assertEquals(1, count($response['data']));

        $data = $response['data'][0];

        $this->assertArrayHasKey('street', $data);
        $this->assertArrayHasKey('rt', $data);
        $this->assertArrayHasKey('rw', $data);
        $this->assertArrayHasKey('city', $data);
        $this->assertArrayHasKey('province', $data);
        $this->assertArrayHasKey('postal_code', $data);

        $this->assertEquals('jl tasek 4', $data['street']);
        $this->assertEquals('014', $data['rt']);
        $this->assertEquals('004', $data['rw']);
        $this->assertEquals('kota kupang 4', $data['city']);
        $this->assertEquals('nusa tenggara timur4', $data['province']);
        $this->assertEquals('indonesia4', $data['country']);
        $this->assertEquals('8514', $data['postal_code']);
    }

    public function testGetWhereRt () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $contactId = Contact::query()->first()->id;
        $street = '?rt=013';

        $response = $this->get("api/contacts/$contactId/addreses$street", [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        Log::info($response);

        $this->assertCount(1, $response['data']);
        $this->assertEquals(1, count($response['data']));

        $data = $response['data'][0];

        $this->assertArrayHasKey('street', $data);
        $this->assertArrayHasKey('rt', $data);
        $this->assertArrayHasKey('rw', $data);
        $this->assertArrayHasKey('city', $data);
        $this->assertArrayHasKey('province', $data);
        $this->assertArrayHasKey('postal_code', $data);

        $this->assertEquals('jl tasek 3', $data['street']);
        $this->assertEquals('013', $data['rt']);
        $this->assertEquals('003', $data['rw']);
        $this->assertEquals('kota kupang 3', $data['city']);
        $this->assertEquals('nusa tenggara timur3', $data['province']);
        $this->assertEquals('indonesia3', $data['country']);
        $this->assertEquals('8513', $data['postal_code']);
    }

    public function testGetWhereRw () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $contactId = Contact::query()->first()->id;
        $street = '?rw=002';

        $response = $this->get("api/contacts/$contactId/addreses$street", [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        Log::info($response);

        $this->assertCount(1, $response['data']);
        $this->assertEquals(1, count($response['data']));

        $data = $response['data'][0];

        $this->assertArrayHasKey('street', $data);
        $this->assertArrayHasKey('rt', $data);
        $this->assertArrayHasKey('rw', $data);
        $this->assertArrayHasKey('city', $data);
        $this->assertArrayHasKey('province', $data);
        $this->assertArrayHasKey('postal_code', $data);

        $this->assertEquals('jl tasek 2', $data['street']);
        $this->assertEquals('012', $data['rt']);
        $this->assertEquals('002', $data['rw']);
        $this->assertEquals('kota kupang 2', $data['city']);
        $this->assertEquals('nusa tenggara timur2', $data['province']);
        $this->assertEquals('indonesia2', $data['country']);
        $this->assertEquals('8512', $data['postal_code']);
    }

    public function testGetWhereCity () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $contactId = Contact::query()->first()->id;
        $street = '?city=kota%20kupang%205';

        $response = $this->get("api/contacts/$contactId/addreses$street", [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        Log::info($response);

        $this->assertCount(1, $response['data']);
        $this->assertEquals(1, count($response['data']));

        $data = $response['data'][0];

        $this->assertArrayHasKey('street', $data);
        $this->assertArrayHasKey('rt', $data);
        $this->assertArrayHasKey('rw', $data);
        $this->assertArrayHasKey('city', $data);
        $this->assertArrayHasKey('province', $data);
        $this->assertArrayHasKey('postal_code', $data);

        $this->assertEquals('jl tasek 5', $data['street']);
        $this->assertEquals('015', $data['rt']);
        $this->assertEquals('005', $data['rw']);
        $this->assertEquals('kota kupang 5', $data['city']);
        $this->assertEquals('nusa tenggara timur5', $data['province']);
        $this->assertEquals('indonesia5', $data['country']);
        $this->assertEquals('8515', $data['postal_code']);
    }

    public function testGetWhereProvince () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $contactId = Contact::query()->first()->id;
        $street = '?province=nusa%20tenggara%20timur1';

        $response = $this->get("api/contacts/$contactId/addreses$street", [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        Log::info($response);

        $this->assertCount(1, $response['data']);
        $this->assertEquals(1, count($response['data']));

        $data = $response['data'][0];

        $this->assertArrayHasKey('street', $data);
        $this->assertArrayHasKey('rt', $data);
        $this->assertArrayHasKey('rw', $data);
        $this->assertArrayHasKey('city', $data);
        $this->assertArrayHasKey('province', $data);
        $this->assertArrayHasKey('postal_code', $data);

        $this->assertEquals('jl tasek 1', $data['street']);
        $this->assertEquals('011', $data['rt']);
        $this->assertEquals('001', $data['rw']);
        $this->assertEquals('kota kupang 1', $data['city']);
        $this->assertEquals('nusa tenggara timur1', $data['province']);
        $this->assertEquals('indonesia1', $data['country']);
        $this->assertEquals('8511', $data['postal_code']);
    }

    public function testGetWherePostalCode () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $contactId = Contact::query()->first()->id;
        $street = '?postal_code=8513';

        $response = $this->get("api/contacts/$contactId/addreses$street", [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertJsonStructure([
            'data',
            'links',
            'meta'
        ])
        ->json();

        Log::info($response);

        $this->assertCount(1, $response['data']);
        $this->assertEquals(1, count($response['data']));

        $data = $response['data'][0];

        $this->assertArrayHasKey('street', $data);
        $this->assertArrayHasKey('rt', $data);
        $this->assertArrayHasKey('rw', $data);
        $this->assertArrayHasKey('city', $data);
        $this->assertArrayHasKey('province', $data);
        $this->assertArrayHasKey('postal_code', $data);

        $this->assertEquals('jl tasek 3', $data['street']);
        $this->assertEquals('013', $data['rt']);
        $this->assertEquals('003', $data['rw']);
        $this->assertEquals('kota kupang 3', $data['city']);
        $this->assertEquals('nusa tenggara timur3', $data['province']);
        $this->assertEquals('indonesia3', $data['country']);
        $this->assertEquals('8513', $data['postal_code']);
    }

    public function testGetWhereUnauthorization () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $contactId = Contact::query()->first()->id;

        $this->get("api/contacts/$contactId/addreses", [
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
            'errors'  => [
                'message'
            ]
        ]);
    }

    public function testGetDataAddressWhereIdSuccess () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $user = User::query()->where('username', 'jufron')->first();

        $idContact = $user->contacts()->first()->id;
        $idAddress = $user->contacts()->first()->addresses()->first()->id;

        $this->get("api/contacts/$idContact/addreses/$idAddress", [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertJson([
            'data'  => [
                'street'        => 'jl tasek 1',
                'rt'            => '011',
                'rw'            => '001',
                'city'          => 'kota kupang 1',
                'province'      => 'nusa tenggara timur1',
                'country'       => 'indonesia1',
                'postal_code'   => '8511'
            ]
        ])
        ->assertJsonStructure([
            'data'  => [
                'street',
                'rt',
                'rw',
                'city',
                'province',
                'country',
                'postal_code'
            ]
        ]);

    }

    public function testGetDataAddressWhereIdNotFund () : void
    {
        $this->seed([
            UserSeeder::class,
        ]);

        $this->get("api/contacts/99/addreses/99", [
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

    public function testGetDataAddressWhereIdUnauthorization () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $idContact = Contact::query()->first()->id;
        $idAddress = Address::query()->first()->id;

        $this->get("api/contacts/$idContact/addreses/$idAddress", [
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
            'errors'  => [
                'message'
            ]
        ]);
    }

    public function testUpdateDataWhereIdSuccess () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $idContact = Contact::query()->first()->id;
        $idAddress = Address::query()->first()->id;

        $response = $this->put("api/contacts/$idContact/addreses/$idAddress", [
            'street'        => 'jl sukun',
            'rt'            => '022',
            'rw'            => '003',
            'city'          => 'kota kupang',
            'province'      => 'nusa tenggara timur',
            'country'       => 'indonesia',
            'postal_code'   => '85141'
        ], [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJson([
            'data' => [
                'street'        => 'jl sukun',
                'rt'            => '022',
                'rw'            => '003',
                'city'          => 'kota kupang',
                'province'      => 'nusa tenggara timur',
                'country'       => 'indonesia',
                'postal_code'   => '85141'
            ]
        ])
        ->assertJsonStructure([
            'data' => [
                'street',
                'rt',
                'rw',
                'city',
                'province',
                'country',
                'postal_code'
            ]
        ]);

        $data = $response->json();

        $this->assertArrayHasKey('data', $data);
        $this->assertArrayNotHasKey('street', $data);
        $this->assertArrayNotHasKey('rt', $data);
        $this->assertArrayNotHasKey('rw', $data);
        $this->assertArrayNotHasKey('city', $data);
        $this->assertArrayNotHasKey('province', $data);
        $this->assertArrayNotHasKey('country', $data);
        $this->assertArrayNotHasKey('postal_code', $data);

        $this->assertEquals('jl sukun' ,$data['data']['street']);
        $this->assertEquals('022' ,$data['data']['rt']);
        $this->assertEquals('003' ,$data['data']['rw']);
        $this->assertEquals('kota kupang' ,$data['data']['city']);
        $this->assertEquals('nusa tenggara timur' ,$data['data']['province']);
        $this->assertEquals('indonesia' ,$data['data']['country']);
        $this->assertEquals('85141' ,$data['data']['postal_code']);
    }

    public function testUpdateDataWhereIdInvalidValidation () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $idContact = Contact::query()->first()->id;
        $idAddress = Address::query()->first()->id;

        $this->put("api/contacts/$idContact/addreses/$idAddress", [
            'street'        => 'jl sukun',
            'rt'            => '022',
            'rw'            => '003',
            'city'          => 'kota kupang',
            'province'      => 'nusa tenggara timur',
            'country'       => 'indonesia',
            'postal_code'   => '85141111111111111111111'
        ], [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(422)
        ->assertUnprocessable()
        ->assertJson([
            'errors' => [
                'postal_code' => [
                    "The postal code field must be between 3 and 10 digits."
                ]
            ]
        ])
        ->assertJsonStructure([
            'errors'  => [
                'postal_code'
            ]
        ]);
    }

    public function testUpdateDataWhereIdNotFound () :  void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $this->put("api/contacts/1111/addreses/1111", [
            'street'        => 'jl sukun',
            'rt'            => '022',
            'rw'            => '003',
            'city'          => 'kota kupang',
            'province'      => 'nusa tenggara timur',
            'country'       => 'indonesia',
            'postal_code'   => '85141'
        ], [
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

    public function testUpdateDataWhereIdUnauthorization () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $idContact = Contact::query()->first()->id;
        $idAddress = Address::query()->first()->id;

        $this->put("api/contacts/$idContact/addreses/$idAddress", [
            'street'        => 'jl sukun',
            'rt'            => '022',
            'rw'            => '003',
            'city'          => 'kota kupang',
            'province'      => 'nusa tenggara timur',
            'country'       => 'indonesia',
            'postal_code'   => '85141'
        ], [
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
            'errors'  => [
                'message'
            ]
        ]);
    }

    public function testDeleteDataWhereIdSuccess () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $idContact = Contact::query()->first()->id;
        $idAddress = Address::query()->first()->id;

        $response = $this->delete(uri: "api/contacts/$idContact/addreses/$idAddress", headers: [
            'authorization'     => '1234567890'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertJson([
            'data' => true
        ])
        ->assertJsonStructure([
            'data'
        ])
        ->json();

        $this->assertTrue($response['data']);
        $this->assertNotFalse($response['data']);
    }

    public function testDeleteDataWhereIdNotFund () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $this->delete(uri: "api/contacts/101010/addreses/101010", headers: [
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

    public function testDeleteDataWhereIdUnauthorization () : void
    {
        $this->seed([
            UserSeeder::class,
            ContactSeeder::class,
            AddressSeeder::class
        ]);

        $idContact = Contact::query()->first()->id;
        $idAddress = Address::query()->first()->id;

        $this->delete(uri: "api/contacts/$idContact/addreses/$idAddress", headers: [
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
            'errors'  => [
                'message'
            ]
        ]);
    }
}
