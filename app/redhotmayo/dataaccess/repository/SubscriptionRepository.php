<?php namespace redhotmayo\dataaccess\repository;

use redhotmayo\model\Account;
use redhotmayo\model\User;

interface SubscriptionRepository extends Repository {
    public function all();

    /**
     * Return an array of all objects that match the given constraints
     *
     * @param $search
     * @param $parameters
     * @return mixed
     */
    public function find($search, $parameters);

    public function create($input);

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $object
     * @return bool
     */
    public function save($subscription);

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll($objects);

    /**
     * Performs a query in order to determine if the user is already subscribed to a particular account
     *
     * @param User $user
     * @param Account $account
     * @return bool
     */
    public function isUserSubscribedToAccount(User $user, Account $account);

    /**
     * Obtain an array of user id's subscribed to a particular zipcode
     *
     * @param int $zipcode
     * @return array
     */
    public function getAllUserIdsSubscribedToZipcode($zipcode);
}


