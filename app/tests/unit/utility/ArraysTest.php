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

    public function test_it_should_safely_get_values_from_arrays() {
        $test = [
            'key' => 'value',
            'nulled' => null
        ];

        $result = Arrays::GetValue($test, 'key', 'value');
        $this->assertEquals('value', $result);

        $result = Arrays::GetValue($test, 'nulled', 'real');
        $this->assertEquals('real', $result);

        $result = Arrays::GetValue($test, 'nulled', null);
        $this->assertNull($result);

        $result = Arrays::GetValue($test, 'nulled', 3);
        $this->assertEquals(3, $result);
    }
}
