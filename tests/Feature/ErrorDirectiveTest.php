<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ErrorDirectiveTest extends TestCase
{
    public function test_error_directive (): void
    {
        $errors = [
            'name'      => 'name harus diisi',
            'password'  => 'password harus diisi'
        ];
        $this->withViewErrors($errors)
        ->view('error_directive', [])
        ->assertSeeText('name harus diisi')
        ->assertSeeText('password harus diisi');
    }
}
