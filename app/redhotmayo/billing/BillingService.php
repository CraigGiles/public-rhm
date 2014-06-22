<?php  namespace redhotmayo\billing;

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
}
