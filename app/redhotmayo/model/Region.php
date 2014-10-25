<?php namespace redhotmayo\model;

use redhotmayo\utility\Arrays;

class Region {
    const CITY = 'city';
    const COUNTY = 'county';
    const STATE = 'state';
    const TYPE = 'type';

    private $type;
    private $city;
    private $county;
    private $state;

    public static function createWithData(array $data) {
        $type = Arrays::GetValue($data, self::TYPE, "");
        $city = Arrays::GetValue($data, self::CITY, "");
        $county = Arrays::GetValue($data, self::COUNTY, "");
        $state = Arrays::GetValue($data, self::STATE, "");

        return new self($type, $city, $county, $state);
    }

    public function __construct($type, $city, $county, $state) {
        $this->type = isset($type) ? $type : "";
        $this->city = isset($city) ? $city : "";
        $this->county = isset($county) ? $county : "";
        $this->state = isset($state) ? $state : "";
    }

    public function getType() {
        return $this->type;
    }

    public function getCity() {
        return $this->city;
    }

    public function getCounty() {
        return $this->county;
    }

    public function getState() {
        return $this->state;
    }
} 