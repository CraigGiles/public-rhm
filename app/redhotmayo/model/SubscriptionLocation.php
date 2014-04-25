<?php namespace redhotmayo\model;


class SubscriptionLocation {
    private $userId;
    private $city;
    private $county;
    private $state;
    private $zipcode;
    private $population;

    public static function FromStdClass($stdClass) {
        $userId = isset($stdClass->userId) ? $stdClass->userId : null;
        $city = isset($stdClass->city) ? $stdClass->city : null;
        $county = isset($stdClass->county) ? $stdClass->county : null;
        $state = isset($stdClass->state) ? $stdClass->state : null;
        $zipcode = isset($stdClass->ZipCode) ? $stdClass->ZipCode : null;
        $population = isset($stdClass->population) ? $stdClass->population : null;

        $loc = new self;
        $loc->setUserId($userId);
        $loc->setCity($city);
        $loc->setCounty($county);
        $loc->setState($state);
        $loc->setZipcode($zipcode);
        $loc->setPopulation($population);
        return $loc;
    }

    public function __construct() {
    }

    public static function FromArray($data) {
        return self::FromStdClass(json_decode(json_encode($data)));
    }

    /**
     * @param int $userId
     */
    public function setUserId($userId) {
        if (isset($userId)) {
            $this->userId = (int)$userId;
        }
    }

    /**
     * @return int
     */
    public function getUserId() {
        return $this->userId;
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
        if (isset($population)) {
            $this->population = (int)$population;
        }
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
        if (isset($zipcode)) {
            $this->zipcode = (int)$zipcode;
        }
    }

    /**
     * @return int
     */
    public function getZipcode() {
        return $this->zipcode;
    }


}