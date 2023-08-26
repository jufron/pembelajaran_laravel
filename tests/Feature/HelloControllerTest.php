<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelloControllerTest extends TestCase
{
    public function test_hello_route (): void
    {
        $this->get('hello/james')
             ->assertStatus(200)
             ->assertSeeText('good bye james');
    }

    public function test_profile_route (): void
    {
        $this->get('profile')
             ->assertStatus(200)
             ->assertSeeText('sinta');
    }

    public function test_request (): void
    {
        $this->withHeaders([
            'accept' => 'plain/text',
            'x-header'   => 'sinta'
            ])
                         ->get('request')
                         ->assertSeeText('GET')
                         ->assertSeeText('plain/text');
                        //  ->assertSeeText('sinta');

    }
}
