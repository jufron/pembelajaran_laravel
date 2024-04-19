<?php


namespace App\Service;

use App\Service\Contract\HelloService;

class HelloServiceEnglish implements HelloService {

    public function hello(string $nama): string
    {
        return "hello god morning mr $nama";
    }
}
