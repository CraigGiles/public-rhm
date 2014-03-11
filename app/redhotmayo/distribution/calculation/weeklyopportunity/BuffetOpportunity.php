<?php namespace redhotmayo\distribution\calculation\weeklyopportunity;

use redhotmayo\distribution\calculation\WeeklyOpportunity;

class BuffetOpportunity extends WeeklyOpportunity {
    const MODIFIER_1 = 3;
    const MODIFIER_2 = 7; // 364/2
    const PERCENT = .405;

    /**
     * (Seats X Check Avg. X 3 X 364 / 52) X 40.50%
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