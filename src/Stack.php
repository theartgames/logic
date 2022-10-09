<?php

namespace Artgames\Logic;

use Illuminate\Support\Collection;

class Stack extends Collection{
  protected $items = [];

  public function poke() {
    return end($this->items);
  }
}
