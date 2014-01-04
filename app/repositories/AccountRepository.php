<?php

class AccountRepository implements Repository {

    public function all() {
        return Account::all();
    }

    public function find($id) {
        return Account::find($id);
    }

    public function create($input) {
        echo 'yup';
        return true;
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
}