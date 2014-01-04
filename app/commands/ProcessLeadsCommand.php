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
    protected $name = 'process-leads';

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
        $records = $this->parseExcelFile($filename);

         if (count($records) == 0) {
             $this->info('No records found in XLSX file.');
             return;
         }

         // Verify and fix any address records
         $this->info('Processing account information...');
         $accounts = $this->makeObjectsFromRecords($records);

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

         $this->info('Saving records to master database...');
         $eloq = new AccountRepository();
         $eloq->saveAll($accounts);
//         $masterAccountDAO = DataAccess::getDAO(DataAccessObject::MASTER_ACCOUNT);
//         $unsavedMaster = $masterAccountDAO->saveAll($accounts);

//         $this->info('Distributing leads to users...');
//         $accountDAO = DataAccess::getDAO(DataAccessObject::ACCOUNT);
//         $unsavedUsers = $accountDAO->saveAll($accounts);

         $time = $timer->stopTimer();
         $time = number_format((float)($time/60), 2, '.', '');

//         $unableCount = count($unsavedMaster);
         $numOfAccounts = count($accounts);
         $this->info("Lead processing completed... Runtime: {$time} minutes.");

         $outputFile = array();
//         foreach ($unsavedMaster as $error) {
//             //TODO: LOGGER: Log the accounts that were unable to save so they can be re-processed
//             $outputFile[] = $error->getAccountName();
//             file_put_contents("../error.txt", $outputFile);
//         }
//
        $this->info("${numOfAccounts} leads processed." );
//         $this->info("${numOfAccounts} leads processed. Unable to save {$unableCount} to the database.");

    }

    private function parseExcelFile($filename) {
        $excel = new ExcelParser($filename);
        return $excel->parse();
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

            break;
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

    private function processAddressInformation($array) {
        //TODO: REFACTOR: Refactor this to use model layer / utility layer
        $sstreets = new SmartyStreetsAPI();
        $tam = new TexasAMAPI();

        $street1 = $array['ADDRESS'];
        $city = $array['CITY'];
        $county = $array['COUNTY'];
        $state = $array['STATE'];
        $zipcode = $array['ZIPCODE'];
        $maxcanidates = 10;

        echo " > Processing address: {$street1}" . PHP_EOL;
        $sstreetsAddress = $sstreets->processAddresses($street1, null, $city, $county, $state, $zipcode, $maxcanidates);


        if (isset($sstreetsAddress)) {
            $address = $sstreetsAddress;
        } else {
            $address = $tam->standardizeAddress($street1, $city, $state, $zipcode);
        }

        //geocode the location
        //TODO: BUG: GoogleMapAPI not working
//        $google = new GoogleMapsAPI();
//        $geocoded = $google->geocode($address);
//        $address->setGoogleGeocoded($geocoded);

        return $address;
    }

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
