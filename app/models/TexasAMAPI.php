<?php


/**
 * Class TexasAMAPI
 * @package RedHotMayo\API
 * @author Craig Giles <craig@gilesc.com>
 * @date 12/2013
 */
class TexasAMAPI implements AddressStandardizationService {
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
     * @return Address
     */
    public function processAddresses($street1, $street2, $city, $county, $state, $zipcode, $maxResponses = null) {
        $address = new Address();

        $street1 = urlencode($street1);
        $city = urlencode($city);
        $state = urlencode($state);
        $zipcode = urlencode($zipcode);
        $key = Config::get('texasam.key');
        $url = "http://geoservices.tamu.edu/Services/AddressNormalization/WebService/v04_01/Rest/?nonParsedStreetAddress={$street1}&nonParsedCity={$city}&nonParsedState={$state}&nonParsedZip={$zipcode}&apikey={$key}&addressFormat=USPSPublication28&responseFormat=JSON&notStore=true&version=4.01";

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