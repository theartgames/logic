<?php
namespace Artgames\Logic\Expression;
use Artgames\Logic\Stack;

abstract class TerminalExpression{
  protected $value = '';

  public function __construct($value) {
    $this->value = $value;
  }

  public static function factory($value, $rule = null) {
    if (is_object($value) && $value instanceof TerminalExpression) {
      return $value;
    } elseif (strtolower($value) == 'true' || $value === true) {
      return new BoolValue(true);
    } elseif (strtolower($value) == 'false' || $value === false) {
      return new BoolValue(false);
    } elseif (strtolower($value) == 'and') {
      if($rule && ( (string)$rule == "100007" || (string)$rule == "100003" ) ){ //"excludes"/"includes" rule changes AND to OR due to sick database logic
        return new LogicalOr($value);
      } else {
        return new LogicalAnd($value);
      }
    } elseif (strtolower($value) == 'or') {
      return new LogicalOr($value);
    } elseif (strtolower($value) == 'not') {
      return new LogicalNot($value);
    } elseif (in_array($value, array('(', ')'))) {
      return new Parenthesis($value);
    } else {
      return new BoolValue(false);
    }
    throw new \Exception('Undefined Value ' . $value);
  }

  abstract public function operate(Stack $stack);

  public function isOperator() {
    return false;
  }

  public function isParenthesis() {
    return false;
  }

  public function isNoOp() {
    return false;
  }

  public function render() {
    return $this->value;
  }
}
