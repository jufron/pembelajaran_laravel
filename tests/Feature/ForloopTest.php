<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ForloopTest extends TestCase
{
    public function test_for_loop_data (): void
    {
        $this->view('for_loop', [
            'data'  => ['apple', 'mangga', 'semangka', 'nanas'],
            'siswa' => ['dodi', 'sinta']
        ])
        ->assertViewHasAll(['data', 'siswa'])
        ->assertSeeTextInOrder(['apple', 'mangga', 'semangka', 'nanas']);
    }

    public function test_foreach_loop_data (): void
    {
        $this->view('for_loop', [
            'data'    => ['melon', 'mangga'],
            'siswa'   => ['sinta', 'dodi', 'erik', 'james']
        ])
        ->assertViewHasAll(['data', 'siswa'])
        ->assertSeeTextInOrder(['sinta', 'dodi', 'erik', 'james']);
    }

    public function test_forelse_loop_data (): void
    {
        $this->view('for_loop', [
            'data'    => ['melon', 'mangga'],
            'siswa'   => ['sinta', 'dodi', 'erik', 'james']
        ])
        ->assertViewHasAll(['data', 'siswa'])
        ->assertSeeTextInOrder(['sinta', 'dodi', 'erik', 'james'])
        ->assertDontSeeText('todal ada data');


        $this->view('for_loop', [
            'data'    => ['melon', 'mangga'],
            'siswa'   => []
        ])
        ->assertViewHasAll(['data', 'siswa'])
        ->assertDontSeeText('sinta')
        ->assertDontSeeText('dodi')
        ->assertDontSeeText('erik')
        ->assertDontSeeText('james')
        ->assertSeeText('tidak ada data');
    }

    public function test_derective_php (): void
    {
        $this->view('for_loop', [
            'data'    => ['melon', 'mangga'],
            'siswa'   => ['sinta', 'dodi', 'erik', 'james']
        ])
        ->assertViewHasAll(['data', 'siswa'])
        ->assertSeeText('hello indra alamat email saya indra@gmail.com');
    }
}
