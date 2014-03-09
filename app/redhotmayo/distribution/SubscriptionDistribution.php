<?php namespace redhotmayo\distribution;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use redhotmayo\dataaccess\repository\RepositoryFactory;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\library\ExcelParser;
use redhotmayo\library\Timer;
use redhotmayo\model\Account;
use redhotmayo\model\Subscription;
use redhotmayo\model\User;

class SubscriptionDistribution extends Distribution {
    const BACKDATE_DAYS = 30;
    private $zipcodeRepo;
    private $timer;

    public function __construct(ZipcodeRepository $zipcodeRepo) {
        $this->timer = new Timer();
        $this->zipcodeRepo = $zipcodeRepo;
    }

    public function loadFromFile($filename) {
        parent::loadFromFile($filename);

        $this->distribute();
    }

    private function distribute() {
        $filename = $this->getFileName();

        $function = array($this, 'process');
        $excelParser = new ExcelParser();
        $excelParser->parse($filename, $function);
    }

    public function process($records) {

        if (!isset($records) || !is_array($records)) {
            throw new Exception("Error with excel parsing. Results not set or not an array");
        }

        if (count($records) == 0) {
            App::info('No records found in XLSX file.');
            return [];
        }

        $subRepo = RepositoryFactory::GetSubscriptionRepository();
        $sub = new Subscription();
        foreach ($records as $subscription) {
            $zips = array();

            $username = $subscription['USERNAME'];
            $cities = isset($subscription['CITIES']) ? $subscription['CITIES'] : array();

            if (!is_array($cities)) {
                $cities = explode(',', $cities);
                $cities = array_map('trim', $cities);
            }

            $zipcodes = isset($subscription['ZIPCODES']) ? $subscription['ZIPCODES'] : array();

            if (!is_array($zipcodes)) {
                $zipcodes = explode(',', $zipcodes);
                $zipcodes = array_map('trim', $zipcodes);
            }

            //goto the users table and find the userId corrisponding to the username $username
            $user = DB::table('users')
                       ->where('username', '=', $username)
                       ->first();

            if (isset($user)) {
                $user = User::FromStdClass($user);
                $zipcodes = !is_array($zipcodes) ? [$zipcodes] : $zipcodes;
                $zips = array_merge($zipcodes, $zips);

                foreach ($cities as $city) {
                    //get all zipcodes for the city and add them here
                    $zips = array_merge($this->zipcodeRepo->getZipcodesFromCity($city), $zips);
                }

                $zips = array_unique($zips);
                $zips = array_map('intval', $zips);

                foreach ($zips as $zipcode) {
                    $sub->add($user, $zipcode);
                    $saved = $subRepo->save($sub);
                    if ($saved) {
                        $this->backdateAccounts($sub, self::BACKDATE_DAYS);
                    }
                }
            } else {
                Log::info("{$username} not found in the users table.");
            }
        }
    }

    private function backdateAccounts(Subscription $sub, $days) {
        //get all leads for $zipcode after $time, and assign a copy for $id
        $repo = RepositoryFactory::GetAccountRepository();
        $accounts = $repo->findAllAccountsForZipcode($sub->getZipCode(), $days);

        if (isset($accounts) && is_array($accounts) && count($accounts) > 0) {
            foreach ($accounts as $account) {
                $acc = Account::FromArray($account);
                $repo->subscribeAccountToUserId($acc, $sub->getUserID());
            }
        }
    }
}