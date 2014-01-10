<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ProcessLeadsCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'accounts:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process a set of leads from an xlsx spreadsheet.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        $timer = new Timer();
        $filename = $this->argument('filename');

        $timer->startTimer();
        if (!file_exists($filename)) {
            throw new InvalidArgumentException('Invalid file name');
        }

        $this->info('Parsing XLSX file...');
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

        //TODO: Map food styles correctly (Need information from Don)
        //TODO: REFACTOR: move this to utility layer
        // Map food styles to one of 7 possabilities (Asian, Mexican, etc)
        $this->info('Mapping food styles...');
        $tmpFoodMap = array(
            'MEXICAN' => 'MEXICAN',
            'N/A' => 'OTHER',
            'KOREAN' => 'ASIAN',
            'BBQ' => 'AMERICAN',
            'CHICKEN' => 'AMERICAN',
            'CAJUN' => 'AMERICAN'
        );

        $this->mapFoodStyles($accounts, $tmpFoodMap);

        $this->info('Saving all records...');
        $accountDAO = DataAccessObject::GetAccountDAO();
        $unsaved = $accountDAO->saveAll($accounts);

        $this->info('Distributing leads to users...');

        $repo = RepositoryFactory::GetAccountRepository();
        $undistributed = $repo->distributeAccountsToUsers($accounts);

        // How much time did this operation take?
        $time = $timer->stopTimer();
        $time = number_format((float)($time / 60), 2, '.', '');

        $accountsCount = count($accounts);
        $unsavedCount = count($unsaved);
        $undistributedCount = count($undistributed);
        $this->info("Lead processing completed... Runtime: {$time} minutes.");
        $this->info("{$accountsCount} processed.");
        $this->info("{$unsavedCount} were unable to be saved into the master database.");
        $this->info("{$undistributedCount} were unable to be distributed.");

        //$this->info("All unsaved accounts have been appended to error.txt");

        $outputFile = array();
//         foreach ($unsavedMaster as $error) {
//             //TODO: LOGGER: Log the accounts that were unable to save so they can be re-processed
//             $outputFile[] = $error->getAccountName();
//             file_put_contents("../error.txt", $outputFile);
//         }
//

    }

    private function makeObjectsFromRecords($records) {
        $accounts = array();

        foreach ($records as $value) {
            $acc = new Account();

            $acc->setAccountName($value['OPERATION']);
            $acc->setContactName($value['NAME'] . " " . $value['LASTNAME']);
            $acc->setPhone($value['PHONE']);
            $acc->setEmailAddress($value['EMAIL']);
            $acc->setSeatCount($value['NOSEATS']);
            $acc->setServiceType($value['SERVICETYPE']);
            $acc->setCuisineType($value['MENU']);
            $acc->setOperatorType($value['CATEGORY']);
            $acc->setOpenDate($value['OPENDATE']);

            $note = new Note();
            $note->setText($value['DESCRIBE']);
            $note->setAuthor('redhotMAYO');
            $note->setAction('RHM Import');
            $acc->addNote($note);

            //do address checking here
            $address = $this->processAddressInformation($value);
            $acc->setAddress($address);

            // calculate the weekly opportunity
            $acc->calculateWeightedOpportunity();

            $accounts[] = $acc;

            if (count($accounts) > 25)  break;
        }

        return $accounts;
    }

    private function mapFoodStyles($accounts, $foodMap) {
        foreach ($accounts as $account) {
            $menu = $account->getCuisineType();
            if (!empty($menu)) {
                $cuisine = 'OTHER';
                if (array_key_exists($account->getCuisineType(), $foodMap)) {
                    $cuisine = strtoupper($foodMap[$account->getCuisineType()]);
                }
                $account->setCuisineType($cuisine);
            }
        }
    }

//    private function processAddressInformation($array) {
//        //TODO: REFACTOR: Refactor this to use model layer / utility layer
//        $sstreets = new SmartyStreetsAPI();
//        $tam = new TexasAMAPI();
//
//        $street1 = $array['ADDRESS'];
//        $city = $array['CITY'];
//        $county = $array['COUNTY'];
//        $state = $array['STATE'];
//        $zipcode = $array['ZIPCODE'];
//        $maxcanidates = 10;
//
//        echo " > Processing address: {$street1}" . PHP_EOL;
//
//
//        if (isset($sstreetsAddress)) {
//            $address = $sstreetsAddress;
//        } else {
//            $address = $tam->standardizeAddress($street1, $city, $state, $zipcode);
//        }
//
//        //geocode the location
//        //TODO: BUG: GoogleMapAPI not working
////        $google = new GoogleMapsAPI();
////        $geocoded = $google->geocode($address);
////        $address->setGoogleGeocoded($geocoded);
//        $address->setGoogleGeocoded(false);
//
//        return $address;
//    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array('filename', InputArgument::REQUIRED, 'XLSX file to be processed.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return array(
            array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}
