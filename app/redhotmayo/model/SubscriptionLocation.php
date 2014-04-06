<?php namespace redhotmayo\model;


class SubscriptionLocation {
    private $city;
    private $county;
    private $state;
    private $zipcode;
    private $population;

    public static function FromStdClass($stdClass) {
        $city = isset($stdClass->city) ? $stdClass->city : null;
        $county = isset($stdClass->county) ? $stdClass->county : null;
        $state = isset($stdClass->state) ? $stdClass->state : null;
        $zipcode = isset($stdClass->ZipCode) ? $stdClass->ZipCode : null;
        $population = isset($stdClass->population) ? $stdClass->population : null;

        $loc = new self;
        $loc->setCity($city);
        $loc->setCounty($county);
        $loc->setState($state);
        $loc->setZipcode($zipcode);
        $loc->setPopulation($population);
        return $loc;
    }

    public function __construct() {
    }

    /**
     * @param mixed $city
     */
    public function setCity($city) {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCity() {
        return $this->city;
    }

    /**
     * @param mixed $county
     */
    public function setCounty($county) {
        $this->county = $county;
    }

    /**
     * @return mixed
     */
    public function getCounty() {
        return $this->county;
    }

    /**
     * @param int $population
     */
    public function setPopulation($population) {
        $this->population = (int)$population;
    }

    /**
     * @return int
     */
    public function getPopulation() {
        return $this->population;
    }

    /**
     * @param mixed $state
     */
    public function setState($state) {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @param int $zipcode
     */
    public function setZipcode($zipcode) {
        $this->zipcode = (int)$zipcode;
    }

    /**
     * @return int
     */
    public function getZipcode() {
        return $this->zipcode;
    }


}