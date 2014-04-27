<?php

use Illuminate\Console\Command;
use redhotmayo\dataaccess\repository\ThrottleRegistrationRepository;
use Symfony\Component\Console\Input\InputArgument;

class RegistrationThrottleCommand extends Command {
    const KEY = 'key';
    const MAX = 'max';

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
        $key = $this->argument(self::KEY);
        $max = $this->argument(self::MAX);

        $this->throttle->addKey($key, $max);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array(self::KEY, InputArgument::REQUIRED, 'Key used to throttle'),
            array(self::MAX, InputArgument::REQUIRED, 'Max number of units to throttle'),
        );
    }
}
