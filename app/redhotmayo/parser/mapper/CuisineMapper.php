<?php namespace redhotmayo\parser\mapper;

interface CuisineMapper {

    /**
     * Obtain the cuisine type and meta data mapped to this value
     *
     * @param $value
     * @return array
     */
    public function map($value);
} 