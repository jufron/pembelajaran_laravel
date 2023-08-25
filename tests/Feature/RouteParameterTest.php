<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteParameterTest extends TestCase
{
    public function test_route_with_multiple_parameter (): void
    {
        $this->get('/hello/sinta/123')
             ->assertStatus(200)
             ->assertSeeText('hallo sinta id saya 123');

        $this->get('/hello/dodi/111')
             ->assertStatus(200)
             ->assertSeeText('hallo dodi id saya 111');

        $this->get('/hello/ida/456')
             ->assertStatus(200)
             ->assertSeeText('hallo ida id saya 456');
    }

    public function test_route_with_multiple (): void
    {
        $this->get('/siswa/111')
             ->assertStatus(200)
             ->assertSeeText('hallo 111');

        $this->get('/siswa/123')
             ->assertStatus(200)
             ->assertSeeText('hallo 123');

        $this->get('/siswa/456')
             ->assertStatus(200)
             ->assertSeeText('hallo 456');
    }

    public function test_route_parameter_using_regular_expression_constrains (): void
    {
        $this->get('mapel/1234')
             ->assertStatus(200)
             ->assertSeeText('mata pelajaran id 1234');

        $this->get('mapel/abc')
             ->assertStatus(200)
             ->assertSeeText('404 by JR');
    }

    public function test_route_parameter_optional (): void
    {
        $this->get('user/james')
             ->assertStatus(200)
             ->assertSeeText('hallo user james');

        $this->get('user/sinta')
             ->assertStatus(200)
             ->assertSeeText('hallo user sinta');

        $this->get('/user')
             ->assertStatus(200)
             ->assertSeeText('hallo user not found');
    }
}

