<?php namespace redhotmayo\parser\subscription;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use redhotmayo\dataaccess\repository\RepositoryFactory;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\model\Subscription;
use redhotmayo\model\User;

class SubscriptionProcessor implements Processor {
    const TIME = '-1 month';

    const C_USERNAME = 'USERNAME';
    const C_CITIES = 'CITIES';
    const C_COUNTIES = 'COUNTIES';
    const C_ZIPCODES = 'ZIPCODES';

    /**
     * @var ZipcodeRepository $zipcodeRepo
     */
    private $zipcodeRepo;

    public function __construct(ZipcodeRepository $zipcodeRepo) {
        $this->zipcodeRepo = $zipcodeRepo;
    }

    public function process($array) {
        $time = strtotime(self::TIME);

        if (isset($array) && is_array($array)) {
            $subRepo = RepositoryFactory::GetSubscriptionRepository();
            $sub = new Subscription();

            foreach ($array as $subscription) {
                $zips = array();

                $username = $subscription[self::C_USERNAME];
                $cities = isset($subscription[self::C_CITIES]) ? $subscription[self::C_CITIES] : array();
                $counties = isset($subscription[self::C_COUNTIES]) ? $subscription[self::C_COUNTIES] : array();
                $zipcodes = isset($subscription[self::C_ZIPCODES]) ? $subscription[self::C_ZIPCODES] : array();

                $cities = explode(',', $cities);
                $counties = explode(',', $counties);
                $zipcodes = explode(',', $zipcodes);

                //goto the users table and find the userId corrisponding to the username $username
                $users = DB::table('users')
                           ->where('username', '=', $username)
                           ->get();

                if (!empty($users)) {
                    $user = User::FromStdClass($users[0]);
                    $zipcodes = array_map('intval', $zipcodes);
                    $zips = array_merge($zipcodes, $zips);

                    foreach ($cities as $city) {
                        //get all zipcodes for the city and add them here
                        $zips = array_merge($this->zipcodeRepo->getZipcodesFromCity($city), $zips);
                    }

                    foreach ($counties as $county) {
                        //get all zipcodes for the city and add them here
                        $zips = array_merge($this->zipcodeRepo->getZipcodesFromCounty($county), $zips);
                    }

                    $zips = array_unique($zips);

                    foreach ($zips as $zipcode) {
                        $sub->add($user, $zipcode);
                        $saved = $subRepo->save($sub);
                        if ($saved) {
                            $this->backdateAccounts($sub, $time);
                        }
                    }
                } else {
                    Log::info("{$username} not found in the users table.");
                }
            }
        }
    }

    private function backdateAccounts(Subscription $sub, $time) {
        //get all leads for $zipcode after $time, and assign a copy for $id
        $repo = RepositoryFactory::GetAccountRepository();
        $accounts = $repo->findAllAccountsForZipcode($sub->getZipCode(), $time);

        if (count($accounts) > 0) {
            foreach ($accounts as $account) {
                //TODO: Not sure we want to use this bool.. here if we need it tho
                $bool = $repo->subscribeAccountToUserId($account, $sub->getUserID());

            }
        }
    }
}


/**
return array(
    array(
        'USERNAME' => 'testuser01',
        'CITIES' => array('Irvine', 'Anaheim', 'Hermosa Beach', 'Long Beach', 'Manhattan Beach', 'Los Angeles'),
    ),
    array(
        'USERNAME' => 'testuser02',
        'ZIPCODES' => array('91423', '93063', '91324', '91502'),
    ),
    array(
        'USERNAME' => 'testuser03',
        'CITIES' => array('San Francisco','San Jose','Alameda','Brisbane', 'Oakland'),
    ),
);
 */