<?php namespace redhotmayo\dataaccess\repository;

use redhotmayo\model\Account;
use redhotmayo\model\Subscription;
use redhotmayo\model\User;

interface SubscriptionRepository extends Repository {
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


    /**
     * Return true if the subscription is already recorded, false otherwise
     *
     * @param Subscription $subscription
     * @return bool
     */
    function isSubscriptionRecorded(Subscription $subscription);
}


