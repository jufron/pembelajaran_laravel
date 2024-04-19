<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelloControllerTest extends TestCase
{
    public function testHelloController (): void
    {
        $this->get('hello')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertSeeText('hello from controller');
    }

    public function testDependencyInjectionHelloService (): void
    {
        $this->get('hello/service/jufron')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertSeeText('hallo selamat pagi jufron');
    }

    public function testDependencyInjectionBar (): void
    {
        $this->get('bar')
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertSeeText('ini foo');
    }

    public function testDependencyInjectionUserRepositoryGet (): void
    {
        $this->get('user-service/get/student')
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertSeeText('select * from student');
    }

    public function testDependencyInjectionUserRepositoryLog (): void
    {
        $this->get('user-service/log')
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertSeeText('ini adalah log');
    }

    public function testSendToRequest (): void
    {
        $this->get('hello/request/input?nama=jufron_from_parameter')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertSeeText('hello jufron_from_parameter');

        $this->post('hello/request/input', [
            'nama'  => 'james from input'
        ])
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertSeeText('hello james from input');
    }

    public function testRequestNestedInput (): void
    {
        $this->post('hello/request/nested', [
            'name' => [
                'first' => 'jufron',
                'last'  => 'tamo ama'
            ]
        ])
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertSeeText('hello jufron tamo ama');
    }

    public function testRequestMerge (): void
    {
        $response = $this->post('hello/request/merge', [
            'firstName' => 'jufron',
            'lastName'  => 'tamo ama',
            'email'     => 'jufrontamoama@gmail.com',
            'admin'     => true
        ])
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertJson([
                'firstName' => 'jufron',
                'lastName'  => 'tamo ama',
                'email'     => 'jufrontamoama@gmail.com',
                'admin'     => false,
                'data'      => null,
                'agama'     => null
            ])
            ->assertJsonStructure([
                'firstName',
                'lastName',
                'email',
                'admin',
                'data',
                'agama'
            ])
            ->assertJsonIsObject();

        $convertToArr = json_decode($response->getContent(), true);

        $this->assertIsArray($convertToArr);

        $this->assertArrayHasKey('firstName', $convertToArr);
        $this->assertArrayHasKey('lastName', $convertToArr);
        $this->assertArrayHasKey('email', $convertToArr);
        $this->assertArrayHasKey('data', $convertToArr);
        $this->assertArrayHasKey('agama', $convertToArr);

        $this->assertNull($convertToArr['data']);
        $this->assertNull($convertToArr['agama']);

        $this->assertEquals('jufron', $convertToArr['firstName']);
        $this->assertEquals('tamo ama', $convertToArr['lastName']);
        $this->assertEquals('jufrontamoama@gmail.com', $convertToArr['email']);
        $this->assertEquals(false, $convertToArr['admin']);
        $this->assertEquals(null, $convertToArr['data']);
        $this->assertEquals(null, $convertToArr['agama']);
    }
}
