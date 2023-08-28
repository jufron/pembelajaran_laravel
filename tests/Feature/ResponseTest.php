<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResponseTest extends TestCase
{
    public function test_response(): void
    {
        $this->get('response/data')
             ->assertStatus(201)
             ->assertSeeText('hello laravel');
    }

    public function test_response_with_header (): void
    {
        $this->get('response/header')
             ->assertStatus(200)
             ->assertSeeText('jufron')
             ->assertSeeText('tamo ama')
             ->assertExactJson([
                'firstName' => 'jufron',
                'lastName'  => 'tamo ama'
             ])
             ->assertHeader('Content-Type', 'application/json')
             ->assertHeader('app', 'laravel')
             ->assertHeader('author', 'jufron');
    }

    public function test_response_with_view (): void
    {
        $this->get('response/view')
             ->assertStatus(200)
             ->assertSeeText('hello world');
    }

    public function test_response_with_file (): void
    {
        $this->get('response/file')
             ->assertStatus(200)
             ->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function test_response_with_download (): void
    {
        $this->get('response/download')
             ->assertStatus(200)
             ->assertDownload('coba.txt');
    }
}
