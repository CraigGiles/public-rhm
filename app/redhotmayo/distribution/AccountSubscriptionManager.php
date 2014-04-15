<?php  namespace redhotmayo\distribution;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use redhotmayo\dataaccess\repository\SubscriptionRepository;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\model\Subscription;
use redhotmayo\model\User;

class AccountSubscriptionManager {
    const DATA_TYPE = 'type';
    const CITY = 'city';
    const STATE = 'state';
    const COUNTY = 'county';

    private $subscriptionRepository;
    private $zipcodeRepository;

    public function __construct(SubscriptionRepository $subscriptionRepository, ZipcodeRepository $zipcodeRepository) {
        $this->subscriptionRepository = $subscriptionRepository;
        $this->zipcodeRepository = $zipcodeRepository;
    }

    public function processNewUsersData(User $user) {
        $data = Session::get(Cookie::get('temp_id'));

        if (isset($data)) {
            $this->process($user, $data);
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

        foreach ($zipcodes as $zip) {
            $sub = new Subscription($user, $zip);
            $this->subscriptionRepository->save($sub);
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
            switch ($data[self::DATA_TYPE]) {
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
}
