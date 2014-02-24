<?php namespace redhotmayo\dataaccess\repository\sql;


use Illuminate\Support\Facades\DB;
use redhotmayo\model\Cuisine;

class CuisineRepositorySQL {
    const CUISINE_TABLE = 'cuisines';
    const SERVICE = 'redhotmayo\dataaccess\repository\sql\CuisineRepositorySQL';

    private $validSources = [ 's2' ];

    public function map($source, $cuisine) {
        if (in_array($source, $this->validSources)) {
            $data = (array)DB::table(self::CUISINE_TABLE)
                      ->where($source, '=', $cuisine)
                      ->first();

            $id = isset($data['id']) ? $data['id'] : null;
            $cuisine = isset($data['cuisine']) ? $data['cuisine'] : null;
            $meta = isset($data['meta']) ? $data['meta'] : null;

            $cuisine = new Cuisine($cuisine, $meta);
            $cuisine->setCuisineId($id);
            return $cuisine;
        } else {
            throw new \InvalidArgumentException("{$source} is not a valid cuisine source.");
        }
    }
}