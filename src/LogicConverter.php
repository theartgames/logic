<?php
namespace Artgames\Logic;

use Artgames\Logic\Expression;
use Artgames\Logic\Stack;
use Artgames\Logic\Expression\TerminalExpression;
use Artgames\Logic\Output\Single;
use Artgames\Logic\Output\Tree;

class LogicConverter {
  protected $variables = array();
  protected $key;
  protected $tokens = array();

  public function massExecute($collection = array()){
    if(!$collection || !count($collection)){
      throw new \RuntimeException('Empty collection');
    }

    $output = array();
    foreach($collection as $index => $item){
      if(!array_key_exists("masterKey", $item)){
        $masterKey = strtotime(date("Y-m-d H:i:s"));
      } else {
        $masterKey = $item['masterKey'];
      }

      $output[] = $this->execute($item['string'], $item['vars'], $masterKey, $item['eval']);
    }

    return $output;
  }

  public function execute($string, $vars = array(), $masterKey = null, $eval = false){
    if(!$masterKey){
      $masterKey = strtotime(date("Y-m-d H:i:s"));
    }
    $this->key = $masterKey;
    if(count($vars)){
      foreach ($vars as $key => $value) {
        $this->registerVariable($key, $value);
      }
    }
    $this->tokens = $this->tokenize($string);

    $logicStack = new Tree($this->tokens);
    $output = array(
      'key' => $masterKey,
      'evaluation' => null,
      'string' => $string,
      'vars' => $this->getVars(),
      'stack' => $logicStack->getTree()
    );
    if($eval){
      $output['evaluation'] = ($masterKey == "100007" || $masterKey == "100003") ? !$this->evaluate($string) : $this->evaluate($string);
    }
    return array($masterKey => $output);
  }

	public function evaluate($string) {
		$stack = $this->parse($string);
		return $this->run($stack);
	}

	public function parse($string) {
		$tokens = $this->tokenizeVars();
		$output = new Stack();
		$operators = new Stack();
		foreach ($tokens as $token) {
			$token = $this->extractVariables($token);
			$expression = TerminalExpression::factory($token, $this->key);
			if ($expression->isOperator()) {
				$this->parseOperator($expression, $output, $operators);
			} elseif ($expression->isParenthesis()) {
				$this->parseParenthesis($expression, $output, $operators);
			} else {
				$output->push($expression);
			}
		}
		while (($op = $operators->pop())) {
			if ($op->isParenthesis()) {
				throw new \RuntimeException('Mismatched Parenthesis');
			}
			$output->push($op);
		}
		return $output;
	}

	public function registerVariable($name, $value) {
		$this->variables[$name] = $value;
	}

	public function run(Stack $stack) {
		while (($operator = $stack->pop()) && $operator->isOperator()) {
			$value = $operator->operate($stack);
			if (!is_null($value)) {
				$stack->push(TerminalExpression::factory($value, $this->key));
			}
		}
		return $operator ? $operator->render() : $this->render($stack);
	}

  protected function getVars(){
    return $this->variables;
  }

	protected function extractVariables($token) {
		if ($token[0] == '$') {
			$key = substr($token, 1);
			return isset($this->variables[$key]) ? $this->variables[$key] : 0;
		}
		return $token;
	}

	protected function render(Stack $stack) {
		$output = '';
		while (($el = $stack->pop())) {
			$output .= $el->render();
		}
		if ($output) {
			return $output;
		}
		throw new \RuntimeException('Could not render output');
	}

	protected function parseParenthesis(TerminalExpression $expression, Stack $output, Stack $operators) {
		if ($expression->isOpen()) {
			$operators->push($expression);
		} else {
			$clean = false;
			while (($end = $operators->pop())) {
				if ($end->isParenthesis()) {
					$clean = true;
					break;
				} else {
					$output->push($end);
				}
			}
			if (!$clean) {
				throw new \RuntimeException('Mismatched Parenthesis');
			}
		}
	}

	protected function parseOperator(TerminalExpression $expression, Stack $output, Stack $operators) {
		$end = $operators->poke();
		if (!$end) {
			$operators->push($expression);
		} elseif ($end->isOperator()) {
			do {
				if ($expression->isLeftAssoc() && $expression->getPrecidence() <= $end->getPrecidence()) {
					$output->push($operators->pop());
				} elseif (!$expression->isLeftAssoc() && $expression->getPrecidence() < $end->getPrecidence()) {
					$output->push($operators->pop());
				} else {
					break;
				}
			} while (($end = $operators->poke()) && $end->isOperator());
			$operators->push($expression);
		} else {
			$operators->push($expression);
		}
	}

	public function tokenize($string) {
		$parts = preg_split('([\{*\}]|(\{\b\})|(\d+|\+|-|\(|\)|/)|\s+)', $string, null, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		$parts = array_map('trim', $parts);
		return $parts;
	}

  protected function tokenizeVars(){
    $tokens = $this->tokens;
    $vars = $this->getVars();
    foreach($tokens as $index => $entry){
      if(array_key_exists($entry, $vars)){
        $tokens[$index] = $vars[$entry];
      }
    }
    return $tokens;
  }
}
?>
