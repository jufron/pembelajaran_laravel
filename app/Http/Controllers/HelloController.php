<?php

namespace App\Http\Controllers;

use App\Services\GoodbyeeServiceIndonesia;
use App\Services\GoodbyeeServiceInterface;
use App\Services\HelloInterface;
use App\Services\HelloServiceIndonesia;
use Illuminate\Http\Request;

class HelloController extends Controller
{
     protected HelloServiceIndonesia $helloServiceIndonesia;

    public function __construct(
        protected GoodbyeeServiceIndonesia $goodbyeeServiceIndonesia,
       )
    {
        $this->helloServiceIndonesia = app()->makeWith(HelloServiceIndonesia::class, [
            'firstName' => 'sinta',
            'email'     => 'sinta@gmail.com',
            'negara'    => 'indonesia'
        ]);
    }

    public function index (string $nama): string
    {
        // return $this->goodbyeService->goodbyee($nama);
        return $this->goodbyeeServiceIndonesia->goodbyee($nama);
    }

    public function show (): string
    {
        return $this->helloServiceIndonesia->getFirstName();
    }
}
