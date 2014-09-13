<?php  namespace redhotmayo\distribution;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use redhotmayo\dataaccess\repository\AccountRepository;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\model\Account;
use redhotmayo\model\Subscription;
use redhotmayo\model\User;
use redhotmayo\notifications\GooglePushNotification;
use redhotmayo\utility\Arrays;

class AccountSubscriptionManager {
    const DATA_TYPE = 'type';
    const CITY = 'city';
    const STATE = 'state';
    const COUNTY = 'county';
    const BACKDATE_DAYS = 30;

    /** @var \redhotmayo\dataaccess\repository\SubscriptionRepository $subscriptionRepository */
    private $subscriptionRepository;

    /** @var \redhotmayo\dataaccess\repository\ZipcodeRepository $zipcodeRepository */
    private $zipcodeRepository;

    /** @var \redhotmayo\dataaccess\repository\AccountRepository $accountRepository */
    private $accountRepository;

    /** @var \redhotmayo\notifications\GooglePushNotification $notifications */
    private $notification;

    public function __construct(
        SubscriptionRepository $subscriptionRepository,
        ZipcodeRepository $zipcodeRepository,
        AccountRepository $accountRepository
    ) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->zipcodeRepository = $zipcodeRepository;
        $this->accountRepository = $accountRepository;

        $this->notification = new GooglePushNotification();
    }

    /**
     * @param User $user
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function processNewUsersData(User $user) {
        $cookie = Cookie::get('temp_id');
        if (isset($cookie)) {
            $data = Session::get($cookie);

            if (isset($data) && is_array($data)) {
                $this->process($user, $data);
            }
        }

        Session::forget(Cookie::get('temp_id'));
    }

    /**
     * Process the subscription data for the authed user given.
     * In a future version the billing process will get involved here as well.. currently the only action is to
     * subscribe the zipcodes of the given dataset to the user and hold it into the subscriptions table.
     *
     * @param User $user
     * @param array $dataset
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    public function process(User $user, array $dataset) {
        $zipcodes        = $this->getZipcodesForRegions($dataset);
        $subscribedTo    = $this->subscriptionRepository->getAllZipcodesForUser($user);
        $unsubscribeFrom = array_diff($subscribedTo, $zipcodes);

        $this->subscriptionRepository->unsubscribeUserFromZipcodes($user, $unsubscribeFrom);
        $this->backdateAllZipcodes($user, $zipcodes);
    }

    /**
     * @param array $dataset
     * @return array
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function getZipcodesForRegions(array $dataset) {
        $zipcodes = [];

        foreach ($dataset as $data) {
            switch (Arrays::GetValue($data, self::DATA_TYPE, null)) {
                case "city":
                    $zipcodes = array_merge($zipcodes, $this->getAllZipcodesForCity($data));
                    break;
                case "county":
                    $zipcodes = array_merge($zipcodes, $this->getAllZipcodesForCounty($data));
                    break;
            }
        }

        $zipcodes = array_map('intval', $zipcodes);
        $zipcodes = array_unique($zipcodes);

        return $zipcodes;
    }

    /**
     * @param $data
     * @return array
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function getAllZipcodesForCity($data) {
        $zipcodes = [];

        if (isset($data[self::CITY]) && isset($data[self::STATE])) {
            $city = $data[self::CITY];
            $state = $data[self::STATE];
            $zipcodes = $this->zipcodeRepository->getZipcodesFromCity($city, $state);
        }

        return $zipcodes;
    }

    /**
     * @param $data
     * @return array
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function getAllZipcodesForCounty($data) {
        $zipcodes = [];

        if (isset($data[self::COUNTY]) && isset($data[self::STATE])) {
            $county = $data[self::COUNTY];
            $state = $data[self::STATE];
            $zipcodes = $this->zipcodeRepository->getZipcodesFromCounty($county, $state);
        }

        return $zipcodes;
    }

    /**
     * @param $user
     * @param $zipcodes
     *
     * @author Craig Giles < craig@gilesc.com >
     */
    private function backdateAllZipcodes($user, $zipcodes) {
        foreach ($zipcodes as $zip) {
            $sub = new Subscription($user, $zip);
            $this->subscriptionRepository->save($sub);
            $this->backdate($user, $sub);
        }
    }

    /**
     * @param \redhotmayo\model\User $user
     * @param $sub Subscription
     *
     * @return bool
     * @author Craig Giles < craig@gilesc.com >
     */
    private function backdate(User $user, Subscription $sub) {
        //get all master accounts in the given zipcode for the last X days
        $accounts = $this->accountRepository->findAllAccountsForZipcode($sub->getZipCode(), self::BACKDATE_DAYS);

        if (isset($accounts) && is_array($accounts)) {
            foreach($accounts as $account) {
                $acct = Account::FromArray($account);
                $this->accountRepository->subscribeAccountToUserId($acct, $user->getUserId());
            }
        }
    }
}
