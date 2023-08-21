<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class FacadesTest extends TestCase
{
    public function test_call_key_value_config_using_config_function (): void
    {
        $firstName = config('belajar_config.author.first');
        $this->assertEquals('jufron', $firstName);
    }

    public function test_call_key_value_config_using_config_class_facades (): void
    {
        $email = Config::get('belajar_config.email');
        $this->assertEquals('jufrontamoama@gmail.com', $email);
    }

    public function test_facade_mock () 
    {
        Config::shouldReceive('get')
                ->with('contoh.author.first')
                ->andReturn('sinta');
        
        $firstName = Config::get('contoh.author.first');
        $this->assertEquals('sinta', $firstName);
    }
}
