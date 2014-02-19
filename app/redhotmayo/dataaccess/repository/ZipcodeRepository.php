<?php namespace redhotmayo\dataaccess\repository;

interface ZipcodeRepository {

    /**
     * Obtain a list of zipcodes for the given city
     *
     * @param string $city
     * @return array
     */
    public function getZipcodesFromCity($city);

    /**
     * Obtain a list of zipcodes for the given county
     *
     * @param $county
     * @return array
     */
    public function getZipcodesFromCounty($county);
}