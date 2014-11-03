<?php namespace redhotmayo\distribution\report;

/**
 * Class DistributionSummary
 * @package redhotmayo\distribution\report;
 * @author jweyer <jweyer@snap-software.com>
 * @date 10/2014
 */
class DistributionSummary {

	// counts
	private $filesDetected = 0;
    private $filesProcessed = 0;
    private $filesUnprocessed = 0;
    private $leadsDetected = 0;
    private $leadsProcessed = 0;
    private $leadsUnprocesed = 0;
    private $leadsUnsaved = 0;
    private $leadsUndistributed = 0;
    private $leadsProcessedPercentage = 0;
    private $leadsUnprocessedPercentage = 0.00;
    private $leadsUnsavedPercentage = 0.00;
    private $leadsUndistributedPercentage = 0.00;

    // date and filenames
    private $processedDate;
    private $processedFiles = [];
    private $unprocessedFiles = [];

    public static function FromArray($summary) {
        return DistributionSummary::FromStdClass(json_decode(json_encode($report)));
    }

    public static function FromStdClass($summary) {

        $filesDetected = isset($summary->filesDetected) ? $summary->filesDetected : null;
        $filesProcessed = isset($summary->filesProcessed) ? $summary->filesProcessed : null;
        $filesUnprocessed = isset($summary->filesUnprocessed) ? $summary->filesUnprocessed : null;
        $leadsDetected = isset($summary->leadsDetected) ? $summary->leadsDetected : null;
        $leadsProcessed = isset($summary->leadsProcessed) ? $summary->leadsProcessed : null;
        $leadsUnprocessed = isset($summary->leadsUnprocessed) ? $summary->leadsUnprocessed : null;
        $leadsUnsaved = isset($summary->leadsUnsaved) ? $summary->leadsUnsaved : null;
        $leadsUndistributed = isset($summary->leadsUndistributed) ? $summary->leadsUndistributed : null;
        $leadsProcessedPercentage = isset($summary->leadsProcessedPercentage) ? $summary->leadsProcessedPercentage : null;
        $leadsUnprocessedPercentage = isset($summary->leadsUnprocessedPercentage) ? $summary->leadsUnprocessedPercentage : null;
        $leadsUnsavedPercentage = isset($summary->leadsUnsavedPercentage) ? $summary->leadsUnsavedPercentage : null;
        $leadsUndistributedPercentage = isset($summary->leadsUndistributedPercentage) ? $summary->leadsUndistributedPercentage : null;

        $processedDate = isset($summary->processedDate) ? $summary->processedDate : null;
        $processedFiles = isset($summary->processedFiles) ? $summary->processedFiles : null;
        $unprocessedFiles = isset($summary->unprocessedFiles) ? $summary->unprocessedFiles : null;

        $obj = new DistributionSummary();
        $obj->setFilesDetected($filesDetected);
        $obj->setFilesProcessed($filesProcessed);
        $obj->setFilesUnprocessed($filesUnprocessed);
        $obj->setLeadsDetected($leadsDetected);
        $obj->setLeadsProcessed($leadsProcessed);
        $obj->setLeadsUnprocessed($leadsUnprocessed);
        $obj->setLeadsUnsaved($leadsUnsaved);
        $obj->setLeadsUndistributed($leadsUndistributed);
        $obj->setLeadsProcessedPercentage($leadsProcessedPercentage);
        $obj->setLeadsUnprocessedPercentage($leadsUnprocessedPercentage);
        $obj->setLeadsUnsavedPercentage($leadsUnsavedPercentage);
        $obj->setLeadsUndistributedPercentage($leadsUndistributedPercentage);

        $obj->setProcessedDate($processedDate);
        $obj->setProcessedFiles($processedFiles);
        $obj->setUnprocessedFiles($unprocessedFiles);

        return $obj;
    }

	public function getFilesDetected() {
        return $this->filesDetected;
    }

    /**
     * @param integer $filesDetected
     */
    public function setFilesDetected($filesDetected) {
        $this->filesDetected = $filesDetected;
    }

    public function getFilesProcessed() {
        return $this->filesProcessed;
    }

    /**
     * @param integer $filesProcessed
     */
    public function setFilesProcessed($filesProcessed) {
        $this->filesProcessed = $filesProcessed;
    }

	public function getFilesUnprocessed() {
		return $this->filesUnprocessed;
	}

	/**
	 * @param integer $filesUnprocessed
	 */
	public function setFilesUnprocessed($filesUnprocessed) {
		$this->filesUnprocessed = $filesUnprocessed;
    }

