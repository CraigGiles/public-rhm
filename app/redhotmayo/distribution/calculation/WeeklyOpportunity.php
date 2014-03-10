<?php namespace redhotmayo\distribution\calculation;

use redhotmayo\exception\NullArgumentException;

abstract class WeeklyOpportunity {
    private $seats;
    private $averageCheck;
    private $percent;

    public function __construct() {
        $this->seats = 0;
        $this->averageCheck = 0;
        $this->percent = 0;
    }

    /**
     * @param double $averageCheck
     */
    public function setAverageCheck($averageCheck) {
        $this->checkIfSet($averageCheck);
        $this->averageCheck = (double)$averageCheck;
    }

    /**
     * @return double
     */
    public function getAverageCheck() {
        return $this->averageCheck;
    }

    /**
     * @param double $percent
     */
    public function setPercent($percent) {
        $this->checkIfSet($percent);
        $this->percent = (double)$percent;
    }

    /**
     * @return double
     */
    public function getPercent() {
        return $this->percent;
    }

    /**
     * @param int $seats
     */
    public function setSeats($seats) {
        $this->checkIfSet($seats);
        $this->seats = (int)$seats;
    }

    /**
     * @return int
     */
    public function getSeats() {
        return $this->seats;
    }

    protected function checkIfSet($value) {
        if (!isset($value)) {
            throw new NullArgumentException();
        }
    }
}