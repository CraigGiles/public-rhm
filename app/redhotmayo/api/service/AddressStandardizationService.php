<?php namespace redhotmayo\api\service;

use redhotmayo\model\Address;

interface AddressStandardizationService {
    /**
     * Standardize the address into a standard form. This does not verify the integrity of the address, however it will
     * ensure the address is in a proper form
     *
     * @param $street1
     * @param $street2
     * @param $city
     * @param $county
     * @param $state
     * @param $zipcode
     * @param null $maxResponses
     *
     * @return Address
     */
    public function processAddresses($street1, $street2, $city, $county, $state, $zipcode, $maxResponses=null);

} 