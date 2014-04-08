<?php namespace redhotmayo\dataaccess\repository;

interface Repository {
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
     * @param $input
     * @return mixed
     */
    public function create($input);

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
}