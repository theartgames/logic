<?php

namespace Artgames\Logic;
use Illuminate\Support\ServiceProvider;

class LogicServiceProvider extends ServiceProvider {
  /**
  * Perform post-registration booting of services.
  *
  * @return void
  */
  public function boot() {
    //
  }

  /**
  * Register any package services.
  *
  * @return void
  */
  public function register() {
    //register facade @Logic and bind to class @LogicConverter
    $this->app->bind('Logic', function(){
      return new LogicConverter;
    });
  }
}
