<?php

class SQLRepository implements Repository {
    /** @var Illuminate\Database\Connection */
    protected $db;

    protected $dao;

    public function __construct($dao, $db=null) {
        //if DB isn't set, use the laravel 4 facade
        if (!isset($db)) {
            $this->db = DB;
        } else {
            $this->db = $db;
        }
        $this->dao = $dao;
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