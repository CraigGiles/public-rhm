<?php

class AccountRepositorySQL implements Repository {

    public function all() {
    }

    public function find($id) {
    }

    public function create($input) {
    }

    /**
     * @param Account $account
     */
    public function save($account) {
        DB::beginTransaction();
        try {
            //save the address
            $address = $account->getAddress();
            $addressSQL = new AddressSQL();
            $addressSQL->save($address);

            //save teh account
            $accountSQL = new AccountSQL();
            $accountSQL->save($account);

            //save all the notes
            $notes = $account->getNotes();
            foreach ($notes as $note) {
                $noteSQL = new NoteSQL();
                $note->setAccountId($account->getId());
                $noteSQL->save($note);
            }

            // if no problems, commit the transaction
            DB::commit();
            return true;
        } catch (Exception $e) {
            //todo: log exception
            DB::rollback();
            return false;
        }
    }

    public function saveAll($accounts) {
        $unsaved = array();
        foreach ($accounts as $account) {
            if (!$this->save($account)) {
                $unsaved[] = $account;
            }
        }
        return $unsaved;
    }

//    public function distributeAccountsToUsers($accounts) {
//        $unsaved = array();
//        foreach ($accounts as $account) {
//            $zipcode = $account->getAddress()->getZipcode();
//            $subRepo = RepositoryFactory::
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

}