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
            //save the address first
            $addressDAO = DataAccess::getDAO(DataAccessObject::ADDRESS);
            $addressDAO->save($account->getAddress());

            if ($account->getUserID() == null) {
                $master = false;
            } else {
                $master = true;
            }

            // save the account
            $id = DB::table('accounts')
                    ->insertGetId(array(
                        'userId' => $account->getUserID(),
                        'weeklyOpportunity' => $account->getWeeklyOpportunity(),
                        'accountName' => $account->getAccountName(),
                        'operatorType' => $account->getOperatorType(),
                        'addressId' => $account->getAddress()->getId(),
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
                        'distributed' => $master,

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
            DB::commit();
            return $id;
        } catch (Exception $e) {
            //todo: log exception
            print_r($e->getMessage());
            DB::rollback();
            return null;
        }
    }
}
