<?php

class LeadDistributionRepositorySQL extends SQLRepository {
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

    public function distributeAccountsToUsers($accounts) {
        $unsaved = array();
        foreach ($accounts as $account) {
            $zipcode = $account->getAddress()->getZipCode();

            $subscribedUsers = $this->getAllUserIdsSubscribedToZipcode($zipcode);

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

    /**
     * Returns all master record account objects within the zipcode provided that has been updated
     * after the date provided.
     *
     * @param $zipcode
     * @param $afterDate
     * @return array Account objects
     */
    function findAllAccountsForZipcode($zipcode, $afterDate) {
        $zipcode = intval($zipcode);
        $addressCols = AddressSQL::GetColumns();
        $accountCols = AccountSQL::GetColumns();
        $noteCols = NoteSQL::GetColumns();

        $cols = array_merge($addressCols, $accountCols, $noteCols);

        $objects = array();

        $accounts = $this->db->table('accounts')
                             ->join('addresses', 'accounts.addressId', '=', 'addresses.id')
                             ->join('notes', 'notes.accountId', '=', 'accounts.id')
                             ->select($cols)
                             ->where('zipCode', '=', $zipcode)
                             ->where('accounts.updated_at', '>', $afterDate)
                             ->get();

        foreach ($accounts as $account) {
            $acct = new Account();
            $addr = new Address();
            $note = new Note();

            $note->setAction($account->action);
            $note->setText($account->text);
            $note->setAuthor($account->action);

            $addr->setPrimaryNumber($account->primaryNumber);
            $addr->setStreetPredirection($account->streetPredirection);
            $addr->setStreetName($account->streetName);
            $addr->setStreetSuffix($account->streetSuffix);
            $addr->setSuiteType($account->suiteType);
            $addr->setSuiteNumber($account->suiteNumber);
            $addr->setCityName($account->cityName);
            $addr->setCountyName($account->countyName);
            $addr->setStateAbbreviation($account->stateAbbreviation);
            $addr->setZipcode($account->zipCode);
            $addr->setPlus4Code($account->plus4Code);
            $addr->setLongitude($account->longitude);
            $addr->setLatitude($account->latitude);
            $addr->setCassVerified($account->cassVerified);
            $addr->setGoogleGeocoded($account->googleGeocoded);

            $acct->setUserID($account->userId);
            $acct->addNote($note);
            $acct->setWeeklyOpportunity($account->weeklyOpportunity);
            $acct->setAccountName($account->accountName);
            $acct->setOperatorType($account->operatorType);
            $acct->setAddress($addr);
            $acct->setContactName($account->contactName);
            $acct->setPhone($account->phone);
            $acct->setServiceType($account->serviceType);
            $acct->setCuisineType($account->cuisineType);
            $acct->setSeatCount($account->seatCount);
            $acct->setAverageCheck($account->averageCheck);
            $acct->setEmailAddress($account->emailAddress);
            $acct->setOpenDate($account->openDate);
            $acct->setEstimatedAnnualSales($account->estimatedAnnualSales);
            $acct->setOwner($account->owner);
            $acct->setMobilePhone($account->mobilePhone);
            $acct->setWebsite($account->website);
            $acct->setIsTargetAccount($account->isTargetAccount);
            $acct->setIsMaster($account->isMaster);

            $objects[] = $acct;
        }

        return $objects;
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