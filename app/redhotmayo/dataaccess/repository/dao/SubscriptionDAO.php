<?php namespace redhotmayo\dataaccess\repository\dao;

use redhotmayo\model\Subscription;

interface SubscriptionDAO {
    /**
     * Save a record and return the objectId
     *
     * @param Subscription $subscription
     * @return mixed
     */
    public function save(Subscription $subscription);

    /**
     * Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $subscriptions
     * @return array
     */
    public function saveAll(array $subscriptions);
}
