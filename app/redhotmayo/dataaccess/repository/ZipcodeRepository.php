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
     * Obtain a list of all cities for the given state
     *
     * @param $conditions
     * @return array
     */
    public function getAllCities($conditions);

    /**
     * Obtain a list of all counties for the given state
     *
     * @param $conditions
     * @return array
     */
    public function getAllCounties($conditions);
}