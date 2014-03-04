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
use redhotmayo\library\CuisineMapper;
use redhotmayo\library\ExcelParser;
use redhotmayo\library\FoodMap;
use redhotmayo\library\Timer;
use redhotmayo\model\Account;
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
     * @param $filename
     * @throws InvalidArgumentException
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

        /** @var CuisineRepository $cuisineRepo */
        $cuisineRepo = App::make('redhotmayo\dataaccess\repository\CuisineRepository');
        $cuisine = $cuisineRepo->map('s2', 'CNT AM');

        /** @var Account $account */
        foreach ($accounts as $account) {
            $type = $cuisineRepo->map('s2', $account->getCuisineType());
            $account->setCuisineType($type->getCuisine());
            $account->setCuisineId($type->getCuisineId());
        }

        $accountRepo = RepositoryFactory::GetAccountRepository();
        Log::info('Saving all records...');
        $unsaved = $accountRepo->saveAll($accounts);

        Log::info('Distributing leads to users...');
        $undistributed = $accountRepo->distributeAccountsToUsers($accounts);

        // How much time did this operation take?
        $time = $this->timer->stopTimer();
        $time = number_format((float)($time / 60), 2, '.', '');

        $accountsCount = count($accounts);
        $unsavedCount = count($unsaved);
        $undistributedCount = count($undistributed);
//        $this->info("Lead processing completed... Runtime: {$time} minutes.");
//        $this->info("{$accountsCount} processed.");
//        $this->info("{$unsavedCount} were unable to be saved into the master database.");
//        $this->info("{$undistributedCount} were unable to be distributed.");

        //$this->info("All unsaved accounts have been appended to error.txt");

        $outputFile = array();
//         foreach ($unsavedMaster as $error) {
//             //TODO: LOGGER: Log the accounts that were unable to save so they can be re-processed
//             $outputFile[] = $error->getAccountName();
//             file_put_contents("../error.txt", $outputFile);
//         }
    }
}
