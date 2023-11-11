<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnvDerectiveTest extends TestCase
{
    public function test_env_not_testing (): void
    {
        $originalEnv = getenv('APP_ENV');
        putenv('APP_ENV=production');

        $this->view('env')
        ->assertSeeText('env')
        ->assertSeeText('ini mode testing');

        putenv("APP_ENV=$originalEnv");
    }

    public function test_env_testing (): void
    {
        $this->view('env')
        ->assertSeeText('env')
        ->assertSeeText('ini mode testing');
    }
}
