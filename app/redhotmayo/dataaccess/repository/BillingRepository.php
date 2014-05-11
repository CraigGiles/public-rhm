<?php namespace redhotmayo\dataaccess\repository;

use redhotmayo\model\User;

interface BillingRepository extends Repository {
    /**
     * @param User $user
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getCustomerToken(User $user);

    /**
     * Return the value associated with an unknown customer token
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getUnknownCustomerToken();
}
