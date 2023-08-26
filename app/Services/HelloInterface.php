<?php

namespace App\Services;

interface HelloInterface {

  public function hello (string $nama): string;

  public function getFirstName(): string;

  public function getEmail(): string;

  public function getNegara(): string;

}
