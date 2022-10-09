<?php
namespace Artgames\Logic\Facades;

use Illuminate\Support\Facades\Facade;

class Logic extends Facade{
  protected static function getFacadeAccessor(){
    return "Logic";
  }
}
?>
