<?php namespace redhotmayo\dataaccess\repository\sql;


use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\dataaccess\repository\UserRepository;

class UserRepositorySQL implements UserRepository {

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
     * @param $search
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
     * @return mixed
     */
    public function create($input) {
        // TODO: Implement create() method.
    }

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $user
     * @return bool
     */
    public function save($user) {
        $userDAO = DataAccessObject::GetUserDAO();
        $userDAO->save($user);
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