<?php namespace redhotmayo\distribution\report;

/**
 * Class DistributionDetail
 * @package redhotmayo\distribution\report;
 * @author jweyer <jweyer@snap-software.com>
 * @date 10/2014
 */
class DistributionDetail {

	private $filename;

	// counts
	private $leadsDetected = 0;
    private $leadsProcessed = 0;
    private $leadsUnprocessed = 0;
    private $leadsUnsaved = 0;
    private $leadsUndistributed = 0;
    private $leadsProcessedPercentage = 0;
    private $leadsUnprocessedPercentage = 0;
    private $leadsUnsavedPercentage = 0;
    private $leadsUndistributedPercentage = 0;

    // accounts
    private $unsavedLeads = [];
    private $undistributedLeads = [];

    public static function FromArray($report) {
        return DistributionDetail::FromStdClass(json_decode(json_encode($report)));
    }

    public static function FromStdClass($detail) {

        $filename = isset($summary->filename) ? $summary->filename : null;
        $leadsDetected = isset($summary->leadsDetected) ? $summary->leadsDetected : null;
        $leadsProcessed = isset($summary->leadsProcessed) ? $summary->leadsProcessed : null;
        $leadsUnprocessed = isset($summary->leadsUnprocessed) ? $summary->leadsUnprocessed : null;
        $leadsUnsaved = isset($summary->leadsUnsaved) ? $summary->leadsUnsaved : null;
        $leadsUndistributed = isset($summary->leadsUndistributed) ? $summary->leadsUndistributed : null;
        $leadsProcessedPercentage = isset($summary->leadsProcessedPercentage) ? $summary->leadsProcessedPercentage : null;
        $leadsUnprocessedPercentage = isset($summary->leadsUnprocessedPercentage) ? $summary->leadsUnprocessedPercentage : null;
        $leadsUnsavedPercentage = isset($summary->leadsUnsavedPercentage) ? $summary->leadsUnsavedPercentage : null;
        $leadsUndistributedPercentage = isset($summary->leadsUndistributedPercentage) ? $summary->leadsUndistributedPercentage : null;

        $unsavedLeads = isset($detail->unsavedLeads) ? $detail->unsavedLeads : null;
        $undistributedLeads = isset($detail->undistributedLeads) ? $detail->undistributedLeads : null;

        $obj = new DistributionDetail();
        $obj->setFilename($filename);
        $obj->setLeadsDetected($leadsDetected);
        $obj->setLeadsProcessed($leadsProcessed);
        $obj->setLeadsUnprocessed($leadsUnprocessed);
        $obj->setLeadsUnsaved($leadsUnsaved);
        $obj->setLeadsUndistributed($leadsUndistributed);
        $obj->setLeadsProcessedPercentage($leadsProcessedPercentage);
        $obj->setLeadsUnprocessedPercentage($leadsUnprocessedPercentage);
        $obj->setLeadsUnsavedPercentage($leadsUnsavedPercentage);
        $obj->setLeadsUndistributedPercentage($leadsUndistributedPercentage);

        $obj->setUnsavedLeads($unsavedLeads);
        $obj->setUndistributedLeads($undistributedLeads);

        return $obj;
    }

	public function getFilename() {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename) {
        $this->filename = $filename;
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

    public function getUnsavedLeads() {
		return $this->unsavedLeads;
	}

	/**
	 * @param array $unsavedLeads
	 */
	public function setUnsavedLeads($unsavedLeads) {
		$this->unsavedLeads = $unsavedLeads;
    }

    public function getUndistributedLeads() {
		return $this->undistributedLeads;
	}

	/**
	 * @param array $undistributedLeads
	 */
	public function setUndistributedLeads($undistributedLeads) {
		$this->undistributedLeads = $undistributedLeads;
    }

    public function toArray() {
        $json = (
			array(
				'filename' => $this->filename,
				'leadsDetected' => $this->leadsDetected,
				'leadsProcessed' => $this->leadsProcessed,
				'leadsUnprocessed' => $this->leadsUnprocessed,
				'leadsUnsaved' => $this->leadsUnsaved,
				'leadsUndistributed' => $this->leadsUndistributed,
				'leadsProcessedPercentage' => $this->leadsProcessedPercentage,
				'leadsUnprocessedPercentage' => $this->leadsUnprocessedPercentage,
				'leadsUnsavedPercentage' => $this->leadsUnsavedPercentage,
				'leadsUndistributedPercentage' => $this->leadsUndistributedPercentage,
				'unsavedLeads' => $this->unsavedLeads->toArray(),
				'undistributedLeads' => $this->undistributedLeads->toArray()
			)
        );
        return $json;
    }
}
