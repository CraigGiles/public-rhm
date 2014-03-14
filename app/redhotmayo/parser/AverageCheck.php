<?php namespace redhotmayo\parser;


class AverageCheck {

    /**
     * Average checks are filled out in several ways:
     * Valid:
     * $15
     * $15 - $30
     *
     * Invalid:
     * N/A
     * Around $15
     * ...
     *
     * If the value is any of the valid numbers, convert it to a straight number by taking the value or
     * the average of the value, and returning it to the caller. If the value is invalid, return a default
     * value of null
     *
     * @param String $value
     *
     * @returns null|double
     */
    public function parse($value) {
        $result = null;

        if (isset($value) && is_string($value)) {
            $values = explode('-', $value);
            if (count($values) == 1) {
                $result = $values[0][0] === '$' ? (float)substr($values[0], 1) : (float)$values[0];
            } else if (count($values) > 1) {
                $first = $values[0][0] === '$' ? (float)substr($values[0], 1) : (float)$values[0];
                $second = $values[0][0] === '$' ? (float)substr($values[1], 1) : (float)$values[1];

                $result = abs($second + $first) / 2;
            }
        }

        return $result;
    }
} 