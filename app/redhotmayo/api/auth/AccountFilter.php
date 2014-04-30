<?php namespace redhotmayo\api\auth;

use InvalidArgumentException;
use redhotmayo\dataaccess\repository\AccountRepository;
use redhotmayo\model\Account;

class AccountFilter {
    /** @var \redhotmayo\dataaccess\repository\AccountRepository $accountRepo */
    private $accountRepo;

    public function __construct(AccountRepository $accountRepo) {
        $this->accountRepo = $accountRepo;
    }

    /**
     * Takes an array of accounts and a userId value and returns only instances of
     * accounts that belong to the specified userId.
     *
     * @param int $userId
     * @param array $accounts
     * @return array
     *
     * @throws InvalidArgumentException
     * @author Craig Giles < craig@gilesc.com >
     */
    public function filterAccountCollections($userId, array $accounts) {
        $valid = [];

        /** @var Account $account */
        foreach ($accounts as $account) {
            if (!$account instanceof Account) {
                throw new InvalidArgumentException("account must be an instance of Account");
            }

            if ($account->getUserID() === $userId) {
                $valid[] = $account;
            }
        }

        return $valid;
    }

    /**
     * @param int $userId
     * @param array $accountIds
     * @return array
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function filterAccountIds($userId, array $accountIds) {
        $accounts = $this->accountRepo->getAllUsersAccountIds($userId);
        $valid = [];

        foreach ($accountIds as $id) {
            if (in_array($id, $accounts)) {
                $valid[] = $id;
            }
        }

        return $valid;
    }
} 
