<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InputControllerTest extends TestCase
{
    public function test_route_get_hello_request (): void
    {
        $this->get('input/request?nama=james')
        ->assertStatus(200)
        // ->assertSeeText('GET')
        ->assertSeeText('hallo james');
    }

    public function test_route_post_hello_request (): void
    {
        $this->post('input/request', [
            'nama'  => 'sinta'
        ])
        ->assertStatus(200)
        // ->assertSeeText('POST')
        ->assertSeeText('hallo sinta');
    }

    public function test_route_post_hello_nested_request (): void
    {
        $this->post('input/nested', [
            'nama' => [
                'first' => 'jufron',
                'last'  => 'tamo ama'
            ],
            "alamat" => [
                'negara'    => 'indonesia',
                'kota'      => 'kupang',
                'provinsi'  => 'nusa tenggara timur',
                'jalan'     => 'jl tasek jalur 40 haukoto'
            ]

        ])
        ->assertStatus(200)
        ->assertSeeText('jufron')
        ->assertSeeText('tamo ama');
    }
}

