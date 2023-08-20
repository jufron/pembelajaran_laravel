<?php

namespace Tests\Feature;

use App\Data\{
    Foo,
    Bar,
    Mobil,
    Truck
};
use App\Providers\FooBarServiceProvider;
// use Illuminate\Contracts\Container\ServiceProvider;

use App\Services\{
    HelloServiceIndonesia
};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_service_provider (): void
    {
        $foo = $this->app->make(Foo::class);
        $bar = $this->app->make(Bar::class);

        $this->assertSame($foo, $bar->getFoo());

        $hello_service_indonesia = $this->app->makeWith(HelloServiceIndonesia::class, [
            'firstName' => 'sinta',
            'email'     => 'sinta@gmail.com',
            'negara'    => 'indonesia'
        ]);
        $this->assertEquals('sinta', $hello_service_indonesia->getFirstName());
        $this->assertEquals('sinta@gmail.com', $hello_service_indonesia->getEamil());
        $this->assertEquals('indonesia', $hello_service_indonesia->getNegara());

        $mobil = $this->app->make(Mobil::class);
        $mobil1 = $this->app->make(Mobil::class);

        $truck = $this->app->make(Truck::class);
        $truck2 = $this->app->make(Truck::class);

        $this->assertSame($mobil, $truck->getMobil());
        $this->assertNotSame($mobil, $truck);
        $this->assertSame($mobil, $mobil1);
        $this->assertSame($truck, $truck2);

        $this->assertEquals('from mobil class and truck class', $truck->truck());
        $this->assertEquals('hidupkan mesin', $truck->getMobil()->hidupkanMesin());
        $this->assertEquals('masukan gigi', $truck->getMobil()->masukanGigi());
        $this->assertEquals('mobil jalan', $truck->getMobil()->jalan());
        $this->assertEquals('mobil berhenti', $truck->getMobil()->berhenti());

    }

    // public function test_eager_or_leazy_foobarserviceprovider (): void
    // {
    //     $myService = new FooBarServiceProvider(app());
    //     $this->assertTrue($myService->isDeferred());
        
    // }
}
