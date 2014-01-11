<?php namespace redhotmayo\api\service;

use redhotmayo\model\Address;

interface CassVerificationService {

    /**
     * Process an address with a CASS verification service to ensure the address is proper and standardized
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