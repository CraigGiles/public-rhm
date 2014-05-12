<?php namespace redhotmayo\dataaccess\repository\dao;

use redhotmayo\billing\Subscription;
use redhotmayo\dataaccess\encryption\Encrypted;

interface BillingStripeDAO extends Encrypted {

    /**
     * Saves the object to the database returning the id of the object
     *
     * @param Subscription $subscription
     * @return int
     */
    public function save(Subscription $subscription);
}
