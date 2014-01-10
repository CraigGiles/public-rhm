<?php

class AccountSQL implements AccountDAO {
    const TABLE_NAME = 'accounts';
    const C_USER = 'userId';
    const C_WEEKLY_OPPORTUNITY = 'weeklyOpportunity';
    const C_ACCOUNT_NAME = 'accountName';
    const C_OPERATOR_TYPE = 'operatorType';
    const C_ADDRESS_ID = 'addressId';
    const C_CONTACT_NAME = 'contactName';
    const C_PHONE = 'phone';
    const C_SERVICE_TYPE = 'serviceType';
    const C_CUISINE_TYPE = 'cuisineType';
    const C_SEAT_COUNT = 'seatCount';
    const C_AVERAGE_CHECK = 'averageCheck';
    const C_EMAIL_ADDRESS = 'emailAddress';
    const C_OPEN_DATE = 'openDate';
    const C_ESTIMATED_ANNUAL_SALES = 'estimatedAnnualSales';
    const C_OWNER = 'owner';
    const C_MOBILE_PHONE = 'mobilePhone';
    const C_WEBSITE = 'website';
    const C_IS_TARGET_ACCOUNT = 'isTargetAccount';
    const C_IS_MASTER = 'isMaster';

    public static function GetColumns() {
        return array(
            self::TABLE_NAME . '.' . self::C_USER,
            self::TABLE_NAME . '.' . self::C_WEEKLY_OPPORTUNITY,
            self::TABLE_NAME . '.' . self::C_ACCOUNT_NAME,
            self::TABLE_NAME . '.' . self::C_OPERATOR_TYPE,
            self::TABLE_NAME . '.' . self::C_ADDRESS_ID,
            self::TABLE_NAME . '.' . self::C_CONTACT_NAME,
            self::TABLE_NAME . '.' . self::C_PHONE,
            self::TABLE_NAME . '.' . self::C_SERVICE_TYPE,
            self::TABLE_NAME . '.' . self::C_CUISINE_TYPE,
            self::TABLE_NAME . '.' . self::C_SEAT_COUNT,
            self::TABLE_NAME . '.' . self::C_AVERAGE_CHECK,
            self::TABLE_NAME . '.' . self::C_EMAIL_ADDRESS,
            self::TABLE_NAME . '.' . self::C_OPEN_DATE,
            self::TABLE_NAME . '.' . self::C_ESTIMATED_ANNUAL_SALES,
            self::TABLE_NAME . '.' . self::C_OWNER,
            self::TABLE_NAME . '.' . self::C_MOBILE_PHONE,
            self::TABLE_NAME . '.' . self::C_WEBSITE,
            self::TABLE_NAME . '.' . self::C_IS_TARGET_ACCOUNT,
            self::TABLE_NAME . '.' . self::C_IS_MASTER,
        );
    }

    /**
     * Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $accounts
     * @return array
     */
    public function saveAll(array $accounts) {
        $unsaved = array();
        foreach ($accounts as $account) {
            $id = $this->save($account);
            if (!isset($id)) {
                $unsaved[] = $account;
            }
        }

        return $unsaved;
    }

    /**
     * Save a record and return the objectId
     *
     * @param Account $address
     * @return mixed
     */
    public function save(Account $account) {
        $this->db->beginTransaction();

        try {
            $userId = $account->getUserID();
            $address = $account->getAddress();

            //if the userId is set, this is a distributed lead...
            //check to see if its a dup, and if it is, rollback the transaction
            //otherwise, commit the transaction and go about our merry way.
            if (isset($userId)) {
                $master = false;
                //since this is a distributed lead, check duplicate status
                $dupResults = $this->db->table('addresses')
                                ->join('accounts', 'addressId', '=', 'addresses.id')
                                ->select('primaryNumber', 'streetPredirection', 'streetName', 'streetSuffix', 'zipCode', 'addressId', 'userId', 'accountName')
                                ->where('userId', '=', $userId)
                                ->where('primaryNumber', '=', $address->getPrimaryNumber())
                                ->where('streetName', '=', $address->getStreetName())
                                ->where('zipCode', '=', $address->getZipCode())
                                ->where('streetSuffix', '=', $address->getStreetSuffix())
                                ->where('accountName', '=', $account->getAccountName())
                                ->get();

                if (count($dupResults) > 0) {
                    //we are a dup. Roll back the transaction and return
                    $this->db->rollback();
                    return null;
                } else {
                    $id = $this->saveAccount($account, $master);
                    $this->db->commit();
                    return $id;
                }
            } else {
                $master = true;

                // save the account
                $id = $this->saveAccount($account, $master);
                $this->db->commit();
                return $id;
            }
        } catch (Exception $e) {
            //todo: log exception
            print_r($e->getMessage());
            DB::rollback();
            return null;
        }
    }

    /**
     * @param Account $account
     * @param $master
     * @return mixed
     */
    private function saveAccount(Account $account, $master) {
        $address = $account->getAddress();

        //save the address first
        $addressDAO = DataAccessObject::getDAO(DataAccessObject::ADDRESS);
        $addressDAO->save($address);

        $id = DB::table('accounts')
                ->insertGetId(array(
                    'userId' => $account->getUserID(),
                    'weeklyOpportunity' => $account->getWeeklyOpportunity(),
                    'accountName' => $account->getAccountName(),
                    'operatorType' => $account->getOperatorType(),
                    'addressId' => $account->getAddress()
                                           ->getId(),
                    'contactName' => $account->getContactName(),
                    'phone' => $account->getPhone(),
                    'serviceType' => $account->getServiceType(),
                    'cuisineType' => $account->getCuisineType(),
                    'seatCount' => $account->getSeatCount(),
                    'averageCheck' => $account->getAverageCheck(),
                    'emailAddress' => $account->getEmailAddress(),
                    'openDate' => $account->getOpenDate(),

                    'estimatedAnnualSales' => $account->getEstimatedAnnualSales(),
                    'owner' => $account->getOwner(),
                    'mobilePhone' => $account->getMobilePhone(),
                    'website' => $account->getWebsiteAddress(),
                    'isTargetAccount' => $account->getIsTargetAccount(),
                    'isMaster' => $master,

                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                )
            );
        $account->setId($id);

        //save all the notes
        $notes = $account->getNotes();
        foreach ($notes as $note) {
            $noteSQL = new NoteSQL();
            $note->setAccountId($account->getId());
            $noteSQL->save($note);
        }

        return $id;
    }
}