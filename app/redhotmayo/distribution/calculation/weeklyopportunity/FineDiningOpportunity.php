<?php namespace redhotmayo\distribution\calculation\weeklyopportunity;


use redhotmayo\distribution\calculation\WeeklyOpportunity;

class FineDiningOpportunity extends WeeklyOpportunity {
    const LOWER_CUTOFF = 30;
    const LOWER_MODIFIER_1 = 1;
    const LOWER_MODIFIER_2 = 7; // 364/2
    const LOWER_PERCENT = .35;

    const UPPER_MODIFIER_1 = 1.5;
    const UPPER_MODIFIER_2 = 7; // 364/2
    const UPPER_PERCENT = .25;

    /**
     * If average check is <= $15 use the following formula:
     * (Seats X Check Avg. X 1 X 364 / 52) X 35%
     *
     * If average check is > $15 use the following formula:
     * (Seats X Check Avg. X 1 X 364 / 52) X 25%
     */
    public function construct($seats, $checkAverage) {
        parent::__construct($seats, $checkAverage);
    }

    /**
     * @return double
     */
    public function getModifier1() {
        return $this->getAverageCheck() <= self::LOWER_CUTOFF ? self::LOWER_MODIFIER_1 : self::UPPER_MODIFIER_1;
    }

    /**
     * @return double
     */
    public function getModifier2() {
        return $this->getAverageCheck() <= self::LOWER_CUTOFF ? self::LOWER_MODIFIER_2 : self::UPPER_MODIFIER_2;
    }

    /**
     * @return double
     */
    public function getPercent() {
        return $this->getAverageCheck() <= self::LOWER_CUTOFF ? self::LOWER_PERCENT : self::UPPER_PERCENT;
    }
}