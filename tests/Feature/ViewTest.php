<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewTest extends TestCase
{
    public function testView (): void
    {
        $this->get('/siswa')
            ->assertStatus(200)
            ->assertOk()
            ->assertSuccessful()
            ->assertViewIs('siswa')
            ->assertViewHas('nama', 'jufron')
            ->assertSeeText('hello jufron');

        $this->view('siswa', ['nama' => 'james'])
            ->assertSeeText('hello james')
            ->assertViewHas('nama', 'james');
    }
}
