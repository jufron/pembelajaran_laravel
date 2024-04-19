<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class FacadesTest extends TestCase
{
    public function testConfigMock (): void
    {
        Config::shouldReceive('get')
            ->with('author.firstName')
            ->andReturn('jufron');

        Config::shouldReceive('get')
            ->with('author.lastName')
            ->andReturn('tamo ama');

        Config::shouldReceive('get')
            ->with('author.email')
            ->andReturn('jufrontamoama@gmail.com');

        $firstNAme = Config::get('author.firstName');
        $lastNAme = Config::get('author.lastName');
        $email = Config::get('author.email');

        $this->assertNotNull($firstNAme);
        $this->assertNotNull($lastNAme);
        $this->assertNotNull($email);

        $this->assertEquals('jufron', $firstNAme);
        $this->assertEquals('tamo ama', $lastNAme);
        $this->assertEquals('jufrontamoama@gmail.com', $email);
    }

    public function testConfigMock2 (): void
    {
        Config::shouldReceive('get')
            ->with('students')
            ->andReturn(['james', 'sinta', 'dodi']);

        Config::shouldReceive('get')
            ->with('teachers')
            ->andReturn(['adi', 'aldi']);

        Config::shouldReceive('get')
            ->with('study')
            ->andReturn(['web programing', 'algorithm', 'data structure']);

        $students = Config::get('students');
        $teachers = Config::get('teachers');
        $studys = Config::get('study');

        $this->assertNotNull($students);
        $this->assertIsArray($students);
        $this->assertEquals(['james', 'sinta', 'dodi'], $students);

        $this->assertNotNull($teachers);
        $this->assertIsArray($teachers);
        $this->assertEquals(['adi', 'aldi'], $teachers);

        $this->assertNotNull($studys);
        $this->assertIsArray($studys);
        $this->assertEquals(['web programing', 'algorithm', 'data structure'], $studys);
    }
}
