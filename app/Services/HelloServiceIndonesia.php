<?php

namespace App\Services;


class HelloServiceIndonesia implements HelloInterface {

  protected string $firstName;
  protected string $email;
  protected string $negara;

  public function __construct(
   string $firstName,
   string $email,
   string $negara
  )
  {
    $this->firstName = $firstName;
    $this->email = $email;
    $this->negara = $negara;
  }

  public function getFirstName (): string
  {
    return $this->firstName;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function getNegara(): string
  {
    return $this->negara;
  }

  public function hello(string $nama): string
  {
    return "hallo $nama";
  }
}
