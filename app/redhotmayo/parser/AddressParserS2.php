<?php namespace redhotmayo\parser;

use redhotmayo\api\service\AddressStandardizationService;
use redhotmayo\api\service\CassVerificationService;
use redhotmayo\api\service\Geocoder;

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
        $addressAsArray
    ) {
        $street1 = $addressAsArray[self::C_ADDRESS];
        $city = $addressAsArray[self::C_CITY];
        $county = $addressAsArray[self::C_COUNTY];
        $state = $addressAsArray[self::C_STATE];
        $zipcode = $addressAsArray[self::C_ZIPCODE];

        $sstreetsAddress = $cassVerification->processAddresses($street1, null, $city, $county, $state, $zipcode, self::MAX_CANIDATED);
        if (isset($sstreetsAddress)) {
            $addressAsArray = $sstreetsAddress;
        } else {
            //we couldn't verify the address with a cass service, let's just standardize it
            $addressAsArray = $addrStandardization->processAddresses($street1, null, $city, null, $state, $zipcode);
        }

        //geocode the location
        //TODO: BUG: GoogleMapAPI not working
        $geocoded = $geocoder->geocode($addressAsArray);
        $addressAsArray->setGoogleGeocoded($geocoded);

        return $addressAsArray;
    }
}