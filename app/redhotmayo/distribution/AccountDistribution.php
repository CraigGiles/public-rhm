<?php namespace redhotmayo\distribution;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use redhotmayo\api\GoogleMapsAPI;
use redhotmayo\api\SmartyStreetsAPI;
use redhotmayo\api\TexasAMAPI;
use redhotmayo\dataaccess\repository\RepositoryFactory;
use redhotmayo\library\CuisineMapper;
use redhotmayo\library\ExcelParser;
use redhotmayo\library\FoodMap;
use redhotmayo\library\Timer;
use redhotmayo\parser\AccountParserS2;

class AccountDistribution {

    public function loadFromFile($filename) {
        $this->leadDistribution($filename);
    }

    /**
     * @param $filename
     * @throws InvalidArgumentException
     */
    private function leadDistribution($filename) {
        $timer = new Timer();

        $timer->startTimer();
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('Invalid file name');
        }

        Log::info('Parsing XLSX file...');
        $smartyStreets = new SmartyStreetsAPI();
        $tam = new TexasAMAPI();
        $google = new GoogleMapsAPI();
        $s2parser = new AccountParserS2($tam, $smartyStreets, $google);
        $function = array($s2parser, AccountParserS2::EXCEL_PROCESSOR);
        $excelParser = new ExcelParser();
        $accounts = $excelParser->parse($filename, $function);

        if (count($accounts) == 0) {
            App::info('No records found in XLSX file.');
            return;
        }

        $map = new FoodMap();
        $cuisine = new CuisineMapper($map);
        foreach ($accounts as $account) {
            $type = $cuisine->mapCuisine($account->getCuisineType());
            $account->setCuisineType($type);
        }

        $accountRepo = RepositoryFactory::GetAccountRepository();
        Log::info('Saving all records...');
        $unsaved = $accountRepo->saveAll($accounts);

        Log::info('Distributing leads to users...');
        $undistributed = $accountRepo->distributeAccountsToUsers($accounts);

        // How much time did this operation take?
        $time = $timer->stopTimer();
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