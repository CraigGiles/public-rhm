<?php namespace redhotmayo\dataaccess\repository;

interface ZipcodeRepository extends Repository {
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

    /**
     * Obtain the City, State, County, Zipcode and Population information for the
     * given constraints.
     *
     * NOTE: this function is separate from find() due to the fact that it needs to be
     * a distinct city. With the zipcode database, zipcodes are not unique, however all
     * zipcodes point to a distinct city.
     *
     * @param $array
     * @return mixed
     */
    public function getLocationInformation($array);
}