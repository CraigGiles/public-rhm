<?php namespace redhotmayo\dataaccess\repository;

use Illuminate\Support\Facades\DB;
use redhotmayo\model\Cuisine;

class CuisineRepository {
    const CUISINE_TABLE = 'cuisines';

    public function map($cuisine) {
        //obtain info from database
        $data = DB::table(self::CUISINE_TABLE)
            ->where('s2', '=', $cuisine)
            ->first();

        dd($data);
        //create Cuisine object from information
        //return cuisine object

        $cuisine = new Cuisine();
    }
} 