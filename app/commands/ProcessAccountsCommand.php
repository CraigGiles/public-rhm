<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ProcessAccountsCommand extends Command {

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

        $map = new FoodMap();
        $cuisine = new CuisineMapper($map);
        foreach ($accounts as $account) {
            $type = $cuisine->mapCuisine($account->getCuisineType());
            $account->setCuisineType($type);
        }

        $accountRepo = RepositoryFactory::GetAccountRepository();
        $this->info('Saving all records...');
        $unsaved = $accountRepo->saveAll($accounts);

        $this->info('Distributing leads to users...');
        $undistributed = $accountRepo->distributeAccountsToUsers($accounts);

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
