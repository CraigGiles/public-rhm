<?php

class AddressParserS2 {
    const C_ADDRESS = 'ADDRESS';
    const C_CITY = 'CITY';
    const C_COUNTY = 'COUNTY';
    const C_STATE = 'STATE';
    const C_ZIPCODE = 'ZIPCODE';
    const MAX_CANIDATED = 10;

    public function processAddressInformation(
        AddressStandardizationService $addrStandardization,
        CassVerificationService $cassVerification,
        Geocoder $geocoder,
        $array
    ) {
        $street1 = $array[self::C_ADDRESS];
        $city = $array[self::C_CITY];
        $county = $array[self::C_COUNTY];
        $state = $array[self::C_STATE];
        $zipcode = $array[self::C_ZIPCODE];

        $sstreetsAddress = $cassVerification->processAddresses($street1, null, $city, $county, $state, $zipcode, self::MAX_CANIDATED);
        if (isset($sstreetsAddress)) {
            $address = $sstreetsAddress;
        } else {
            //we couldn't verify the address with a cass service, let's just standardize it
            $address = $addrStandardization->processAddresses($street1, null, $city, null, $state, $zipcode);
        }

        //geocode the location
        //TODO: BUG: GoogleMapAPI not working
        $geocoded = $geocoder->geocode($address);
        $address->setGoogleGeocoded($geocoded);

        return $address;
    }
}