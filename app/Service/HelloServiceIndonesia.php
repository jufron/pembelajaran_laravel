<?php


namespace App\Service;

use App\Service\Contract\HelloService;

class HelloServiceIndonesia implements HelloService {

    public function hello(string $nama): string
    {
        return "hallo selamat pagi $nama";
    }

}
