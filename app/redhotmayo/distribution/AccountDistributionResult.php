<?php namespace redhotmayo\distribution;

/**
 * Class AccountDistributionResult
 * @package redhotmayo\distribution
 * @author jweyer <jweyer@snap-software.com>
 * @date 10/2014
 */
class AccountDistributionResult {

	private $filename;
	private $totalAccounts;
	private $unsavedAccounts;
	private $undistributedAccounts;


	public function __construct($filename, $totalAccounts, $unsavedAccounts = null, $undistributedAccounts = null) {
		$this->filename = $filename;
		$this->totalAccounts = $totalAccounts;
		if (isset($unsavedAccounts)) {
			$this->unsavedAccounts = $unsavedAccounts;
		}
		if (isset($undistributedAccounts)) {
			$this->undistributedAccounts = $undistributedAccounts;
		}
	}

    public static function FromArray($report) {
        return AccountDistributionResult::FromStdClass(json_decode(json_encode($report)));
    }

    public static function FromStdClass($result) {

        $filename = isset($result->filename) ? $result->filename : null;
        $totalAccounts = isset($result->totalAccounts) ? $result->totalAccounts : null;
        $unsavedAccounts = isset($result->unsavedAccounts) ? $result->unsavedAccounts : null;
        $undistributedAccounts = isset($result->undistributedAccounts) ? $result->undistributedAccounts: null;

        $obj = new AccountDistributionResult();
        $obj->setFilename($filename);
        $obj->setTotalAccounts($totalAccounts);
        $obj->setUnsavedAccounts($unsavedAccounts);
        $obj->setUndistributedAccounts($undistributedAccounts);

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

    public function getTotalAccounts() {
		return $this->totalAccounts;
	}

	/**
	 * @param string $totalAccounts
	 */
	public function setTotalAccounts($totalAccounts) {
		$this->totalAccounts = $totalAccounts;
    }

    public function getUnsavedAccounts() {
		return $this->unsavedAccounts;
	}

	/**
	 * @param array $unsavedAccounts
	 */
	public function setUnsavedAccounts($unsavedAccounts) {
		$this->unsavedAccounts = $unsavedAccounts;
    }

    public function getUndistributedAccounts() {
		return $this->undistributedAccounts;
	}

	/**
	 * @param array $undistributedAccounts
	 */
	public function setUndistributedAccounts($undistributedAccounts) {
		$this->undistributedAccounts = $undistributedAccounts;
    }

    public function toArray() {
        $json = (
			array(
				'totalAccounts' => $this->totalAccounts,
				'unsavedAccounts' => $this->unsavedAccounts->toArray(),
				'undistributedAccounts' => $this->undistributedAccounts->toArray()
			)
        );
        return $json;
    }
}
