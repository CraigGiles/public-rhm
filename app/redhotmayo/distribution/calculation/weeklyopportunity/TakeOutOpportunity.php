<?php namespace redhotmayo\distribution\calculation\weeklyopportunity;

use redhotmayo\distribution\calculation\WeeklyOpportunity;

class TakeOutOpportunity extends WeeklyOpportunity {
    const MODIFIER_1 = 3;
    const MODIFIER_2 = 7; // 364/52
    const PERCENT = .35;

    /**
     * Quick Service Weekly Opportunity Calculation
     *
     * (Seats X Check Avg. X 3 X 364 / 52) X 35%
     */
    public function construct($seats, $checkAverage) {
        parent::__construct($seats, $checkAverage);
    }

    /**
     * @return double
     */
    public function getModifier1() {
        return self::MODIFIER_1;
    }

    /**
     * @return double
     */
    public function getModifier2() {
        return self::MODIFIER_2;
    }

    /**
     * @return double
     */
    public function getPercent() {
        return self::PERCENT;
    }
}