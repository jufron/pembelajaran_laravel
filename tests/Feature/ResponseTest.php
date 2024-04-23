<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testResponseHeader (): void
    {
        $this->get('response/header')
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful()
        ->assertHeader('Content-Type', 'application/json')
        ->assertHeader('author', 'james')
        ->assertHeader('app', 'belajar laravel')
        ->assertJson([
            'firstName' => 'jufron',
            'lastName'  => 'tamo ama',
            'email'     => 'jufrontamoama@gmail.com'
        ])
        ->assertJsonStructure([
            'firstName',
            'lastName',
            'email'
        ]);
    }

    public function testResponseHeader2 (): void
    {
        $this->post('response/header/request', [
            'firstName'     => 'jufron',
            'lastName'      => 'tamo ama',
            'email'         => 'jufrontamoama@gmail.com'
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk()
        ->assertHeader('Content-Type', 'application/json')
        ->assertJson([
            'firstName' => 'jufron',
            'lastName'  => 'tamo ama',
            'email'     => 'jufrontamoama@gmail.com'
        ])
        ->assertJsonStructure([
            'firstName',
            'lastName',
            'email'
        ]);
    }

    public function testResponseView (): void
    {
        $this->get('response/view')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertViewHas('nama', 'james')
            ->assertViewIs('siswa')
            ->assertSeeText('hello james');
    }

    public function testResponseFile (): void
    {
        $this->get('response/file')
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertHeader('Content-Type', 'image/jpeg');
    }

    public function testREsponseDownload (): void
    {
        $this->get('response/download')
            ->assertStatus(200)
            ->assertSuccessful()
            ->assertOk()
            ->assertHeader('Content-Type', 'image/jpeg')
            ->assertDownload('designer.jpeg');
    }
}
