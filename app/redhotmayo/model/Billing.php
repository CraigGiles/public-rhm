<?php  namespace redhotmayo\model;

use redhotmayo\utility\Arrays;

class Billing extends DataObject {
    const BILLABLE_ID = 'billableId';
    const ACTIVE = 'active';
    const PLAN = 'plan';
    const LAST_FOUR = 'lastFour';
    const CURRENT_PERIOD_END = 'currentPeriodEnd';
    const TRIAL_ENDS_AT = 'trialEndsAt';

    private $billableId;
    private $active;
    private $plan;
    private $lastFour;
    private $currentPeriodEnd;
    private $trialEndsAt;

    function __construct($active, $currentPeriodEnd, $billableId, $lastFour, $plan, $trialEndsAt) {
        $this->active = $active;
        $this->currentPeriodEnd = $currentPeriodEnd;
        $this->billableId = $billableId;
        $this->lastFour = $lastFour;
        $this->plan = $plan;
        $this->trialEndsAt = $trialEndsAt;
    }

    public static function createWithData($array) {
        return new self(
            Arrays::GetValue($array, self::ACTIVE, null),
            Arrays::GetValue($array, self::CURRENT_PERIOD_END, null),
            Arrays::GetValue($array, self::BILLABLE_ID, null),
            Arrays::GetValue($array, self::LAST_FOUR, null),
            Arrays::GetValue($array, self::PLAN, null),
            Arrays::GetValue($array, self::TRIAL_ENDS_AT, null)
        );
    }

    public function setActive($active) {
        $this->active = $active;
    }

    public function getActive() {
        return $this->active;
    }

    public function setCurrentPeriodEnd($currentPeriodEnd) {
        $this->currentPeriodEnd = $currentPeriodEnd;
    }

    public function getCurrentPeriodEnd() {
        return $this->currentPeriodEnd;
    }

    public function setBillableId($billableId) {
        $this->billableId = $billableId;
    }

    public function getBillableId() {
        return $this->billableId;
    }

    public function setLastFour($lastFour) {
        $this->lastFour = $lastFour;
    }

    public function getLastFour() {
        return $this->lastFour;
    }

    public function setPlan($plan) {
        $this->plan = $plan;
    }

    public function getPlan() {
        return $this->plan;
    }

    public function setTrialEndsAt($trialEndsAt) {
        $this->trialEndsAt = $trialEndsAt;
    }

    public function getTrialEndsAt() {
        return $this->trialEndsAt;
    }
}
