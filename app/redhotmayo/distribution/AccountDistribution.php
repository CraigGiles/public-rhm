<?php namespace redhotmayo\distribution;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use redhotmayo\api\GoogleMapsAPI;
use redhotmayo\api\SmartyStreetsAPI;
use redhotmayo\api\TexasAMAPI;
use redhotmayo\dataaccess\repository\CuisineRepository;
use redhotmayo\dataaccess\repository\RepositoryFactory;
use redhotmayo\dataaccess\repository\FoodServicesRepository;
use redhotmayo\dataaccess\repository\sql\UserRepositorySQL;
use redhotmayo\library\CuisineMapper;
use redhotmayo\library\ExcelParser;
use redhotmayo\library\FoodMap;
use redhotmayo\library\Timer;
use redhotmayo\model\Account;
use redhotmayo\notifications\GooglePushNotification;
use redhotmayo\parser\AccountParserS2;

class AccountDistribution extends Distribution {
    private $timer;

    public function __construct() {
        $this->timer = new Timer();
    }

    public function loadFromFile($filename) {
        parent::loadFromFile($filename);

        $this->leadDistribution();
    }

    /**
     * @throws \Exception
     * @internal param $filename
     */
    private function leadDistribution() {
        $filename = $this->getFileName();
        $this->timer->startTimer();

        Log::info('Parsing XLSX file...');
        $smartyStreets = new SmartyStreetsAPI();
        $tam = new TexasAMAPI();
        $google = new GoogleMapsAPI();
        $s2parser = new AccountParserS2($tam, $smartyStreets, $google);
        $function = array($s2parser, AccountParserS2::EXCEL_PROCESSOR);
        $excelParser = new ExcelParser();
        $accounts = $excelParser->parse($filename, $function);

        if (!isset($accounts) || !is_array($accounts)) {
            throw new Exception("Error with excel parsing. Results not set or not an array");
        }

        if (count($accounts) == 0) {
            App::info('No records found in XLSX file.');
            return;
        }




        $accountRepo = RepositoryFactory::GetAccountRepository();
        Log::info('Saving all records...');
        $unsaved = $accountRepo->saveAll($accounts);

        Log::info('Distributing leads to users...');
        $undistributed = $accountRepo->distributeAccountsToUsers($accounts);

        $newAccounts = $accountRepo->allAccountsDistributedToday();

        $userIds = [];

        foreach ($newAccounts as $acct) {
            $userIds[] = $acct['userId'];
        }

        $userIds = array_unique($userIds);
        $userRepo = new UserRepositorySQL();
        $users = [];
        foreach ($userIds as $uid) {
            $users[] = $userRepo->find(['id' => $uid]);
        }

        $data = ['notificationType' => 'newLeads'];

        foreach ($users as $user) {
            (new GooglePushNotification())->send($user, $data);
        }

        /** @var Account $error */
        foreach ($unsaved as $error) {
            //TODO: LOGGER: Log the accounts that were unable to save so they can be re-processed
            Log::error('Unsaved account to accounts table: ' . $error->getAccountName());
        }

        /** @var Account $error */
        foreach ($undistributed as $error) {
            //TODO: LOGGER: Log the accounts that were unable to save so they can be re-processed
            Log::error('Undistributed accounts to users: ' . $error->getAccountName());
        }
    }
}
