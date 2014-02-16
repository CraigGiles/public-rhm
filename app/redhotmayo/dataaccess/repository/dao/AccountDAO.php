<?php namespace redhotmayo\dataaccess\repository\dao;

use redhotmayo\model\Account;

interface AccountDAO {
    /**
     * Save a record and return the objectId
     *
     * @param \redhotmayo\model\Account $account
     * @internal param \redhotmayo\model\Account $address
     * @return mixed
     */
    public function save(Account $account);

    /**
     * Save an array of records returning a list of objects that could not be saved.
     *
     * @param array $accounts
     * @return array
     */
    public function saveAll(array $accounts);
}
