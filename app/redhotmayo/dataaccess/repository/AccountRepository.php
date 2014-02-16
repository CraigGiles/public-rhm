<?php namespace redhotmayo\dataaccess\repository;

use redhotmayo\model\Account;

interface AccountRepository extends Repository {

    /**
     * Determines weather or not an account is already subscribed to a user
     *
     * @param Account $account
     * @param $userId
     * @return bool
     */
    public function isAccountSubscribedToUser(Account $account, $userId);

    /**
     * Given a list of account objects, iterate through each account object and distribute it to all users subscribed
     * to that accounts zipcode that are not already subscribed to the account. This process will return a list of
     * accounts that could not be distributed.
     *
     * @param $accounts
     * @return array $unsaved
     */
    public function distributeAccountsToUsers($accounts);

    /**
     * Create a copy of the account object, assigning the userId to the new object and save it to the data source.
     * Returns true if the account was able to be saved, false otherwise.
     *
     * @param Account $account
     * @param $userId
     * @return bool
     */
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

    /**
     * Adds a note to the specified account
     *
     * @param array $notes
     * @return mixed
     */
    public function attachNotesToAccount($notes);

    /**
     * Mark the given accounts as deleted
     *
     * @param array $accounts
     * @return mixed
     */
    public function markAccountsDeleted($accounts);

    /**
     * Restore the given accounts from deleted status
     *
     * @param $accounts
     * @return mixed
     */
    public function restoreAccounts($accounts);

    /**
     * Mark the given accounts as target accounts
     *
     * @param $accounts
     */
    public function markAccountsTargeted($accounts);

    /**
     * Get all account IDs owned by user with the provided ID
     *
     * @param int $id
     * @return array
     */
    public function getAllUsersAccountIds($id);
}