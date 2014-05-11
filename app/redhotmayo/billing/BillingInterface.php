<?php  namespace redhotmayo\billing;

use redhotmayo\billing\plan\BillingPlan as Plan;
use redhotmayo\model\User;

interface BillingInterface {
    /**
     * Create a customer with the billing service given the current data
     *
     * @param User $user
     * @param $billableToken
     * @return string
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function createCustomer(User $user, $billableToken);

    /**
     * Subscribe a user to a billing plan
     *
     * @param Plan $plan
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function subscribe(Plan $plan);

    /**
     * Change the users current billing plan for a new billing plan
     *
     * @param Plan $plan
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function change(Plan $plan);

    /**
     * Cancel the current billing plan
     *
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function cancel();

    /**
     * Return the users current billing plan
     *
     * @return Plan
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getBillingPlan();

    /**
     * Return true if the user has an active subscription, false otherwise
     *
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function isSubscribed();
} 
