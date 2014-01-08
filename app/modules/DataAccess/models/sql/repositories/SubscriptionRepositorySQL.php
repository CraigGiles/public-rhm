<?php

class SubscriptionRepositorySQL extends SQLRepository {
    public function subscribeUserToZipcode(User $user, $zipcode) {
        $subscription = new Subscription();
        $subscription->add($user, $zipcode);
        $id = $this->dao->save($subscription);
        return isset($id);
    }

    /**
     * Performs a query in order to determine if the user is already subscribed to a particular account
     *
     * @param UserEloquent $user
     * @param Account $account
     * @return bool
     */
    public function isUserSubscribedToAccount(User $user, Account $account) {
        $address = $account->getAddress();
        $accounts = $this->db->table('addresses')
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

    /**
     * Obtain an array of user id's subscribed to a particular zipcode
     *
     * @param $zipcode
     * @return array
     */
    public function getAllUserIdsSubscribedToZipcode($zipcode) {
        $zipcode = intval($zipcode);
        $users = $this->db->table('subscriptions')->select('userId')->where('zipCode', '=', $zipcode)->get();
        $ids = array();

        foreach ($users as $user) {
            $ids[] = $user->userId;
        }

        return $ids;
    }
    /**
     * OLD FUNCTIONS BASED ON NON-TDD WORK
     */

//    /**
//     * Distribute an array of accounts to users subscribed to zip codes in which the account lives. An array of
//     * accounts that could not be distributed will be sent back.
//     *
//     * @param $accounts
//     * @return array
//     */
//    public function distributeLeadsToUsers($accounts) {
//        $unsaved = array();
//
//        foreach ($accounts as $account) {
//            $zipcode = $account->getAddress()->getZipCode();
//            $subscribedUsers = $this->getAllUserIdsSubscribedToZipcode($zipcode);
//
//            // if the user is already subscribed to this lead, don't re-sub them
//            foreach ($subscribedUsers as $user) {
//                $subscribed = $this->subscribeAccountToUser($account, $user);
//                if (!$subscribed) {
//                    Log::info("User is already subscribed to this lead");
//                }
//            }
//        }
//
//        return $unsaved;
//    }



//    /**
//     * Subscribes the account to a particular user.
//     * This function will create a duplicate row inside the accounts table, setting the account isMaster to false and
//     * assigning the userId column to the user id provided. If the account was successfully subscribed to the user
//     * true will be returned. If the account has already been subscribed to the user, false will be returned.
//     *
//     * @param Account $account
//     * @param unsigned int $userId
//     * @return bool
//     */
//    public function subscribeAccountToUser(Account $account, $user) {
//        $subscribed = $this->isUserSubscribedToAccount($user, $account);
//        if (!$subscribed) {
//            $userId = $user->getId();
//            $account->setUserID($userId);
//            $accountDAO = DataAccess::getDAO(DataAccessObject::ACCOUNT);
//            $id = $accountDAO->save($account);
//
//            //TODO: QUESTION: if the account couldn't be saved, do i want to throw an exception?
//            if (isset($id)) {
//                $subscribed = true;
//            }
//        }
//
//        return $subscribed;
//    }

} 