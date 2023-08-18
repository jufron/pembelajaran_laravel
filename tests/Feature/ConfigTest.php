<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ConfigTest extends TestCase
{
    public function test_config_value_same(): void
    {
        $first = config('belajar_config.author.first');
        $last = config('belajar_config.author.last');
        $email = config('belajar_config.email');
        $web = config('belajar_config.web');
        $lulus = config('belajar_config.lulus');

        $this->assertSame('jufron', $first);
        $this->assertSame('tamo ama', $last);
        $this->assertSame('jufrontamoama@gmail.com', $email);
        $this->assertSame('https://www.jufrontamoama.com', $web);
        $this->assertSame(true, $lulus);
    }

    public function test_config_is_excist() : void 
    {
        $this->assertTrue(config()->has('belajar_config.author.first'));
        $this->assertTrue(config()->has('belajar_config.author.last'));
        $this->assertTrue(config()->has('belajar_config.email'));
        $this->assertTrue(config()->has('belajar_config.web'));
        $this->assertTrue(config()->has('belajar_config.lulus'));
    }

    public function test_config_data_type(): void
    {
        $first = config('belajar_config.author.first');
        $last = config('belajar_config.author.last');
        $email = config('belajar_config.email');
        $web = config('belajar_config.web');
        $lulus = config('belajar_config.lulus');

        $this->assertTrue(is_string($first));
        $this->assertTrue(is_string($last));
        $this->assertTrue(is_string($email));
        $this->assertTrue(is_string($web));
        $this->assertTrue($lulus);
    }
}