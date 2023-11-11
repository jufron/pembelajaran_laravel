<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Isset_emptyTest extends TestCase
{
    public function test_isset (): void
    {
        $this->view('isset_empty', [
            'nama'  => 'james',
            'buah'  => []
        ])
        ->assertViewHasAll(['nama', 'buah'])
        ->assertSeeText('hello my name is james');

        $this->view('isset_empty', [
            'buah'  => []
        ])
        ->assertViewHasAll(['buah'])
        ->assertDontSeeText('hello my name is james');
    }

    public function test_empty (): void
    {
        $this->view('isset_empty', [
            'nama'  => 'sinta',
            'buah'  => []
        ])
        ->assertViewHasAll(['nama', 'buah'])
        ->assertSeeText('0', false);

        $this->view('isset_empty', [
            'nama'  => 'sinta',
            'buah'  => ['anggur', 'semangka', 'pepaya']
        ])
        ->assertViewHasAll(['nama', 'buah'])
        ->assertDontSeeText('3');
    }
}
