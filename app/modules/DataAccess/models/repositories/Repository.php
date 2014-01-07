<?php

interface Repository {
    public function all();
    public function find($id);
    public function create($input);

    /**
     * Save the object to the database returning the ID if successful and null if unsuccessful.
     *
     * @param $object
     * @return int|null ID of the object
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