<?php

use Illuminate\Console\Command;
use redhotmayo\distribution\AccountsProcessor;
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
    protected $description = 'Processes a set of XLSX files, each containing a set of new leads.';

    /**
     * Create a new command instance.
     *
     * @return \ProcessAccountsCommand
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

		$inputDirectory = $this->argument('inputDirectory');
		$outputDirectory = $this->argument('outputDirectory');
		$emailAddresses = explode(',', $this->argument('emailAddresses'));

		$processor = new AccountsProcessor($inputDirectory, $outputDirectory, $emailAddresses);
		$processor->process();
    }


    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments() {
        return array(
            array('inputDirectory', InputArgument::REQUIRED, 'The input directory contains the XLSX files.'),
            array('outputDirectory', InputArgument::REQUIRED, 'The output directory where the XLSX files are moved after processing.'),
            array('emailAddresses', InputArgument::REQUIRED, 'The comma-separated list of email addresses to send the results.'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions() {
        return [];
    }
}
