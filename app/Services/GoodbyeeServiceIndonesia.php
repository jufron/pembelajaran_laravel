<?php

namespace App\Services;

class GoodbyeeServiceIndonesia implements GoodbyeeServiceInterface {

  public function goodbyee(string $nama): string
  {
    return "good bye $nama";
  }
}