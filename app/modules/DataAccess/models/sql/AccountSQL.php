<?php

class AccountSQL implements AccountDAO {
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
        DB::beginTransaction();

        try {
            $userId = $account->getUserID();
            $address = $account->getAddress();

            //if the userId is set, this is a distributed lead...
            //check to see if its a dup, and if it is, rollback the transaction
            //otherwise, commit the transaction and go about our merry way.
            if (isset($userId)) {
                $master = false;
                //since this is a distributed lead, check duplicate status
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

                if (count($dupResults) > 0) {
                    //we are a dup. Roll back the transaction and return
                    DB::rollback();
                    return null;
                } else {
                    $id = $this->saveAccount($account, $master);
                    DB::commit();
                    return $id;
                }
            } else {
                $master = true;

                // save the account
                $id = $this->saveAccount($account, $master);
                DB::commit();
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
        $addressDAO = DataAccess::getDAO(DataAccessObject::ADDRESS);
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

        //save all the notes
        $notes = $account->getNotes();
        foreach ($notes as $note) {
            $noteSQL = new NoteSQL();
            $note->setAccountId($account->getId());
            $noteSQL->save($note);
        }

        $account->setId($id);
        return $id;
    }
}
