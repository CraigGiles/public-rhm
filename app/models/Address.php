<?php

/**
 * Class Address
 * @package RedHotMayo\Models
 * @author Craig Giles <craig@gilesc.com>
 * @date 12/2013
 */
class Address extends DataObject {
    private $primaryNumber;
    private $streetName;
    private $streetSuffix;
    private $suiteType;
    private $suiteNumber;
    private $cityName;
    private $countyName;
    private $stateAbbreviation;
    private $zipcode;
    private $plus4Code;
    private $streetPredirection;
    private $latitude;
    private $longitude;
    private $cassVerified;
    private $googleGeocoded;
    private $deliveryLine;

    /**
     * @return bool
     */
    public function getGoogleGeocoded() {
        return $this->googleGeocoded;
    }

    /**
     * @param bool $googleGeocoded
     */
    public function setGoogleGeocoded($googleGeocoded) {
        $this->googleGeocoded = $googleGeocoded;
    }

    /**
     * @return string
     */
    public function getStreetPredirection() {
        return $this->streetPredirection;
    }

    /**
     * @param string $primaryPredirection
     */
    public function setStreetPredirection($primaryPredirection) {
        $this->streetPredirection = $primaryPredirection;
    }

    /**
     * @return string
     */
    public function getCityName() {
        return $this->cityName;
    }

    /**
     * @param string $cityName
     */
    public function setCityName($cityName) {
        $this->cityName = $cityName;
    }

    /**
     * @return double
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * @param double $latitude
     */
    public function setLatitude($latitude) {
        $this->latitude = doubleval($latitude);
    }

    /**
     * @return double
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * @param double $longitude
     */
    public function setLongitude($longitude) {
        $this->longitude = doubleval($longitude);
    }

    /**
     * @return string
     */
    public function getPlus4Code() {
        return $this->plus4Code;
    }

    /**
     * @param string $plus4Code
     */
    public function setPlus4Code($plus4Code) {
        $this->plus4Code = $plus4Code;
    }

    /**
     * @return int
     */
    public function getPrimaryNumber() {
        return $this->primaryNumber;
    }

    /**
     * @param int $primaryNumber
     */
    public function setPrimaryNumber($primaryNumber) {
        $this->primaryNumber = intval($primaryNumber);
    }

    /**
     * @return string
     */
    public function getStateAbbreviation() {
        return $this->stateAbbreviation;
    }

    /**
     * @param string $stateAbbreviation
     */
    public function setStateAbbreviation($stateAbbreviation) {
        $this->stateAbbreviation = $stateAbbreviation;
    }

    /**
     * @return string
     */
    public function getStreetName() {
        return $this->streetName;
    }

    /**
     * @param string $streetName
     */
    public function setStreetName($streetName) {
        $this->streetName = $streetName;
    }

    /**
     * @return string
     */
    public function getStreetSuffix() {
        return $this->streetSuffix;
    }

    /**
     * @param string $streetSuffix
     */
    public function setStreetSuffix($streetSuffix) {
        $this->streetSuffix = $streetSuffix;
    }

    /**
     * @return string
     */
    public function getSuiteNumber() {
        return $this->suiteNumber;
    }

    /**
     * @param string $suiteNumber
     */
    public function setSuiteNumber($suiteNumber) {
        $this->suiteNumber = $suiteNumber;
    }

    /**
     * @return string
     */
    public function getSuiteType() {
        return $this->suiteType;
    }

    /**
     * @param string $suiteType
     */
    public function setSuiteType($suiteType) {
        $this->suiteType = $suiteType;
    }

    /**
     * @return int
     */
    public function getZipcode() {
        return $this->zipcode;
    }

    /**
     * @param int $zipcode
     */
    public function setZipcode($zipcode) {
        $this->zipcode = intval($zipcode);
    }

    /**
     * @return mixed
     */
    public function getCountyName() {
        return $this->countyName;
    }

    /**
     * @param mixed $countyName
     */
    public function setCountyName($countyName) {
        $this->countyName = $countyName;
    }

    /**
     * @return bool
     */
    public function getCassVerified() {
        return $this->cassVerified;
    }

    /**
     * @param bool $verified
     */
    public function setCassVerified($verified) {
        $this->cassVerified = $verified;
    }

    public function getDeliveryLine() {
        return $this->deliveryLine;
    }

    public function setDeliveryLine($deliveryLine) {
        $this->deliveryLine = $deliveryLine;
    }

    public function __toString() {
        $return = empty($this->primaryNumber)      ? '' : $this->getPrimaryNumber() . " " ;
        $return .= empty($this->streetPredirection)? '' : $this->getStreetPredirection() . " " ;
        $return .= empty($this->streetName)        ? '' : $this->getStreetName() . " " ;
        $return .= empty($this->streetSuffix)      ? '' : $this->getStreetSuffix() . " " ;
        $return .= empty($this->suiteType)         ? '' : $this->getSuiteType() . " " ;
        $return .= empty($this->suiteNumber)       ? '' : $this->getSuiteNumber() . " " ;
        $return .= empty($this->cityName)          ? '' : $this->getCityName() . " " ;
        $return .= empty($this->stateAbbreviation) ? '' : $this->getStateAbbreviation() . " " ;
        $return .= empty($this->zipcode)           ? '' : $this->getZipcode() . " " ;
        return $return;
    }
}