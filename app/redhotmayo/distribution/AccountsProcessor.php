<?php namespace redhotmayo\distribution;

use DateTime;
use DateTimeZone;
use Exception;
use FilesystemIterator;
use RegexIterator;
use Illuminate\Support\Facades\Log;
use redhotmayo\distribution\AccountDistribution;
use redhotmayo\distribution\report\DistributionReport;
use redhotmayo\distribution\report\DistributionSummary;
use redhotmayo\distribution\report\DistributionDetail;
use redhotmayo\mailers\DistributionReportMailer;

class AccountsProcessor {

	private $inputDirectory;
	private $outputDirectory;
	private	$emailAddresses;

	public function __construct($inputDirectory, $outputDirectory, $emailAddresses) {
		$this->inputDirectory = $inputDirectory;
		$this->outputDirectory = $outputDirectory;
		$this->emailAddresses = $emailAddresses;
	}

	public function process() {

		$files = $this->loadFiles();
		$count = count($files);
		if ($count == 0) {
			$message = 'No xls/xlsx files were found to process. Sleeping.';
			Log::info($message);
			return;
		}

		$message = vsprintf('Processing %s lead file(s).', $count);
		Log::info($message);

		$processedFiles = array();
		$unprocessedFiles = array();
		$details = array();

		foreach ($files as $file) {
			try {
				$distribution = new AccountDistribution();
				$result = $distribution->loadFromFile($file);
				$detail = $this->createDetail($result);
				$details[] = $detail;
				$processedFiles[] = $file->getFilename();
				$this->moveFile($file);
			} catch (Exception $exception) {
				$message = vsprintf('Unable to parse the xls/xlsx file [%s]: ', array($file));
				Log::error($message . $exception);
				$unprocessedFiles[] = $file->getFilename();
				$this->moveFile($file);
			}
		}

		$report = new DistributionReport();
		$summary = $this->createSummary($files, $processedFiles, $unprocessedFiles, $details);
		$report->setSummary($summary);
		$report->setDetails($details);

		$mailer = new DistributionReportMailer();
		$mailer->send($report, $this->emailAddresses);
	}

	private function loadFiles() {

		$iterator = new FilesystemIterator($this->inputDirectory);
		$filter = new RegexIterator($iterator, '/^.+\.(xls|xlsx)$/i');
		$files = array();
		foreach($filter as $entry) {
		    $files[] = $entry;
		}

		return $files;
	}

	private function moveFile($file) {
		$filename = $file->getFilename();
		rename($file, $this->outputDirectory . '/'. $filename);
	}

	private function createSummary($files, $processedFiles, $unprocessedFiles, $details) {

		$summary = new DistributionSummary();

		$processedDate = new DateTime();
		$processedDate->setTimeZone(new DateTimeZone('America/Los_Angeles'));

		$summary->setProcessedDate($processedDate);
		$summary->setProcessedFiles($processedFiles);
		$summary->setUnprocessedFiles($unprocessedFiles);
		$summary->setFilesDetected(count($files));
		$summary->setFilesProcessed(count($processedFiles));
		$summary->setFilesUnprocessed(count($unprocessedFiles));

		$aggregator = new DetailAggregator();
		$aggregator->aggregate($details);
		$summary->setLeadsDetected($aggregator->getLeadsDetected());
		$summary->setLeadsProcessed($aggregator->getLeadsProcessed());
		$summary->setLeadsUnprocessed($aggregator->getLeadsUnprocessed());
		$summary->setLeadsUnsaved($aggregator->getLeadsUnsaved());
		$summary->setLeadsUndistributed($aggregator->getLeadsUndistributed());
		$summary->setLeadsProcessedPercentage($aggregator->getLeadsProcessedPercentage());
		$summary->setLeadsUnprocessedPercentage($aggregator->getLeadsUnprocessedPercentage());
		$summary->setLeadsUnsavedPercentage($aggregator->getLeadsUnsavedPercentage());
		$summary->setLeadsUndistributedPercentage($aggregator->getLeadsUndistributedPercentage());

		return $summary;
	}

