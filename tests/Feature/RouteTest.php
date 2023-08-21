<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RouteTest extends TestCase
{
    public function test_route_about (): void 
    {
        $response = $this->get('/about');
        $response->assertStatus(200);
        $response->assertContent('hello about');
        $response->assertSeeText('hello about');
        $this->assertEquals('hello about', $response->getContent());
    }

    public function test_route_contact (): void
    {
        $this->get('/contact')
             ->assertStatus(200)
             ->assertSeeText('hello contact')
             ->assertSuccessful();
    }

    public function test_route_redirect_seting_to_seting_dev (): void
    {
        $this->get('/seting')
             ->assertStatus(302)
             ->assertRedirect();
        
        $this->get('/seting/dev')
             ->assertStatus(200)
             ->assertSee('hello seting dev');
    }

    public function test_route_testing_to_not_found (): void
    {
        $this->get('/404')
             ->assertSeeText('404 by JR');
    }
}
