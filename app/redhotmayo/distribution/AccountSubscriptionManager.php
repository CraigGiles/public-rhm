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
        SubscriptionRepository $subscriptionRepository, ZipcodeRepository $zipcodeRepository,
        AccountRepository $accountRepository
    ) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->zipcodeRepository = $zipcodeRepository;
        $this->accountRepository = $accountRepository;

        $this->notification = new GooglePushNotification();
    }

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
        //get all zipcodes for 'type' in state 'state'
        $zipcodes = $this->getZipcodesForRegions($dataset);

        /**
         * Does this stuff need to go here?
         */
//        //todo: calculate subscription value here (newSub - currentSub)
//        //$subDifference = Billing->Calculate(userId, newSubs) ?
//
//        //todo: if new subDifference is more expensive than old subscription (IE: positive number)
//        //todo: save in session and redirect to billing
//        //Session::put(self::SUBSCRIPTION . Auth::user()->id, $subs);
//
//        //todo otherwise, the subscription hasn't changed based on new areas. Save and send them off.
//        $subs = [];
//        foreach ($data as $sub) {
//            $subLocation = SubscriptionLocation::FromArray($sub);
//            $subs[] = SubscriptionLocation::FromArray($sub);
//        }

        $newSubs = false;

        foreach ($zipcodes as $zip) {
            $sub = new Subscription($user, $zip);
            $this->subscriptionRepository->save($sub);
            $subs = $this->backdateAccounts($sub);

            // if newSubs is false, keep trying to get new subscriptions
            if (!$newSubs) {
                $newSubs = $subs;
            }
        }

        if ($newSubs) {
            $this->notification->send($user, ['notificationType' => 'newLeads']);
        }
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
     * @param $sub Subscription
     *
     * @return bool
     * @author Craig Giles < craig@gilesc.com >
     */
    private function backdateAccounts(Subscription $sub) {
        $accounts = $this->accountRepository->findAllAccountsForZipcode($sub->getZipCode(), self::BACKDATE_DAYS);
        $newSub = false;

        if (isset($accounts) && is_array($accounts)) {
            foreach ($accounts as $account) {
                $acc = Account::FromArray($account);

                if ($this->accountRepository->subscribeAccountToUserId($acc, $sub->getUserID())) {
                    $newSub = true;
                }
            }
        }

        return $newSub;
    }
}