    public function getLeadsDetected() {
		return $this->leadsDetected;
	}

	/**
	 * @param integer $leadsDetected
	 */
	public function setLeadsDetected($leadsDetected) {
		$this->leadsDetected = $leadsDetected;
    }

	public function getLeadsProcessed() {
		return $this->leadsProcessed;
	}

	/**
	 * @param integer $leadsProcessed
	 */
	public function setLeadsProcessed($leadsProcessed) {
		$this->leadsProcessed = $leadsProcessed;
    }

    public function getLeadsUnprocessed() {
		return $this->leadsUnprocessed;
	}

	/**
	 * @param integer $leadsUnprocessed
	 */
	public function setLeadsUnprocessed($leadsUnprocessed) {
		$this->leadsUnprocessed = $leadsUnprocessed;
    }

    public function getLeadsUnsaved() {
		return $this->leadsUnsaved;
	}

	/**
	 * @param integer $leadsUnsaved
	 */
	public function setLeadsUnsaved($leadsUnsaved) {
		$this->leadsUnsaved = $leadsUnsaved;
    }

    public function getLeadsUndistributed() {
		return $this->leadsUndistributed;
	}

	/**
	 * @param integer $leadsUndistributed
	 */
	public function setLeadsUndistributed($leadsUndistributed) {
		$this->leadsUndistributed = $leadsUndistributed;
    }

    public function getLeadsProcessedPercentage() {
		return $this->leadsProcessedPercentage;
	}

	/**
	 * @param integer $leadsProcessedPercentage
	 */
	public function setLeadsProcessedPercentage($leadsProcessedPercentage) {
		$this->leadsProcessedPercentage = $leadsProcessedPercentage;
	}

	public function getLeadsUnprocessedPercentage() {
		return $this->leadsUnprocessedPercentage;
	}

	/**
	 * @param integer $leadsUnprocessedPercentage
	 */
	public function setLeadsUnprocessedPercentage($leadsUnprocessedPercentage) {
		$this->leadsUnprocessedPercentage = $leadsUnprocessedPercentage;
	}

	public function getLeadsUnsavedPercentage() {
		return $this->leadsUnsavedPercentage;
	}

	/**
	 * @param integer $leadsUnsavedPercentage
	 */
	public function setLeadsUnsavedPercentage($leadsUnsavedPercentage) {
		$this->leadsUnsavedPercentage = $leadsUnsavedPercentage;
	}

	public function getLeadsUndistributedPercentage() {
		return $this->leadsUndistributedPercentage;
	}

	/**
	 * @param integer $leadsUndistributedPercentage
	 */
	public function setLeadsUndistributedPercentage($leadsUndistributedPercentage) {
		$this->leadsUndistributedPercentage = $leadsUndistributedPercentage;
    }

    public function getProcessedDate() {
		return $this->processedDate;
	}

	/**
	 * @param date $processedDate
	 */
	public function setProcessedDate($processedDate) {
		$this->processedDate = $processedDate;
    }

    public function getProcessedFiles() {
		return $this->processedFiles;
	}

	/**
	 * @param array $processedFiles
	 */
	public function setProcessedFiles($processedFiles) {
		$this->processedFiles = $processedFiles;
    }

    public function getUnprocessedFiles() {
		return $this->unprocessedFiles;
	}

	/**
	 * @param array $unprocessedFiles
	 */
	public function setUnprocessedFiles($unprocessedFiles) {
		$this->unprocessedFiles = $unprocessedFiles;
    }

    public function toArray() {
        $json = (
			array(
				'processedDate' => $this->processedDate,
				'processedFiles' => $this->processedFiles->toArray(),
				'unprocessedFiles' => $this->unprocessedFiles->toArray(),
				'filesDetected' => $this->filesDetected,
				'filesProcessed' => $this->filesProcessed,
				'filesUnprocessed' => $this->filesUnprocessed,
				'leadsDetected' => $this->leadsDetected,
				'leadsProcessed' => $this->leadsProcessed,
				'leadsUnprocessed' => $this->leadsUnprocessed,
				'leadsUnsaved' => $this->leadsUnsaved,
				'leadsUndistributed' => $this->leadsUndistributed,
				'leadsProcessedPercentage' => $this->leadsProcessedPercentage,
				'leadsUnprocessedPercentage' => $this->leadsUnprocessedPercentage,
				'leadsUnsavedPercentage' => $this->leadsUnsavedPercentage,
				'leadsUndistributedPercentage' => $this->leadsUndistributedPercentage
			)
        );
        return $json;
    }
}
