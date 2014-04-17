<?php namespace redhotmayo\utility;

use RedHotMayoTestCase;

class ArraysTest extends RedHotMayoTestCase {
    public function test_it_should_remove_null_values_from_array() {
        $test = [
            'key' => 'value',
            'nulled' => null
        ];

        $result = Arrays::RemoveNullValues($test);
        $this->assertEquals(1, count($result));
    }
}
