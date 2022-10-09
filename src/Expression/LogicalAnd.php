<?php
namespace Artgames\Logic\Expression;
use Artgames\Logic\Expression\Operator;
use Artgames\Logic\Stack;

class LogicalAnd extends Operator{
  protected $precidence = 4;
  public function operate(Stack $stack) {
      $left = $stack->pop()->operate($stack);
      $right = $stack->pop()->operate($stack);
      return $left && $right;
  }
}
