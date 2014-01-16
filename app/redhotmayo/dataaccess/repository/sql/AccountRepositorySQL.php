<?php namespace redhotmayo\dataaccess\repository\sql;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use redhotmayo\dataaccess\repository\AccountRepository;
use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\dataaccess\repository\dao\sql\AccountSQL;
use redhotmayo\dataaccess\repository\dao\sql\AddressSQL;
use redhotmayo\dataaccess\repository\dao\sql\NoteSQL;
use redhotmayo\dataaccess\repository\RepositoryFactory;
use redhotmayo\model\Account;
use redhotmayo\model\Address;
use redhotmayo\model\Note;
use redhotmayo\rest\Constraint;

class AccountRepositorySQL implements AccountRepository {
    /**
     * Given a list of account objects, iterate through each account object and distribute it to all users subscribed
     * to that accounts zipcode that are not already subscribed to the account. This process will return a list of
     * accounts that could not be distributed.
     *
     * @param $accounts
     * @return array $unsaved
     */
    public function distributeAccountsToUsers($accounts) {
        $unsaved = array();

        /** @var Account $account */
        foreach ($accounts as $account) {

            /** @var Address $address */
            $address = $account->getAddress();
            if (isset($address)) {
                $zipcode = $account->getAddress()
                                   ->getZipcode();
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

    /**
     * Create a copy of the account object, assigning the userId to the new object and save it to the data source.
     * Returns true if the account was able to be saved, false otherwise.
     *
     * @param Account $account
     * @param $userId
     * @return bool
     */
    public function subscribeAccountToUserId(Account $account, $userId) {
        $account->setUserID($userId);
        $address = $account->getAddress();
        $address->setAddressId(null);

        $notes = $account->getNotes();

        /** @var Note $note */
        foreach ($notes as $note) {
            $note->setNoteId(null);
        }
        return $this->save($account);
    }

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param Account $account
     * @return bool
     */
    public function save($account) {
        DB::beginTransaction();
        $saved = false;
        try {
            $accountDAO = DataAccessObject::GetAccountDAO();
            $addressDAO = DataAccessObject::GetAddressDAO();
            $noteDAO = DataAccessObject::GetNoteDAO();

            $userId = $account->getUserID();
            $master = !isset($userId);

            //only save if account is a master account or the user doesn't already have it
            if ($master || (!$master && !$this->isAccountSubscribedToUser($account, $userId))) {
                //save address
                $address = $account->getAddress();
                $addressDAO->save($address);

                //save account
                $accountId = $accountDAO->save($account);

                //save all notes
                $notes = $account->getNotes();

                /** @var Note $note */
                foreach ($notes as $note) {
                    $note->setAccountId($accountId);
                    $noteDAO->save($note);
                }

                DB::commit();
                $saved = true;
            }
        } catch (Exception $e) {
            dd($e);
            DB::rollback();
            $id = null;
        }

        return $saved;
    }

    /**
     * Determines weather or not an account is already subscribed to a user
     *
     * @param Account $account
     * @param $userId
     * @return bool
     */
    public function isAccountSubscribedToUser(Account $account, $userId) {
        $address = $account->getAddress();
        $dupResults = DB::table('addresses')
                        ->join('accounts', 'addressId', '=', 'addresses.id')
                        ->select('primaryNumber', 'streetPredirection', 'streetName', 'streetSuffix', 'zipCode', 'addressId', 'userId', 'accountName')
                        ->where('userId', '=', $userId)
                        ->where('primaryNumber', '=', $address->getPrimaryNumber())
                        ->where('streetName', '=', $address->getStreetName())
                        ->where('zipCode', '=', $address->getZipCode())
                        ->where('streetSuffix', '=', $address->getStreetSuffix())
                        ->where('accountName', '=', $account->getAccountName())
                        ->get();

        return (count($dupResults) > 0);
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

        $accounts = DB::table('accounts')
                      ->join('addresses', 'accounts.addressId', '=', 'addresses.id')
                      ->join('notes', 'notes.accountId', '=', 'accounts.id')
                      ->select($cols)
                      ->where('zipCode', '=', $zipcode)
                      ->where('accounts.updated_at', '>', $afterDate)
                      ->get();

        $objects = $this->convertRecordsToObjects($accounts);

        return $objects;
    }

    /**
     * Take an array of database records and convert them to the appropriate objects
     *
     * @param $records
     * @return array
     */
    function convertRecordsToObjects($records) {
        $objects = array();
        foreach ($records as $account) {
            $acct = new Account();
            $addr = new Address();
            $note = new Note();

            $note->setNoteId($account->noteId);
            $note->setAccountId($account->accountId);
            $note->setAction($account->action);
            $note->setText($account->text);
            $note->setAuthor($account->author);

            $addr->setAddressId($account->addressId);
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

            $acct->setAccountId($account->accountId);
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
     * Return an array of all objects
     *
     * @return array
     */
    public function all() {
        $addressCols = AddressSQL::GetColumns();
        $accountCols = AccountSQL::GetColumns();
        $noteCols = NoteSQL::GetColumns();

        $cols = array_merge($addressCols, $accountCols, $noteCols);

        $accounts = DB::table('accounts')
                      ->join('addresses', 'addresses.id', '=', 'accounts.addressId')
                      ->join('notes', 'accounts.id', '=', 'notes.accountId')
                      ->select($cols)
                      ->get();
        return $this->convertRecordsToObjects($accounts);
    }

    public function show($searchType, $paramters) {

    }

    /**
     * Return an array of all objects that match the given constraints
     *
     * @param $search
     * @param $parameters
     * @return mixed
     */
    public function find($search, $parameters) {
        $constraints = $this->setupSearchConstraints($parameters);
        $order = $this->setupOrderBy($parameters);

        $addressCols = AddressSQL::GetColumns();
        $accountCols = AccountSQL::GetColumns();
        $noteCols = NoteSQL::GetColumns();

        $cols = array_merge($addressCols, $accountCols, $noteCols);

        /** @var Constraint $constraint */
        $db = DB::table('accounts')
          ->join('addresses', 'addresses.id', '=', 'accounts.addressId')
          ->join('notes', 'accounts.id', '=', 'notes.accountId')
          ->select($cols);
        foreach ($constraints as $constraint) {
            $db->where($constraint->getColumn(), $constraint->getOperator(), $constraint->getValue());
        }

        if (isset($order)) {
            $db->orderBy($order->getColumn(), $order->getValue());
        }

        $accounts = $db->get();
        return $this->convertRecordsToObjects($accounts);
    }

    /**
     * Create an object from given input
     *
     * @param $input
     * @return mixed
     */
    public function create($input) {
        // TODO: Implement create() method.
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $accounts
     * @return array
     */
    public function saveAll($accounts) {
        Log::info("Saving all accounts");
        $unsaved = array();
        foreach ($accounts as $account) {
            if (!$this->save($account)) {
                $unsaved[] = $account;
            }
        }
        return $unsaved;
    }

    public function convertArrayToObjects($array) {
        //Account::FromArray($data);
        $accounts = array();
        foreach ($array as $account) {
            $accounts[] = $this->getAccountFromArray($account);
        }
        return $accounts;
    }

    private function getAccountFromArray($account) {

    }

    private function setupSearchConstraints($parameters) {
        $return = array();
        foreach ($parameters as $param => $value) {
            switch ($param) {
                case 'account':
                    $return[] = new Constraint(AccountSQL::C_ACCOUNT_NAME, 'like', "%{$value}%");
                    break;

                case 'targeted':
                    $return[] = new Constraint(AccountSQL::C_IS_TARGET_ACCOUNT, '=', intval($value));
                    break;

                case 'zipcode':
                    $return[] = new Constraint(AddressSQL::C_ZIP_CODE, '=', intval($value));
                    break;

                case 'user':
                    $return[] = new Constraint(AccountSQL::C_USER, '=', intval($value));
                    break;
            }
        }
        return $return;
    }

    private function setupOrderBy($parameters) {
        foreach ($parameters as $param => $value) {
            switch ($param) {
                case 'order':
                    if ($value === 'created_at') return new Constraint(AccountSQL::TABLE_NAME .'.'. AccountSQL::C_CREATED_AT, '>', 'asc');
            }
        }
    }
}