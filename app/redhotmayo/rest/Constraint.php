<?php namespace redhotmayo\rest;

class Constraint {
    private $column;
    private $operator;
    private $value;

    function __construct($column, $operator, $value) {
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getColumn() {
        return $this->column;
    }

    /**
     * @return mixed
     */
    public function getOperator() {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

}