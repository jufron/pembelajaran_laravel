<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HelloTest extends TestCase
{
    public function test_view_hello (): void
    {
        $this->view('hello', [
            'title' => 'ini title',
            'name'  => 'ini aldo'
        ])
        ->assertViewHasAll(['title', 'name'])
        ->assertSeeText('ini title')
        ->assertSeeText('ini aldo');
    }

    public function test_route_hello_page (): void
    {
        $this->get('home')
        ->assertViewIs('hello')
        ->assertViewHasAll(['title', 'name'])
        ->assertSeeText('halaman home')
        ->assertSeeText('sinta');
    }

    public function test_hello_page_disble_blade (): void
    {
        $this->view('hello', [
            'title' => 'ini title',
            'name'  => 'ini name'
        ])
        ->assertSeeText('{{ $nama }}');
    }

    public function test_hello_page_disble_blade_using_verbatim_derective (): void
    {
        $this->view('hello', [
            'title'     => 'ini title',
            'name'      => 'ini name'
        ])
        ->assertSeeText('{{ $nama }}')
        ->assertSeeText('{{ $email }}')
        ->assertSeeText('{{ $alamat }}');
    }
}
