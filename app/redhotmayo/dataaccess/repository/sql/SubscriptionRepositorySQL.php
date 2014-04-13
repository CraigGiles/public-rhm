<?php namespace redhotmayo\dataaccess\repository\sql;

use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\dataaccess\repository\dao\sql\SubscriptionSQL;
use redhotmayo\dataaccess\repository\dao\sql\UserSQL;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\model\Account;
use redhotmayo\model\Subscription;
use redhotmayo\model\SubscriptionLocation;
use redhotmayo\model\User;

class SubscriptionRepositorySQL extends RepositorySQL implements SubscriptionRepository {
    const SERVICE = '\redhotmayo\dataaccess\repository\sql\SubscriptionRepositorySQL';

    /** @var \redhotmayo\dataaccess\repository\UserRepository */
    private $userRepository;

    /** @var \redhotmayo\dataaccess\repository\ZipcodeRepository */
    private $zipcodeRepository;

    public function __construct(UserRepository $userRepository, ZipcodeRepository $zipcodeRepository) {
        $this->userRepository = $userRepository;
        $this->zipcodeRepository = $zipcodeRepository;
    }

    public function all() {
        // TODO: Implement all() method.
    }

    /**
     * Return an array of all objects that match the given constraints
     *
     * @param $parameters
     * @internal param $search
     * @return array
     */
    public function find($parameters) {
        $subscriptions = parent::find($parameters);
        return $this->getSubscriptionLocationObjects($subscriptions);
    }

    public function create($input) {
        // TODO: Implement create() method.
    }

    /**
     * Save the object to the database returning true if the object was saved, false otherwise.
     *
     * @param $subscription
     * @return bool
     */
    public function save($subscription) {
        if (isset($subscription) && $subscription instanceof Subscription) {
            $dao = DataAccessObject::GetSubscriptionDAO();
            $id = $this->isSubscriptionRecorded($subscription) ? true : $dao->save($subscription);
        }
        return isset($id);
    }

    /**
     * Save all objects to the database returning any objects that were unsuccessful.
     *
     * @param $objects
     * @return array
     */
    public function saveAll(array $objects) {
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
    function convertRecordsToJsonObjects($records) {
        // TODO: Implement convertRecordsToJsonObjects() method.
    }

    /**
     * Return true if the subscription is already recorded, false otherwise
     *
     * @param Subscription $subscription
     * @return bool
     */
    function isSubscriptionRecorded(Subscription $subscription) {
        $users = $this->getAllUserIdsSubscribedToZipcode($subscription->getZipCode());
        return in_array($subscription->getUserID(), $users);
    }

    protected function getConstraints($parameters) {
        $constraints = [];
        if (isset($parameters[SubscriptionSQL::C_USER_ID])) {
            $constraints[SubscriptionSQL::C_USER_ID] = $parameters[SubscriptionSQL::C_USER_ID];
        }

        return $constraints;
    }

    private function getSubscriptionLocationObjects($subscriptions) {
        $data = [];

        foreach ($subscriptions as $sub) {
            $result = $this->zipcodeRepository->getLocationInformation([ZipcodeRepositorySQL::C_ZIPCODE => $sub->zipCode]);
            $data[] = SubscriptionLocation::FromStdClass($result);
        }

        return $data;
    }

    function getTableName() {
        return SubscriptionSQL::TABLE_NAME;
    }

    function getColumns() {
        return SubscriptionSQL::GetColumns();
    }
}