	private function createDetail($result) {

		$detail = new DistributionDetail();

		$detail->setFilename($result->getFilename());

		$leadsDetected = $result->getTotalAccounts();
		$detail->setLeadsDetected($leadsDetected);
		if ($leadsDetected > 0) {

			$leadsUnsaved = count($result->getUnsavedAccounts());
			$leadsUndistributed = count($result->getUndistributedAccounts());
			$leadsUnprocessed = $leadsUnsaved + $leadsUndistributed;
			$leadsProcessed = $leadsDetected - $leadsUnprocessed;
			$leadsProcessedPercentage = $leadsProcessed / $leadsDetected;
			$leadsUnprocessedPercentage = $leadsUnprocessed / $leadsDetected;
			$leadsUnsavedPercentage = $leadsUnsaved / $leadsDetected;
			$leadsUndistributedPercentage = $leadsUndistributed / $leadsDetected;

			$detail->setLeadsProcessed($leadsProcessed);
			$detail->setLeadsUnprocessed($leadsUnprocessed);
			$detail->setLeadsUnsaved($leadsUnsaved);
			$detail->setLeadsUndistributed($leadsUndistributed);
			$detail->setLeadsProcessedPercentage($leadsProcessedPercentage);
			$detail->setLeadsUnprocessedPercentage($leadsUnprocessedPercentage);
			$detail->setLeadsUnsavedPercentage($leadsUnsavedPercentage);
			$detail->setLeadsUndistributedPercentage($leadsUndistributedPercentage);

			$detail->setUnsavedLeads($result->getUnsavedAccounts());
			$detail->setUndistributedLeads($result->getUndistributedAccounts());
		}

		return $detail;
	}

}

class DetailAggregator {

	// counts
	private $leadsDetected = 0;
	private $leadsProcessed = 0;
	private $leadsUnprocessed = 0;
	private $leadsUnsaved = 0;
	private $leadsUndistributed = 0;
	private $leadsProcessedPercentage = 0;
	private $leadsUnprocessedPercentage = 0.00;
	private $leadsUnsavedPercentage = 0.00;
    private $leadsUndistributedPercentage = 0.00;

	public function aggregate($details) {

		foreach ($details as $detail) {
			$this->leadsDetected += $detail->getLeadsDetected();
			$this->leadsProcessed += $detail->getLeadsProcessed();
			$this->leadsUnprocessed += $detail->getLeadsUnprocessed();
			$this->leadsUnsaved += count($detail->getUnsavedLeads());
			$this->leadsUndistributed += count($detail->getUndistributedLeads());
		}

		if ($this->leadsDetected > 0) {
			$this->leadsProcessedPercentage = $this->leadsProcessed / $this->leadsDetected;
			$this->leadsUnprocessedPercentage = $this->leadsUnprocessed / $this->leadsDetected;
			$this->leadsUnsavedPercentage = $this->leadsUnsaved / $this->leadsDetected;
			$this->leadsUndistributedPercentage = $this->leadsUndistributed / $this->leadsDetected;
		}
	}

	public function getLeadsDetected() {
		return $this->leadsDetected;
	}

	public function getLeadsProcessed() {
		return $this->leadsProcessed;
	}

    public function getLeadsUnprocessed() {
		return $this->leadsUnprocessed;
	}

    public function getLeadsUnsaved() {
		return $this->leadsUnsaved;
	}

    public function getLeadsUndistributed() {
		return $this->leadsUndistributed;
	}

    public function getLeadsProcessedPercentage() {
		return $this->leadsProcessedPercentage;
	}

	public function getLeadsUnprocessedPercentage() {
		return $this->leadsUnprocessedPercentage;
	}

	public function getLeadsUnsavedPercentage() {
		return $this->leadsUnsavedPercentage;
	}

	public function getLeadsUndistributedPercentage() {
		return $this->leadsUndistributedPercentage;
	}
}
