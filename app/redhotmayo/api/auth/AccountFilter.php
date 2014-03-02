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
     * @param $userId
     * @param array $accounts Account[]
     *
     * @return array Account[]
     *
     * @throws InvalidArgumentException
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
     * @param $userId
     * @param $accountIds
     *
     * @return array
     */
    public function filterAccountIds($userId, $accountIds) {
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