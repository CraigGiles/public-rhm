<?php namespace redhotmayo\dataaccess\repository\sql;

use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\dataaccess\repository\MobileDeviceRepository;
use redhotmayo\model\MobileDevice;

class MobileDeviceRepositorySQL implements MobileDeviceRepository {
    const SERVICE = 'redhotmayo\dataaccess\repository\sql\MobileDeviceRepositorySQL';

    /**
     * Return an array of all objects
     *
     * @return array
     */
    public function all() {
        // TODO: Implement all() method.
    }

    /**
     * Return an array of all objects that match the given constraints
     *
     * @param $parameters
     * @return mixed
     */
    public function find($parameters) {
        // TODO: Implement find() method.
    }

    /**
     * Create an object from given input
     *
     * @param $input
     * @return bool
     */
    public function create(MobileDevice $input) {
        $dao = DataAccessObject::GetMobileDevicesDAO();
        $id = $dao->save($input);
        return isset($id);
    }

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $object
     * @return bool
     */
    public function save($object) {
        // TODO: Implement save() method.
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll($objects) {
        // TODO: Implement saveAll() method.
    }

    /**
     * Take an array of database records and convert them to the appropriate objects
     *
     * @param $records
     * @return array
     */
    function convertRecordsToJsonObjects($records) {
        // TODO: Implement convertRecordsToJsonObjects() method.
    }
}