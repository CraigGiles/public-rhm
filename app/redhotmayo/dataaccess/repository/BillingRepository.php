<?php namespace redhotmayo\dataaccess\repository;

use redhotmayo\billing\plan\BillingPlan;
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
     * Gets the users current billing plan
     *
     * @param User $user
     * @return BillingPlan
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPlanForUser(User $user);

    /**
     * Return the value associated with an unknown customer token
     *
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getUnknownCustomerToken();
}
