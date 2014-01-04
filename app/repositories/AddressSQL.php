<?php

class AddressSQL {
    /**
     * @param Address $address
     */
    public function save($address) {
        $id = DB::table('addresses')
                 ->insertGetId(array(
                'primaryNumber' => $address->getPrimaryNumber(),
                'streetPredirection' => $address->getStreetPredirection(),
                'streetName' => $address->getStreetName(),
                'streetSuffix' => $address->getStreetSuffix(),
                'suiteType' => $address->getSuiteType(),
                'suiteNumber' => $address->getSuiteNumber(),
                'cityName' => $address->getCityName(),
                'countyName' => $address->getCountyName(),
                'stateAbbreviation' => $address->getStateAbbreviation(),
                'zipCode' => $address->getZipcode(),
                'plus4Code' => $address->getPlus4Code(),
                'longitude' => $address->getLongitude(),
                'latitude' => $address->getLatitude(),
                'cassVerified' => $address->getCassVerified(),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
            )
        );

        $address->setId($id);
        return $id;
    }
} 