<?php  namespace redhotmayo\billing;

use redhotmayo\billing\plan\BillingPlan;
use redhotmayo\model\User;

interface BillingService {
    /**
     * Subscribe a user to our billing system. If the client is new than
     * a new customer record will be constructed. If the client is
     * existing, than our billing records will be updated with
     * the new subscription.
     *
     * @see setBillingToken
     *
     * @param User $user
     * @return Subscription
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function subscribe(User $user);

    /**
     * Cancel a users billing subscription. The user will continue to enjoy
     * the product until the current billing subscription expires.
     *
     * @param User $user
     * @return bool
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function cancel(User $user);

    /**
     * Set the users billing token. If the user is a new user, this
     * MUST be triggered before any subscribe calls.
     *
     * @param $token
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function setBillingToken($token);

    /**
     * Get the active subscription
     *
     * @param User $user
     * @return Subscription
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getSubscriptionForUser(User $user);

    /**
     * Creates a new billing plan based on the users subscribed population
     *
     * @param User $user
     * @return BillingPlan
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function createBillingPlanForUser(User $user);

    /**
     * Obtains the current population count for the users subscribed territories
     *
     * @param User $user
     * @return int
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function getPopulationCountForUser(User $user);

    /**
     * Given an array of regions, output the proposed total for each billing cycle
     *
     * @param array $regions
     * @return int
     */
    public function getProposedTotalForRegions(array $regions);
}
