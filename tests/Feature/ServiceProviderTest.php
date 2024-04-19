<?php

namespace Tests\Feature;

use App\Data\Bar;
use App\Data\Foo;
use App\Data\UserRepository;
use App\Service\Contract\HelloService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testServicePrvider (): void
    {
        $foo = app()->make(Foo::class);
        $bar = app()->make(Bar::class);

        $this->assertNotSame($foo, $bar->getFooFromBar());
        $this->assertEquals('ini foo', $bar->bar());
        $this->assertEquals('ini foo 2', $bar->bar2());
    }

    public function testProperty (): void
    {
        $helloService = app()->make(HelloService::class);

        $this->assertNotNull($helloService->hello('james'));
        $this->assertEquals("hallo selamat pagi james", $helloService->hello('james'));
    }

    public function testUserRepsitory (): void
    {
        $userRepository = app()->make(UserRepository::class);

        $this->assertEquals('select * from user', $userRepository->get('user'));
        $this->assertEquals('ini adalah log', $userRepository->log());
        $this->assertNotNull($userRepository->get('articles'));
        $this->assertNotNull($userRepository->log());
    }
}
