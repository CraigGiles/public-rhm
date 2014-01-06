<?php

class AddressSQL {
    /**
     * Save a record and return the objectId
     *
     * @param Address $address
     * @return int
     */
    public function save(Address $address) {
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
                'googleGeocoded' => $address->getGoogleGeocoded(),
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s"),
//                'created_at' => new DateTime,
//                'updated_at' => new DateTime,
            )
        );

        $address->setId($id);
        return $id;
    }

    /**
     *  Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $addresses
     * @return int
     */
    public function saveAll(array $addresses) {
        $unsaved = array();
        foreach ($addresses as $address) {
            try {
                $this->save($address);
            } catch (Exception $e) {
                $unsaved[] = $address;
            }
        }

        return $unsaved;
    }

}
