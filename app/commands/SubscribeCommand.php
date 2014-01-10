<?php

use Illuminate\Console\Command;
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
        $time = strtotime("-1 month");
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

        $subRepo = RepositoryFactory::GetSubscriptionRepository();
        $sub = new Subscription();
        foreach ($records as $subscription) {
            $zips = array();

            $username = $subscription['USERNAME'];
            $cities = isset($subscription['CITIES']) ? $subscription['CITIES'] : array();
            $zipcodes = isset($subscription['ZIPCODES']) ? $subscription['ZIPCODES'] : array();

            //goto the users table and find the userId corrisponding to the username $username
            $userId = DB::table('users')
                ->select('id')
                ->where('username', '=', $username)
                ->get();

            if (!empty($userId)) {
                $userId = $userId[0]->id;
                $zipcodes = array_map('intval', $zipcodes);
                $zips = array_merge($zipcodes, $zips);

                foreach ($cities as $city) {
                    //get all zipcodes for the city and add them here
                    $zips = array_merge($this->lookupCityZipCodes($city), $zips);
                }

                $zips = array_unique($zips);

                foreach ($zips as $zipcode) {
                    $sub->add($userId, $zipcode);
                    $saved = $subRepo->save($sub);
                    if ($saved) {
                        $this->backdateAccounts($sub, $time);
                    }
                }
            } else {
                Log::info("{$username} not found in the users table.");
                $this->info("{$username} not found in the users table.");
            }
        }

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

    private function lookupCityZipCodes($city) {
        switch ($city) {
            case 'Irvine':
                return array(92606, 92612);
            case 'Anaheim':
                return array(92806, 92807);
            case 'Hermosa Beach':
                return array(90254);
            case 'Long Beach':
                return array(90802, 90803, 90810);
            case 'Manhattan Beach':
                return array(90266);
            case 'Los Angeles':
                return array(90020, 90048);
            case 'San Francisco':
                return array(94109, 94118, 94132, 94133);
            case 'San Jose':
                return array(95113, 95125, 95131);
            case 'Alameda':
                return array(94501);
            case 'Brisbane':
                return array(94005);
            case 'Oakland':
                return array(94607);
        }
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

    private function backdateAccounts(Subscription $sub, $time) {
        //get all leads for $zipcode after $time, and assign a copy for $id
        $accounts = AccountQueries::GetAllAccountsForZipAfterDate($sub->getZipCode(), $time);

        if (count($accounts) > 0) {
            foreach ($accounts as $account) {
                SubscriptionsQueries::SubscribeAccountToUser($account, $sub->getUserId());
            }
        }
    }

}
