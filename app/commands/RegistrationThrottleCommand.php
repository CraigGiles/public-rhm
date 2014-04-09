<?php

use Illuminate\Console\Command;
use redhotmayo\dataaccess\repository\ThrottleRegistrationRepository;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class RegistrationThrottleCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'throttle:registration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Throttle registration based on key';

    /** @var ThrottleRegistrationRepository $throttle */
    private $throttle;

    public function __construct() {
        parent::__construct();
        $this->throttle = App::make('ThrottleRegistrationRepository');
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        $key = $this->argument('key');
        $max = $this->argument('max');

        $this->throttle->addKey($key, $max);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array('key', InputArgument::REQUIRED, 'Key used to throttle'),
            array('max', InputArgument::REQUIRED, 'Max number of units to throttle'),
        );
    }
}