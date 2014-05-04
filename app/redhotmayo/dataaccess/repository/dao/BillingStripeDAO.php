<?php namespace redhotmayo\dataaccess\repository\dao;

use redhotmayo\dataaccess\encryption\Encrypted;
use redhotmayo\model\Billing;

interface BillingStripeDAO extends Encrypted {

    /**
     * Saves the object to the database returning the id of the object
     *
     * @param Billing $billing
     * @return int
     */
    public function save(Billing $billing);
}
