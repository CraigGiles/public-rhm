<?php namespace redhotmayo\distribution\report;

/**
 * Class DistributionReport
 * @package redhotmayo\distribution\report;
 * @author jweyer <jweyer@snap-software.com>
 * @date 10/2014
 */
class DistributionReport {

    private $summary;
    private $details;

    public static function FromArray($report) {
        return DistributionReport::FromStdClass(json_decode(json_encode($report)));
    }

    public static function FromStdClass($report) {
        $summary = isset($report->summary) ? $report->summary : null;
        $details = isset($report->details) ? $report->details : null;

        $obj = new DistributionReport();
        $obj->setSummary($summary);
        $obj->setDetails($details);

        return $obj;
    }

    public function getSummary() {
        return $this->summary;
    }

    /**
     * @param mixed $summary
     */
    public function setSummary($summary) {
        $this->summary = $summary;
    }

    public function getDetails() {
		return $this->details;
	}

	/**
	 * @param array $details
	 */
	public function setDetails($details) {
		$this->details = $details;
    }

    public function toArray() {
        $json = (
			array(
				'summary' => $this->summary,
				'details' => $this->details->toArray(),

			)
        );
        return $json;
    }
}
