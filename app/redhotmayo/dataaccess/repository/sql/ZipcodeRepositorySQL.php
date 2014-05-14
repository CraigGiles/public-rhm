<?php namespace redhotmayo\dataaccess\repository\sql;


use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\exceptions\NotSupportedException;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\utility\Arrays;

class ZipcodeRepositorySQL extends RepositorySQL implements ZipcodeRepository {
    const SERVICE = 'redhotmayo\dataaccess\repository\sql\ZipcodeRepositorySQL';

    const TABLE_NAME = 'ZIPCodes';

    const C_ID = 'id';
    const C_CITY = 'city';
    const C_STATE = 'state';
    const C_COUNTY = 'county';
    const C_ZIPCODE = 'ZipCode';
    const C_POPULATION = 'population';

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $object
     * @throws \redhotmayo\dataaccess\exceptions\NotSupportedException
     * @return bool
     */
    public function save($object) {
        throw new NotSupportedException("Unable to save to the zipcode database");
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param array $objects
     * @throws NotSupportedException
     * @return array
     */
    public function saveAll(array $objects) {
        throw new NotSupportedException("Unable to save to the zipcode database");
    }

    /**
     * Obtain the City, State, County, Zipcode and Population information for the
     * given constraints.
     *
     * NOTE: this function is separate from find() due to the fact that it needs to be
     * a distinct city. With the zipcode database, zipcodes are not unique, however all
     * zipcodes point to a distinct city.
     *
     * @param $parameters
     * @return mixed
     */
    public function getLocationInformation($parameters) {
        $constraints = $this->getConstraints($parameters);

        $builder = DB::table($this->getTableName())
                     ->select($this->getColumns())
                     ->distinct();

        foreach($constraints as $constraint => $value) {
            $builder->where($constraint, '=', $value);
        }

        return $builder->first();
    }

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

        $state = Arrays::GetValue($conditions, self::C_STATE, null);

        if (isset($state)) {
            $values = DB::table(self::TABLE_NAME)
                        ->select(self::C_COUNTY)
                        ->distinct()
                        ->whereRaw('state=?', [$state])
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
     * @param $state
     * @return array
     */
    public function getZipcodesFromCity($city, $state) {
        if (!isset($city) || !isset($state)) {
            return [];
        }

        $zipcodes = [];

        $values = DB::table(self::TABLE_NAME)
            ->select(self::C_ZIPCODE)
            ->where(self::C_CITY, 'like', $city)
            ->whereRaw('state=?', [$state])
            ->get();

        foreach($values as $value) {
            if (isset($value->ZipCode)) { $zipcodes[] = $value->ZipCode; }
        }

        return $zipcodes;
    }

    public function getTableName() {
        return self::TABLE_NAME;
    }

    public function getColumns() {
        return [
            self::TABLE_NAME . '.' . self::C_CITY,
            self::TABLE_NAME . '.' . self::C_STATE,
            self::TABLE_NAME . '.' . self::C_COUNTY,
            self::TABLE_NAME . '.' . self::C_ZIPCODE,
            self::TABLE_NAME . '.' . self::C_POPULATION,
        ];
    }

    protected function getConstraints($parameters) {
        $constraints = [];

        if (isset($parameters[self::C_ZIPCODE])) {
            $constraints[self::C_ZIPCODE] = $parameters[self::C_ZIPCODE];
        }

        if (isset($parameters[self::C_CITY])) {
            $constraints[self::C_CITY] = $parameters[self::C_CITY];
        }

        return $constraints;
    }

    /**
     * Obtain a list of zipcodes for the given county
     *
     * @param string $county
     * @param string $state
     * @return array
     */
    public function getZipcodesFromCounty($county, $state) {
        if (!isset($county) || !isset($state)) {
            return [];
        }

        $zipcodes = [];
        $values = DB::table(self::TABLE_NAME)
                    ->select(self::C_ZIPCODE)
                    ->where(self::C_COUNTY, 'like', $county)
                    ->whereRaw('state=?', [$state])
                    ->get();

        foreach($values as $value) {
            if (isset($value->ZipCode)) { $zipcodes[] = $value->ZipCode; }
        }

        return $zipcodes;
    }

    /**
     * Prepares all values returned from the database to a format which can be
     * consumed by the application. Encrypted values will be unencrypted
     * prior to conversion.
     *
     * @param $values
     * @return mixed
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    protected function filter($values) {
        return $values;
    }
}
