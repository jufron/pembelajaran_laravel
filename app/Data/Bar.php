<?php

namespace App\Data;

class Bar {

  private Foo $foo;

  public function __construct(Foo $foo)
  {
    $this->foo = $foo;
  }

  public function bar (): string
  {
    return $this->foo->foo() . " and bar class";
  }

  public function getFoo (): Foo
  {
    return $this->foo;
  }
}