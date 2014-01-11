<?php namespace redhotmayo\dataaccess\repository;

use redhotmayo\model\Account;

interface AccountRepository {

    public function all();

    public function find($id);

    public function create($input);

    public function saveAll($accounts);

    /**
     * @param Account $account
     */
    public function save($account);

    public function isUserSubscribedToAccount(Account $account, $userId);

    public function distributeAccountsToUsers($accounts);

    public function subscribeAccountToUserId(Account $account, $userId);

    /**
     * Returns all master record account objects within the zipcode provided that has been updated
     * after the date provided.
     *
     * @param $zipcode
     * @param $afterDate
     * @return array Account objects
     */
    function findAllAccountsForZipcode($zipcode, $afterDate);
}