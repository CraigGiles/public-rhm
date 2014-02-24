<?php namespace redhotmayo\dataaccess\repository;

use Illuminate\Support\Facades\DB;
use redhotmayo\model\Cuisine;

interface CuisineRepository {
    /**
     * Map a given cuisine to a specified cuisine type
     *
     * @param $source
     * @param $cuisine
     * @return Cuisine
     */
    public function map($source, $cuisine);
}