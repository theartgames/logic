<?php
namespace Artgames\Logic\Expression;
use Artgames\Logic\Expression\TerminalExpression;
use Artgames\Logic\Stack;

class BoolValue extends TerminalExpression{
  public function operate(Stack $stack) {
      return $this->value;
  }
}
