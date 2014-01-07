<?php

class SubscriptionRepositorySQL extends SQLRepository {
    /**
     * Obtain an array of user id's subscribed to a particular zipcode
     *
     * @param $zipcode
     * @return array
     */
    public function getAllUsersSubscribedToZipcode($zipcode) {
        $zipcode = intval($zipcode);
        $users = DB::table('subscriptions')->select('userId')->where('zipCode', '=', $zipcode)->get();
        $ids = array();

        foreach ($users as $user) {
            $ids[] = $user->userId;
        }

        return $ids;
    }

    /**
     * Distribute an array of accounts to users subscribed to zip codes in which the account lives. An array of
     * accounts that could not be distributed will be sent back.
     *
     * @param $accounts
     * @return array
     */
    public function distributeLeadsToUsers($accounts) {
        $unsaved = array();

        foreach ($accounts as $account) {
            $zipcode = $account->getAddress()->getZipCode();
            $subscribedUsers = $this->getAllUsersSubscribedToZipcode($zipcode);

            // if the user is already subscribed to this lead, don't re-sub them
            foreach ($subscribedUsers as $user) {
                $subscribed = $this->subscribeAccountToUser($account, $user);
                if (!$subscribed) {
                    Log::info("User is already subscribed to this lead");
                }
            }
        }

        return $unsaved;
    }

    public function isUserSubscribedToAccount(User $user, Account $account) {
        $address = $account->getAddress();
        $accounts = DB::table('addresses')
                      ->join('accounts', 'accounts.addressId', '=', 'addresses.id')
                      ->select('primaryNumber', 'streetPredirection', 'streetName', 'streetSuffix', 'zipCode', 'addressId', 'userId', 'accountName')
                      ->where('userId', '=', $user->getId())
                      ->where('primaryNumber', '=', $address->getPrimaryNumber())
                      ->where('streetName', '=', $address->getStreetName())
                      ->where('zipCode', '=', $address->getZipCode())
                      ->where('streetSuffix', '=', $address->getStreetSuffix())
                      ->where('accountName', '=', $account->getAccountName())->get();

        if (count($accounts) > 0) return true;
        return false;
    }

    public function subscribeAccountToUser(Account $account, $userId) {
        $account->setUserID($userId);
        $accountDAO = DataAccess::getDAO(DataAccessObject::ACCOUNT);
        $id = $accountDAO->save($account);
        if (!isset($id)) {
            return false;
        }

        return true;
    }

} 