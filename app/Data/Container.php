<?php


namespace App\Data;

use Exception;

class Container {

    private $services = [];

    public function bind ($name, $callback)
    {
        $this->services[$name] = $callback;
    }

    public function make ($name)
    {
        if (isset($this->services[$name])) {
            $callback = $this->services[$name];
            return $callback($this);
        } else {
            throw new Exception("service $name not found");
        }
    }
}

$container = new Container();

$container->bind('database', function ($container) {
    return new Database();
});

$database = $container->make('database');
