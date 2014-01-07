<?php


/**
 * Class GoogleMapsAPI
 * @package RedHotMayo\API
 * @author Craig Giles <craig@gilesc.com>
 */
class GoogleMapsAPI implements Geocoder {

    /**
     * Takes in an address value object and calls an external API source in order to attempt a geocoding
     * the address into lat/lon values. If successful the addresses will have a valid lat/lon and true
     * will be returned. Otherwise, the addresses lat/lon will remain untouched and false will be returned
     *
     * @param Address $address
     * @return bool
     */
    public function geocode(Address $address) {
        $key = Config::get('google.key');
        $unit = $address->getPrimaryNumber();
        $street = $address->getStreetName();
        $city = $address->getCityName();
        $state = $address->getStateAbbreviation();
        $addr = $unit . "+" . $street . "+" . $city . "+" . $state;

        $url = "http://maps.googleapis.com/maps/api/geocode/json?address={$addr}&sensor=false";//&key={$key}";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);

        // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
        if ($response['status'] != 'OK') {
            return false;
        }

        $geometry = $response['results'][0]['geometry'];

        $latitude = $geometry['location']['lat'];
        $longitude = $geometry['location']['lng'];

        $address->setLatitude($latitude);
        $address->setLongitude($longitude);

        return true;
    }
}
