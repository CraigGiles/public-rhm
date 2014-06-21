<?php namespace redhotmayo\dataaccess\repository;

interface ZipcodeRepository extends Repository {
    /**
     * Obtain a list of zipcodes for the given city
     *
     * @param string $city
     * @param $state
     * @return array
     */
    public function getZipcodesFromCity($city, $state);

    /**
     * Obtain a list of zipcodes for the given county
     *
     * @param string $county
     * @param string $state
     * @return array
     */
    public function getZipcodesFromCounty($county, $state);

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

    /**
     * @param array $zipcodes
     * @return int
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPopulationForZipcodes(array $zipcodes);

}
