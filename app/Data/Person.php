<?php

namespace App\Data;

class Person {

    private $firstName;
    private $lastName;
    private $email;

    public function __construct($firstName, $lastName, $email)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
    }

    public function getFirstName () : string
    {
        return $this->firstName;
    }

    public function getLastName (): string
    {
        return $this->lastName;
    }

    public function getEamil (): string
    {
        return $this->email;
    }
}
