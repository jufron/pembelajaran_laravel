<?php

namespace Tests\Unit;

use App\Data\{
    Bar,
    Foo
};
use PHPUnit\Framework\TestCase;

class DependencyInjectionTest extends TestCase
{
    public function test_dependency_injection (): void
    {
        $foo = new Foo();
        $bar = new Bar($foo);

        $this->assertEquals('foo class and bar class', $bar->bar());
    }
}
