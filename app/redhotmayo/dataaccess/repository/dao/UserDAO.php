<?php namespace redhotmayo\dataaccess\repository\dao;

use redhotmayo\model\User;

interface UserDAO {
    /**
     * Obtains a user record based on the credentials
     *
     * @param $credentials
     * @return mixed
     */
    public function getUser($credentials);

    /**
     * Save a record and return the objectId
     *
     * @param User $user
     * @return mixed
     */
    public function save(User $user);

    /**
     * Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $users
     * @return array
     */
    public function saveAll(array $users);
}

