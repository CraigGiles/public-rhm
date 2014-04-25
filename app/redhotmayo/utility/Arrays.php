<?php  namespace redhotmayo\utility;

class Arrays {

    /**
     * Takes an array as input and ensures all keys have non-null values
     *
     * @param array $array
     * @return array
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public static function RemoveNullValues(array $array) {
        foreach ($array as $key => $value) {
            if (!isset($value)) {
                unset($array[$key]);
            }
        }

        return $array;
    }

    /**
     * Safely grab the value from the given key of the array. If the key does
     * not exist then return whatever is designated as the default value.
     *
     * @param array $array
     * @param $value
     * @param null $default
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public static function GetValue(array $array, $value, $default=null) {
        return isset($array[$value]) ? $array[$value] : $default;
    }
} 
