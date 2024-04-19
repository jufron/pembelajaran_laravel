<?php

namespace Tests\Feature;

use App\Data\Bar;
use App\Data\Database;
use App\Data\Foo;
use App\Data\Log;
use App\Data\Person;
use App\Data\UserRepository;
use App\Service\Contract\HelloService;
use App\Service\HelloServiceEnglish;
use App\Service\HelloServiceIndonesia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
use Tests\TestCase;

class DependencyInjectionTest extends TestCase
{
    public function testDependencyInjection (): void
    {
        $foo = new Foo();
        $bar = new Bar($foo);

        $this->assertNotNull($bar->bar());
        $this->assertEquals('ini foo' ,$bar->bar());

        $this->assertNotNull($bar->bar2());
        $this->assertEquals('ini foo 2', $bar->bar2());
    }

    public function testDependencyInjection2 (): void
    {
        $database = new Database();
        // $userRepository = new UserRepository($database);

        // $this->assertNotNull($userRepository->get('user'));
        // $this->assertEquals('select * from user', $userRepository->get('user'));
    }

    public function testCreateDependency (): void
    {
        $foo1 = app()->make(Foo::class);
        $foo2 = app()->make(Foo::class);

        $this->assertEquals('ini foo', $foo1->foo());
        $this->assertEquals('ini foo', $foo2->foo());

        $this->assertNotSame($foo1, $foo2);

        $foo3 = app()->singleton(Foo::class);
        $foo4 = app()->singleton(Foo::class);

        $this->assertSame($foo3, $foo4);
    }

    public function testBind (): void
    {
        app()->bind(Person::class, function ($app) {
            return new Person('jufron', 'tamo ama', 'jufrontamoama@gmail.com');
        });

        $person1 = app()->make(Person::class);
        $person2 = app()->make(Person::class);

        $this->assertEquals('jufron', $person1->getFirstName());
        $this->assertEquals('tamo ama', $person1->getLastName());
        $this->assertEquals('jufrontamoama@gmail.com', $person1->getEamil());

        $this->assertEquals('jufron', $person2->getFirstName());
        $this->assertEquals('tamo ama', $person2->getLastName());
        $this->assertEquals('jufrontamoama@gmail.com', $person2->getEamil());

        $this->assertNotSame($person1, $person2);
    }

    public function testSingleton (): void
    {
        app()->singleton(Person::class, function ($app) {
            return new Person('jufron', 'tamo ama', 'jufrontamoama@gmail.com');
        });

        $person1 = app()->make(Person::class);
        $person2 = app()->make(Person::class);

        $this->assertSame($person1, $person2);
    }

    public function createInstance (): void
    {
        $person = new Person('jufron', 'tamo ama', 'jufrontamoama@gmail.com');
        app()->instance(Person::class, $person);
    }

    public function testInstance (): void
    {
        $this->createInstance();

        $person1 = app()->make(Person::class);
        $person2 = app()->make(Person::class);

        $this->assertSame($person1, $person2);

        $this->assertEquals('jufron', $person1->getFirstName());
        $this->assertEquals('tamo ama', $person1->getLastName());
        $this->assertEquals('jufrontamoama@gmail.com', $person1->getEamil());

        $this->assertEquals('jufron', $person2->getFirstName());
        $this->assertEquals('tamo ama', $person2->getLastName());
        $this->assertEquals('jufrontamoama@gmail.com', $person2->getEamil());

    }

    public function testDependencyInjection3 (): void
    {
        app()->singleton(Foo::class, function ($app) {
            return new Foo();
        });

        $foo = app()->make(Foo::class);
        $bar = app()->make(Bar::class);

        $this->assertEquals('ini foo', $bar->bar());
        $this->assertEquals('ini foo 2', $bar->bar2());

        $this->assertEquals('ini foo', $foo->foo());
        $this->assertEquals('ini foo 2', $foo->foo2());

        $this->assertSame($foo, $bar->getFooFromBar());
    }

    public function testDependencyInjectioonClosure (): void
    {
        // app()->singleton(Foo::class, function ($app) {
            // return $app->make(Foo::class);
            // return new Foo();
        // });

        app()->singleton(Bar::class, function ($app) {
            return new Bar(
                $app->make(Foo::class)
            );
        });

        $bar1 = app()->make(Bar::class);
        $bar2 = app()->make(Bar::class);

        $this->assertSame($bar1, $bar2);

        $this->assertEquals('ini foo', $bar1->bar());
        $this->assertEquals('ini foo 2', $bar1->bar2());
    }

    public function testDependencyInjectioonClosure2 (): void
    {
        app()->singleton(UserRepository::class, function ($app) {
            return new UserRepository(
                $app->make(Database::class),
                $app->make(Log::class)
            );
        });

        app()->singleton(Log::class, function ($app) {
            return new Log('ini data log');
        });

        $userRepository = app()->make(UserRepository::class);

        $this->assertEquals('select * from user', $userRepository->get('user'));
        $this->assertEquals('ini data log', $userRepository->log());
    }

    public function testHelloService (): void
    {
        app()->singleton(HelloService::class, HelloServiceIndonesia::class);
        app()->singleton(HelloService::class, HelloServiceEnglish::class);

        // $helloService = app()->make(HelloService::class);

        // $this->assertEquals('hallo selamat pagi jufron', $helloService->hello('jufron'));

        $indonesia1 = app()->make(HelloServiceIndonesia::class);
        $english1 = app()->make(HelloServiceEnglish::class);

        $this->assertInstanceOf(HelloService::class, $indonesia1);
        $this->assertEquals('hallo selamat pagi jufron', $indonesia1->hello('jufron'));

        $this->assertInstanceOf(HelloService::class, $english1);
        $this->assertEquals('hello god morning mr james', $english1->hello('james'));
    }

    public function testServiceContainerLaravel (): void
    {
        app()->singleton(Bar::class, function ($app) {
            return new Bar($app->make(Foo::class));
        });

        $bar = app()->make(Bar::class);

        $this->assertEquals('ini foo', $bar->bar());
        $this->assertEquals('ini foo 2', $bar->bar2());
    }
}
