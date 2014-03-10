<?php namespace redhotmayo\distribution\calculation;


class CasualDiningOpportunity extends WeeklyOpportunity {
    const LOWER_CUTOFF = 15;
    const LOWER_MODIFIER_1 = 1.5;
    const LOWER_MODIFIER_2 = 7; // 364/2
    const LOWER_PERCENT = .34;

    const UPPER_MODIFIER_1 = 1.5;
    const UPPER_MODIFIER_2 = 7; // 364/2
    const UPPER_PERCENT = .27;

    public function __construct($seats, $averageCheck) {
        //do conversion here

        //set seats and average check
        $this->setSeats($seats);
        $this->setAverageCheck($averageCheck);
    }

    function calculate() {
        if ($this->getAverageCheck() <= self::LOWER_CUTOFF) {
            $this->calculateLowerCutoff();
        } else {
            $this->calculateUpperCutoff();
        }
    }

    /**
     * If average check is <= $15 use the following formula:
     * (Seats X Check Avg. X 1.5 X 364 / 52) X 34%
     *
     * @return double
     */
    private function calculateLowerCutoff() {
        $seats = $this->getSeats();
        $ave = $this->getAverageCheck();

        return $seats * $ave * self::LOWER_MODIFIER_1 * self::LOWER_MODIFIER_2 * self::LOWER_PERCENT;
    }

    /**
     * If average check is > $15 use the following formula:
     * (Seats X Check Avg. X 1.5 X 364 / 52) X 27%
     *
     * @return double
     */
    private function calculateUpperCutoff() {
        $seats = $this->getSeats();
        $ave = $this->getAverageCheck();

        return $seats * $ave * self::UPPER_MODIFIER_1 * self::UPPER_MODIFIER_2 * self::UPPER_PERCENT;
    }
}