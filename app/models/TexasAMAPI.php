<?php


/**
 * Class TexasAMAPI
 * @package RedHotMayo\API
 * @author Craig Giles <craig@gilesc.com>
 * @date 12/2013
 */
class TexasAMAPI {
    public function standardizeAddress($streetAddress, $city, $state, $zipcode) {
        $address = new Address();

        $streetAddress = urlencode($streetAddress);
        $city = urlencode($city);
        $state = urlencode($state);
        $zipcode = urlencode($zipcode);
        $key = Config::get('texasam.key');
        $url = "http://geoservices.tamu.edu/Services/AddressNormalization/WebService/v04_01/Rest/?nonParsedStreetAddress={$streetAddress}&nonParsedCity={$city}&nonParsedState={$state}&nonParsedZip={$zipcode}&apikey={$key}&addressFormat=USPSPublication28&responseFormat=JSON&notStore=true&version=4.01";

        $ch = curl_init();
        $timeout = 500;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $output = curl_exec($ch);
        curl_close($ch);

        $output = json_decode($output);

        if (isset($output->QueryStatusCode) && $output->QueryStatusCode == 'Success') {
            $array = $output->StreetAddresses;

            if (count($array) > 0) {
                $class = $array[0];
                $address->setPrimaryNumber($class->Number);
                $street = (!empty($class->PreDirectional) ? $class->PreDirectional . " " . $class->StreetName : $class->StreetName);
                $address->setStreetName($street);
                $address->setCityName($class->City);
                $address->setStateAbbreviation($class->State);
                $address->setZipcode($class->ZIP);
                $address->setSuiteType($class->SuiteType);
                $address->setSuiteNumber($class->SuiteNumber);
            }
        }

        // Since TexasA&M doesn't verify addresses, it only standardizes them.
        $address->setCassVerified(false);

        return $address;
    }
}