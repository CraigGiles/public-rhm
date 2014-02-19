<?php namespace redhotmayo\dataaccess\repository\sql;


use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\ZipcodeRepository;

class ZipcodeRepositorySQL implements ZipcodeRepository {
    const SERVICE = 'redhotmayo\dataaccess\repository\sql\ZipcodeRepositorySQL';

    const TABLE_NAME = 'zipcodes';
    const C_ZIPCODE = 'ZipCode';
    const C_COUNTY = 'county';
    const C_CITY = 'city';

    /**
     * Obtain a list of zipcodes for the given city
     *
     * @param string $city
     * @return array
     */
    public function getZipcodesFromCity($city) {
        $zipcodes = [];

        if (isset($city) && !empty($city)) {
            $values = DB::table(self::TABLE_NAME)
                        ->select(self::C_ZIPCODE)
                        ->where(self::C_CITY, 'like', $city)
                        ->get();

            foreach($values as $value) {
                if (isset($value->ZipCode)) { $zipcodes[] = $value->ZipCode; }
            }
        }

        return $zipcodes;
    }

    /**
     * Obtain a list of zipcodes for the given county
     *
     * @param $county
     * @return array
     */
    public function getZipcodesFromCounty($county) {
        $zipcodes = [];

        if (isset($county) && !empty($county)) {
            $values = DB::table(self::TABLE_NAME)
                        ->select(self::C_ZIPCODE)
                        ->where(self::C_COUNTY, 'like', $county)
                        ->get();

            foreach($values as $value) {
                if (isset($value->ZipCode)) { $zipcodes[] = $value->ZipCode; }
            }
        }

        return $zipcodes;
    }
}