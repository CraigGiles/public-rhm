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
} 
