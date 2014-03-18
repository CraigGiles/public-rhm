<?php namespace redhotmayo\dataaccess\repository;

use redhotmayo\model\MobileDevice;

interface MobileDeviceRepository {

    /**
     * Return an array of all objects
     *
     * @return array
     */
    public function all();

    /**
     * Return an array of all objects that match the given constraints
     *
     * @param $parameters
     * @return mixed
     */
    public function find($parameters);

    /**
     * Create an object from given input
     *
     * @param $mobile
     * @return bool
     */
    public function create(MobileDevice $mobile);

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $object
     * @return bool
     */
    public function save($object);

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll($objects);

    /**
     * Take an array of database records and convert them to the appropriate objects
     *
     * @param $records
     * @return array
     */
    function convertRecordsToJsonObjects($records);
}