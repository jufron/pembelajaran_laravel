<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClassDerectiveTest extends TestCase
{
    public function test_class_derective_active_true (): void
    {
        $this->view('css_class', [
            'isActive'  => true
        ])
        ->assertViewHas('isActive')
        ->assertSeeText('active')
        ->assertSeeText('Home');
    }

    public function test_class_derective_active_false (): void
    {
        $this->view('css_class', [
            'isActive'  => false
        ])
        ->assertViewHas('isActive')
        // ->assertDontSeeText('active')
        ->assertSeeText('Home');
    }
}
