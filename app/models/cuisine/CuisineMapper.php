<?php

class CuisineMapper {
    const DEFAULT_CUISINE = 'OTHER';

    protected $map;

    public function __construct(FoodMap $map) {
        $this->map = $map->getMap();
    }

    public function mapCuisine($cuisine) {
        $return = self::DEFAULT_CUISINE;
        if (array_key_exists($cuisine, $this->map)) {
            $return = strtoupper($this->map[$cuisine]);
        }
        return $return;
    }
}