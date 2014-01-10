<?php

/**
 * Class SmartyStreetsAPI
 *
 * @package RedHotMayo\API
 * @author Craig Giles <craig@gilesc.com>
 * @date 12/2013
 */
class SmartyStreetsAPI implements CassVerificationService {
    /**
     * @param array $addresses
     */
    public function processAddresses($street1, $street2, $city, $county, $state, $zipcode, $maxResponses=null) {
        //process the street until you reach an & symbol.
        $arr = explode("&", $street1, 2);
        $street1 = $arr[0];

        $arr = explode("&", $street2, 2);
        $street2 = $arr[0];

        $authId = Config::get('smartystreets.key');
        $authToken = Config::get('smartystreets.token');

        $street1 = urlencode($street1);
        $street2 = urlencode($street2);
        $city = urlencode($city);
        $state = urlencode($state);
        $zipcode = urlencode($zipcode);
        $maxResponses = urlencode($maxResponses);

        $url = "https://api.smartystreets.com/street-address?auth-id={$authId}&auth-token={$authToken}&street={$street1}&street2={$street2}&city={$city}&state={$state}&zipcode={$zipcode}&candidates={$maxResponses}";

        $ch = curl_init();
        $timeout = 500;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $output = curl_exec($ch);
        curl_close($ch);

        $decoded = json_decode($output);
        $address = null;

        if (count($decoded) > 0) {
            $class = $decoded[0]->components;
            $meta = $decoded[0]->metadata;

            $address = new Address();

            $deliveryLine = isset($decoded[0]->delivery_line_1) ? $decoded[0]->delivery_line_1 : null;
            $primary_number = isset($class->primary_number) ? $class->primary_number : null;
            $street_predirection = isset($class->street_predirection) ? $class->street_predirection : null;
            $street_name = isset($class->street_name) ? $class->street_name : null;
            $street_suffix = isset($class->street_suffix) ? $class->street_suffix : null;
            $city_name = isset($class->city_name) ? $class->city_name : null;
            $county_name = isset($meta->county_name) ? $meta->county_name : null;
            $state_abbreviation = isset($class->state_abbreviation) ? $class->state_abbreviation : null;
            $zipcode = isset($class->zipcode) ? $class->zipcode : null;
            $plus4_code = isset($class->plus4_code) ? $class->plus4_code : null;
            $latitude = isset($meta->latitude) ? $meta->latitude : null;
            $longitude = isset($meta->longitude) ? $meta->longitude : null;
            $secondary_designator = isset($class->secondary_designator) ? $class->secondary_designator : null;
            $secondary_number = isset($class->secondary_number) ? $class->secondary_number : null;

            $address->setPrimaryNumber($primary_number);
            $address->setStreetPredirection($street_predirection);
            $address->setStreetName($street_name);
            $address->setStreetSuffix($street_suffix);
            $address->setCityName($city_name);
            $address->setCountyName($county_name);
            $address->setStateAbbreviation($state_abbreviation);
            $address->setZipcode($zipcode);
            $address->setPlus4Code($plus4_code);
            $address->setLatitude($latitude);
            $address->setLongitude($longitude);
            $address->setSuiteType($secondary_designator);
            $address->setSuiteNumber($secondary_number);
            $address->setDeliveryLine($deliveryLine);

            $address->setCassVerified(true);
        }

        //Address object
        return $address;
    }
}
