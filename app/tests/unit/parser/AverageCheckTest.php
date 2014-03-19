<?php namespace redhotmayo\parser;

use TestCase;

class AverageCheckTest extends TestCase {
    public function test_two_values_gives_average() {
        $input = '$10-$20';
        $expected = (20-10) / 2;

        $average = new AverageCheck();
        $results = $average->parse($input);
        $this->assertEquals($expected, $results);
    }
}
 