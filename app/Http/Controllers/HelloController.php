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

    public function index (Request $request, string $nama): string
    {
        // return $this->goodbyeService->goodbyee($nama);
        // var_dump("full url : ". $request->fullUrl());
        // var_dump("path : " . $request->path());
        // var_dump("url : " . $request->url());
        return $this->goodbyeeServiceIndonesia->goodbyee($nama);
    }

    public function show (): string
    {
        return $this->helloServiceIndonesia->getFirstName();
    }

    public function request (Request $request)
    {
        return
            $request->path()  . PHP_EOL .
            $request->method() . PHP_EOL .
            $request->fullUrl() . PHP_EOL .
            $request->header('accept');
     }
}
