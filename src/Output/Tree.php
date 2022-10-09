<?php
namespace Artgames\Logic\Output;

class Tree {
  protected $index = 0;
  protected $data;

  public function __construct($data) {
    $this->data = $data;
  }

  public function getTree() {
    return $this->parse();
  }

  protected function parse() {
    $result = ["terms" => []];
    while ($this->index < count($this->data)) {
      switch($this->data[$this->index]) {
        case "(":
          $this->index++;
          $result["terms"][] = $this->parse();
          break;
        case ")":
          $this->index++;
          return $result;
          break;
        case "AND":
          $result["operator"] = "AND";
          $this->index++;
          break;
        case "OR":
          $result["operator"] = "OR";
          $this->index++;
          break;
        default:
          $result["terms"][] = $this->data[$this->index];
          $this->index++;
          break;
      }
    }
    return $result;
  }
}
?>
