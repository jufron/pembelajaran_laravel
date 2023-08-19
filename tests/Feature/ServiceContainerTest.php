<?php

namespace Tests\Feature;

use App\Data\{
    Bar,
    Foo,
    Person,
    Mobil,
    Truck
};
use App\Services\HelloInterface;
use App\Services\helloServiceIndonesia;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Contracts\Foundation\Application;


class ServiceContainerTest extends TestCase
{
    public function test_service_container (): void
    {
        $foo = app()->make(Foo::class);
        $foo2 = app()->make(Foo::class);
        
        $bar = app()->make(Bar::class);

        $this->assertEquals('foo class', $foo->foo());
        $this->assertEquals('foo class', $foo2->foo());
        $this->assertNotSame($foo, $foo2);
    }

    public function test_bind (): void
    {
        app()->bind(Person::class, function ($app) {
            return new Person('jufron', 'tamo ama');
        });

        $person1 = app()->make(Person::class);
        $person2 = app()->make(Person::class);
        $person3 = app()->make(Person::class);

        $this->assertEquals('jufron', $person1->firstName);
        $this->assertEquals('jufron', $person2->firstName);
        $this->assertEquals('jufron', $person3->firstName);

        $this->assertNotSame($person1, $person2);
        $this->assertEquals($person2, $person3);
        $this->assertEquals($person1, $person3);
    }

    public function test_singleton (): void
    {
        // * dengan menggunakan singleton object akan dibuat sekali saja

        app()->singleton(Person::class, function ($app) {
            return new Person('jufron', 'tamo ama');
        });

        $person1 = app()->make(Person::class);  // new Person
        $person2 = app()->make(Person::class);  // return excisting

        $this->assertEquals('jufron', $person1->firstName);
        $this->assertEquals('tamo ama', $person1->lastName);

        $this->assertEquals('jufron', $person2->firstName);
        $this->assertEquals('tamo ama', $person2->lastName);

        $this->assertSame($person1, $person2);
    }

    public function test_instance (): void 
    {
        // * cara lain selain menggunakan singleton 

        $params = new Person('jufron', 'tamo ama');
        app()->instance(Person::class, $params);

        $person1 = app()->make(Person::class);
        $person2 = app()->make(Person::class);

        $this->assertEquals('jufron', $person1->firstName);
        $this->assertEquals('tamo ama', $person1->lastName);

        $this->assertEquals('jufron', $person2->firstName);
        $this->assertEquals('tamo ama', $person2->lastName);

        $this->assertSame($person1, $person2);
    }

    public function test_dependency_injection_using_service_container (): void
    {
        //* foo dibuat sekali selanjutnya digunakan saja
        app()->singleton(Foo::class, function (Application $app) {
            return new Foo();
        });

        app()->bind(Bar::class, function (Application $app) {
            return new Bar($app->make(Foo::class));
        });
        
        $foo = app()->make(Foo::class);
        $foo1 = app()->make(Foo::class);

        $bar = app()->make(Bar::class);
        $bar1 = app()->make(Bar::class);

        $this->assertEquals('foo class and bar class', $bar->bar());
        $this->assertSame($foo, $bar->getFoo());

        $this->assertSame($foo, $foo1);
        // $this->assertSame($bar, $bar1);
    }

    public function test_dependency_injection_using_service_container2 (): void
    {
        app()->singleton(Mobil::class, function (Application $app) {
            return new Mobil();
        });

        app()->bind(Truck::class, function (Application $app) {
            return new Truck($app->make(Mobil::class), 4);
        });

        $mobil = app()->make(Mobil::class);
        $mobil1 = app()->make(Mobil::class);

        $truck = app()->make(Truck::class);

        $this->assertSame($mobil, $mobil1);
        $this->assertEquals('from mobil class and truck class', $truck->truck());
        $this->assertEquals('hidupkan mesin', $truck->getMobil()->hidupkanMesin());
        $this->assertEquals('masukan gigi', $truck->getMobil()->masukanGigi());
        $this->assertEquals('mobil jalan', $truck->getMobil()->jalan());
        $this->assertEquals('mobil berhenti', $truck->getMobil()->berhenti());
    }

    public function test_hello_service_indonesia (): void
    {
        //* cara sederhana jika object tidak komplex

        $this->app->singleton(HelloInterface::class, HelloInterfaceIndonesia::class);
        $this->app->singletonIf(helloServiceIndonesia::class, function () {
            return new helloServiceIndonesia('jufron', 'jufron@gmail.com', 'indonesia');
        });
     
        $helloServiceIndonesia = $this->app->make(helloServiceIndonesia::class);

        $firstName = $helloServiceIndonesia->getFirstName();
        $email = $helloServiceIndonesia->getEamil();
        $negara = $helloServiceIndonesia->getNegara();

        $this->assertEquals('jufron', $firstName);
        $this->assertEquals('jufron@gmail.com', $email);
        $this->assertEquals('indonesia', $negara);

        $this->assertEquals('hallo james', $helloServiceIndonesia->hello('james'));
    }
}
