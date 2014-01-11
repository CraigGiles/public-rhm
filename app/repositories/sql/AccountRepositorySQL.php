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
        $saved = false;
        try {
            //save address
            $address = $account->getAddress();
            $addressDAO = DataAccessObject::GetAddressDAO();
            $addressDAO->save($address);

            //save account
            $accountDAO = DataAccessObject::GetAccountDAO();
            $accountId = $accountDAO->save($account);

            //save all notes
            $noteDAO = DataAccessObject::GetNoteDAO();
            $notes = $account->getNotes();
            foreach ($notes as $note) {
                $note->setAccountId($accountId);
                $noteDAO->save($note);
            }

            // only commit this transaction if the account was saved.
            if (isset($accountId)) {
                DB::commit();
                $saved = true;
            }
        } catch (Exception $e) {
            DB::rollback();
            $id = null;
        }

        return $saved;
    }

    public function saveAll($accounts) {
        \Illuminate\Support\Facades\Log::info("Saving all accounts");
        $unsaved = array();
        foreach ($accounts as $account) {
            if (!$this->save($account)) {
                $unsaved[] = $account;
            }
        }
        return $unsaved;
    }

    public function distributeAccountsToUsers($accounts) {
        $unsaved = array();
        foreach ($accounts as $account) {
            $address = $account->getAddress();
            if (isset($address)) {
                $zipcode = $account->getAddress()->getZipcode();
                $subRepo = RepositoryFactory::GetSubscriptionRepository();
                $subscribedUsers = $subRepo->getAllUserIdsSubscribedToZipcode($zipcode);
                // if the user is already subscribed to this lead, don't re-sub them
                foreach ($subscribedUsers as $userId) {
                    $subscribed = $this->subscribeAccountToUserId($account, $userId);
                    if (!$subscribed) {
                        Log::info("User is already subscribed to this lead");
                    }
                }
            } else {
                $unsaved[] = $account;
            }

        }

        return $unsaved;
    }

    public function subscribeAccountToUserId(Account $account, $userId) {
        $account->setUserID($userId);
        $address = $account->getAddress();
        $address->setId(null);

        $notes = $account->getNotes();
        foreach ($notes as $note) {
            $note->setId(null);
        }
        $this->save($account);
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

        $accounts = DB::table('accounts')
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