<?php namespace redhotmayo\dataaccess\repository\sql;

use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\model\Account;
use redhotmayo\model\User;

class SubscriptionRepositorySQL implements SubscriptionRepository {
    public function all() {
        // TODO: Implement all() method.
    }

    /**
     * Return an array of all objects that match the given constraints
     *
     * @param $search
     * @param $parameters
     * @return mixed
     */
    public function find($search, $parameters) {
    }

    public function create($input) {
        // TODO: Implement create() method.
    }

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $object
     * @return bool
     */
    public function save($subscription) {
        $dao = DataAccessObject::GetSubscriptionDAO();
        $id = $dao->save($subscription);
        return isset($id);
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll($objects) {
        $unsaved = array();
        foreach ($objects as $subscription) {
            $id = $this->save($subscription);
        }
        return $unsaved;
    }

    /**
     * Performs a query in order to determine if the user is already subscribed to a particular account
     *
     * @param User $user
     * @param Account $account
     * @return bool
     */
    public function isUserSubscribedToAccount(User $user, Account $account) {
        $address = $account->getAddress();
        $accounts = DB::table('addresses')
                             ->join('accounts', 'accounts.addressId', '=', 'addresses.id')
                             ->select('primaryNumber', 'streetPredirection', 'streetName', 'streetSuffix', 'zipCode', 'addressId', 'userId', 'accountName')
                             ->where('userId', '=', $user->getUserId())
                             ->where('primaryNumber', '=', $address->getPrimaryNumber())
                             ->where('streetName', '=', $address->getStreetName())
                             ->where('zipCode', '=', $address->getZipCode())
                             ->where('streetSuffix', '=', $address->getStreetSuffix())
                             ->where('accountName', '=', $account->getAccountName())->get();

        if (count($accounts) > 0) return true;
        return false;
    }

    /**
     * Obtain an array of user id's subscribed to a particular zipcode
     *
     * @param int $zipcode
     * @return array
     */
    public function getAllUserIdsSubscribedToZipcode($zipcode) {
        $zipcode = intval($zipcode);
        $users = DB::table('subscriptions')->select('userId')->where('zipCode', '=', $zipcode)->get();
        $ids = array();

        foreach ($users as $user) {
            $ids[] = $user->userId;
        }

        return $ids;

    }

    /**
     * Take an array of database records and convert them to the appropriate objects
     *
     * @param $records
     * @return array
     */
    function convertRecordsToObjects($records) {
        // TODO: Implement convertRecordsToObjects() method.
    }
}
