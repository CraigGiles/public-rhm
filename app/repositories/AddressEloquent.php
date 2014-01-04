<?php

class AddressEloquent extends Eloquent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notes';

    /**
     * @param Address $address
     */
    public function setAttributes($address) {
        $this->primaryNumber = $address->getPrimaryNumber();
        $this->streetPredirection = $address->getStreetPredirection();
        $this->streetName = $address->getStreetName();
        $this->streetSuffix = $address->getStreetSuffix();
        $this->suiteType = $address->getSuiteType();
        $this->suiteNumber = $address->getSuiteNumber();
        $this->cityName = $address->getCityName();
        $this->countyName = $address->getCountyName();
        $this->stateAbbreviation = $address->getStateAbbreviation();
        $this->zipCode = $address->getZipcode();
        $this->plus4Code = $address->getPlus4Code();
        $this->longitude = $address->getLongitude();
        $this->latitude = $address->getLatitude();
        $this->cassVerified = $address->getCassVerified();
    }
} 