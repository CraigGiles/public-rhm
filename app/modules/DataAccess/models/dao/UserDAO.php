<?php

interface UserDAO {
    /**
     * Save a record and return the objectId
     *
     * @param UserEloquent $user
     * @return mixed
     */
    public function save(UserEloquent $user);

    /**
     * Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $users
     * @return array
     */
    public function saveAll(array $users);
}

