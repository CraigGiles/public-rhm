<?php namespace redhotmayo\dataaccess\repository\sql;


use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\ZipcodeRepository;

class ZipcodeRepositorySQL implements ZipcodeRepository {
    const SERVICE = 'redhotmayo\dataaccess\repository\sql\ZipcodeRepositorySQL';

    const TABLE_NAME = 'ZIPCodes';
    const C_CITY = 'city';
    const C_STATE = 'state';
    const C_COUNTY = 'county';
    const C_ZIPCODE = 'ZipCode';
    const C_POPULATION = 'population';

    /**
     * Obtain a list of all cities for the given state
     *
     * @param $conditions
     * @return array
     */
    public function getAllCities($conditions) {
        $cities = [];

        if (isset($conditions['state'])) {
            $values = DB::table(self::TABLE_NAME)
                        ->select(self::C_CITY)
                        ->distinct()
                        ->whereRaw('state=?', [$conditions['state']])
                        ->get();

            foreach ($values as $value) {
                $cities[] = $value->city;
            }
        }

        return $cities;
    }

    /**
     * Obtain a list of all counties for the given state
     *
     * @param $conditions
     * @return array
     */
    public function getAllCounties($conditions) {
        $counties = [];

        if (isset($conditions['state'])) {
            $values = DB::table(self::TABLE_NAME)
                        ->select(self::C_COUNTY)
                        ->distinct()
                        ->whereRaw('state=?', [$conditions['state']])
                        ->get();

            foreach ($values as $value) {
                $counties[] = $value->county;
            }
        }

        return $counties;
    }

    /**
     * Obtain a list of zipcodes for the given city
     *
     * @param string $city
     * @return array
     */
    public function getZipcodesFromCity($city) {
        $zipcodes = [];
        $values = DB::table(self::TABLE_NAME)
            ->select(self::C_ZIPCODE)
            ->where(self::C_CITY, 'like', $city)
            ->get();

        foreach($values as $value) {
            if (isset($value->ZipCode)) { $zipcodes[] = $value->ZipCode; }
        }

        return $zipcodes;
    }
}