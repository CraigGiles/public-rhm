<?php

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use redhotmayo\dataaccess\repository\RepositoryFactory;
use redhotmayo\dataaccess\repository\sql\ZipcodeRepositorySQL;
use redhotmayo\dataaccess\repository\ZipcodeRepository;
use redhotmayo\library\ExcelParser;
use redhotmayo\library\Timer;
use redhotmayo\model\Subscription;
use redhotmayo\model\User;
use redhotmayo\parser\subscription\SubscriptionProcessor;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class ProcessLeadsCommand
 *
 * Subscribe users to cities and zipcodes based on their subscriptions. The XLSX spreadsheet should look like the following:
 *
 * USERNAME   | CITIES                     | ZIPCODES
 * -----------|----------------------------|--------------------------
 * testuser01 | Los Angeles, Simi Valley   |
 * -----------|----------------------------|--------------------------
 * testuser02 |                            | 90210, 93063, 93065
 * -----------|----------------------------|--------------------------
 * testuser03 | Sacramento                 | 53058
 * -----------|----------------------------|--------------------------
 *
 * testuser01 will be subscribed to ALL zipcodes located within LA and Simi Valley
 * testuser02 will be subscribed to only the 3 zipcodes mentioned
 * testuser03 will be subscribed to all Sacramento zipcodes in addition to 53058
 */
class SubscribeCommand extends Command {
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'accounts:subscribe';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse an XLSX spreadsheet that allows users to subscribe to cities or zipcodes';

    /**
     * @var ZipcodeRepository $zipcodeRepo
     */
    protected $zipcodeRepo;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->zipcodeRepo = App::make('redhotmayo\dataaccess\repository\ZipcodeRepository');
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
        $processor = new SubscriptionProcessor(App::make('redhotmayo\dataaccess\repository\ZipcodeRepository'));
        $function = array($processor, 'process');
        $excelParser = new ExcelParser();
        $excelParser->parse($filename, $function);

        // How much time did this operation take?
        $time = $timer->stopTimer();
        $time = number_format((float)($time / 60), 2, '.', '');
        $this->info("Subscription processes completed... Runtime: {$time} minutes.");

    }


    private function parseExcelFile($filename) {
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

//        foreach ($records as $record) {
//            $username = $record['USERNAME'];
//            $cities = $record['CITIES'];
//            $zipcodes = $record['ZIPCODES'];
//        }

//        $excel = new LeadParser($filename);
//        return $excel->parse();
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
