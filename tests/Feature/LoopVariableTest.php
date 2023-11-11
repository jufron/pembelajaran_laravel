<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoopVariableTest extends TestCase
{
    public function test_loop_variable_iteration (): void
    {
        $this->view('loop_variabel', [
            'data'  => ['indra', 'dodi', 'putri', 'sinta']
        ])
        ->assertViewHas('data')
        ->assertSeeTextInOrder([
            'ini iterasi ke : 1 indra',
            'ini iterasi ke : 2 dodi',
            'ini iterasi ke : 3 putri',
            'ini iterasi ke : 4 sinta'
        ]);


        $this->view('loop_variabel', [
            'data'  => ['indra', 'dodi', 'putri', 'sinta']
        ])
        ->assertViewHas('data')
        ->assertSeeTextInOrder([
            'ini index ke : 0 indra',
            'ini index ke : 1 dodi',
            'ini index ke : 2 putri',
            'ini index ke : 3 sinta'
        ]);


        $this->view('loop_variabel', [
            'data'  => ['indra', 'dodi', 'putri', 'sinta']
        ])
        ->assertViewHas('data')
        ->assertSeeTextInOrder([
            '3',
            '2',
            '1',
            '0'
        ]);
    }
}
