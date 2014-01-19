<?php namespace redhotmayo\dataaccess\repository\dao\sql;

use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use redhotmayo\model\Address;

class AddressSQL {
    const TABLE_NAME = 'addresses';

    const C_ID = 'id';
    const C_PRIMARY_NUMBER = 'primaryNumber';
    const C_STREET_PREDIRECTION = 'streetPredirection';
    const C_STREET_NAME = 'streetName';
    const C_STREET_SUFFIX = 'streetSuffix';
    const C_SUITE_TYPE = 'suiteType';
    const C_SUITE_NUMBER = 'suiteNumber';
    const C_CITY_NAME = 'cityName';
    const C_COUNTY_NAME = 'countyName';
    const C_STATE_ABBREVIATION = 'stateAbbreviation';
    const C_ZIP_CODE = 'zipCode';
    const C_PLUS_4_CODE = 'plus4Code';
    const C_LONGITUDE = 'longitude';
    const C_LATITUDE = 'latitude';
    const C_CASS_VERIFIED = 'cassVerified';
    const C_GOOGLE_GEOCODED = 'googleGeocoded';
    const C_CREATED_AT = 'created_at';
    const C_UPDATED_AT = 'updated_at';

    public static function GetColumns() {
        return array(
            self::TABLE_NAME . '.' . self::C_ID,
            self::TABLE_NAME . '.' . self::C_PRIMARY_NUMBER,
            self::TABLE_NAME . '.' . self::C_STREET_PREDIRECTION,
            self::TABLE_NAME . '.' . self::C_STREET_NAME,
            self::TABLE_NAME . '.' . self::C_STREET_SUFFIX,
            self::TABLE_NAME . '.' . self::C_SUITE_TYPE,
            self::TABLE_NAME . '.' . self::C_SUITE_NUMBER,
            self::TABLE_NAME . '.' . self::C_CITY_NAME,
            self::TABLE_NAME . '.' . self::C_COUNTY_NAME,
            self::TABLE_NAME . '.' . self::C_STATE_ABBREVIATION,
            self::TABLE_NAME . '.' . self::C_ZIP_CODE,
            self::TABLE_NAME . '.' . self::C_PLUS_4_CODE,
            self::TABLE_NAME . '.' . self::C_LONGITUDE,
            self::TABLE_NAME . '.' . self::C_LATITUDE,
            self::TABLE_NAME . '.' . self::C_CASS_VERIFIED,
            self::TABLE_NAME . '.' . self::C_GOOGLE_GEOCODED,
            self::TABLE_NAME . '.' . self::C_CREATED_AT,
            self::TABLE_NAME . '.' . self::C_UPDATED_AT,
        );
    }

    /**
     * Save a record and return the objectId
     *
     * @param Address $address
     * @return int|null
     */
    public function save(Address $address) {
        $id = $address->getAddressId();
        if (!isset($id)) {
            $id = DB::table('addresses')
                    ->insertGetId(array(
                        self::C_PRIMARY_NUMBER => $address->getPrimaryNumber(),
                        self::C_STREET_PREDIRECTION => $address->getStreetPredirection(),
                        self::C_STREET_NAME => $address->getStreetName(),
                        self::C_STREET_SUFFIX => $address->getStreetSuffix(),
                        self::C_SUITE_TYPE => $address->getSuiteType(),
                        self::C_SUITE_NUMBER => $address->getSuiteNumber(),
                        self::C_CITY_NAME => $address->getCityName(),
                        self::C_COUNTY_NAME => $address->getCountyName(),
                        self::C_STATE_ABBREVIATION => $address->getStateAbbreviation(),
                        self::C_ZIP_CODE => $address->getZipcode(),
                        self::C_PLUS_4_CODE => $address->getPlus4Code(),
                        self::C_LONGITUDE => $address->getLongitude(),
                        self::C_LATITUDE => $address->getLatitude(),
                        self::C_CASS_VERIFIED => $address->getCassVerified(),
                        self::C_GOOGLE_GEOCODED => $address->getGoogleGeocoded(),
                        self::C_CREATED_AT => date('Y-m-d H:i:s'),
                        self::C_UPDATED_AT => date('Y-m-d H:i:s'),
                    )
                );
            $address->setAddressId($id);
        }

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
