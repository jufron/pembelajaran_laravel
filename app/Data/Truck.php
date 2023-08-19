<?php

namespace App\Data;

class Truck {

  protected Mobil $mobil;
  protected int $roda;

  public function __construct(Mobil $mobil, int $roda)
  {
    $this->mobil = $mobil;
    $this->roda = $roda;
  }

  public function truck () : string
  {
    return $this->mobil->mobil() . ' and truck class';
  }

  public function getMobil () : Mobil
  {
    return $this->mobil;
  }

  public function getRoda (): int
  {
    return $this->roda;
  }

}