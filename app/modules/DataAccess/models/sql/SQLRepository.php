<?php

class SQLRepository implements Repository {
    private $dao;

    public function __construct($dataAccessObjectLabel) {
        $this->dao = DataAccess::getDAO($dataAccessObjectLabel);
    }

    public function all() {
        throw new Exception("Method not implemented:" . get_class($this) . ":" . __FUNCTION__);
    }

    public function find($id) {
        throw new Exception("Method not implemented:" . get_class($this) . ":" . __FUNCTION__);
    }

    public function create($input) {
        throw new Exception("Method not implemented:" . get_class($this) . ":" . __FUNCTION__);
    }

    /**
     * Save the object to the database returning the ID if successful and null if unsuccessful.
     *
     * @param $object
     * @return int|null ID of the object
     */
    public function save($object) {
        return $this->dao->save($object);
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll($objects) {
        return $this->dao->saveAll($objects);
    }
}