<?php

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use redhotmayo\api\GoogleMapsAPI;
use redhotmayo\api\SmartyStreetsAPI;
use redhotmayo\api\TexasAMAPI;
use redhotmayo\dataaccess\repository\RepositoryFactory;
use redhotmayo\distribution\AccountDistribution;
use redhotmayo\library\CuisineMapper;
use redhotmayo\library\ExcelParser;
use redhotmayo\library\FoodMap;
use redhotmayo\library\Timer;
use redhotmayo\parser\AccountParserS2;
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
        $filename = $this->argument('filename');
        $acct = new AccountDistribution();
        $acct->loadFromFile($filename);
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
