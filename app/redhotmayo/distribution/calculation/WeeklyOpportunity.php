<?php namespace redhotmayo\distribution\calculation;

use redhotmayo\distribution\calculation\weeklyopportunity\BuffetOpportunity;
use redhotmayo\distribution\calculation\weeklyopportunity\CasualDiningOpportunity;
use redhotmayo\distribution\calculation\weeklyopportunity\FastCasualOpportunity;
use redhotmayo\distribution\calculation\weeklyopportunity\FineDiningOpportunity;
use redhotmayo\distribution\calculation\weeklyopportunity\QuickServiceOpportunity;
use redhotmayo\distribution\calculation\weeklyopportunity\TakeOutOpportunity;
use redhotmayo\exception\NullArgumentException;
use redhotmayo\parser\AverageCheck;

abstract class WeeklyOpportunity {
    /**
     * @param $service
     * @param $seats
     * @param $averageCheck
     *
     * @return WeeklyOpportunity
     */
    public static function make($service, $seats, $averageCheck) {
        switch ($service) {
            case "Casual Dining":
                return new CasualDiningOpportunity($seats, $averageCheck);
            case "Fine Dining":
                return new FineDiningOpportunity($seats, $averageCheck);
            case "Buffet":
                return new BuffetOpportunity($seats, $averageCheck);
            case "Fast Casual":
                return new FastCasualOpportunity($seats, $averageCheck);
            case "Quick Service":
                return new QuickServiceOpportunity($seats, $averageCheck);
            case "Take-Out":
                return new TakeOutOpportunity($seats, $averageCheck);
        }
    }

    /** @var  AverageCheck $averageCheckParser */
    private $averageCheckParser;

    /** @var int $seats */
    private $seats;

    /** @var double $averageCheck */
    private $averageCheck;

    public function __construct($seats, $averageCheck) {
        //do conversion here
        $checkParser = $this->getAverageCheckParser();

        //set seats and average check
        $this->setSeats($seats);
        $this->setAverageCheck($checkParser->parse($averageCheck));
    }

    abstract function getModifier1();
    abstract function getModifier2();
    abstract function getPercent();

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

    /**
     * Calculate the Weekly opportunity based on given criteria
     *
     * @return double
     */
    public function calculate() {
        $seats = $this->getSeats();
        $ave = $this->getAverageCheck();

        return $seats * $ave * $this->getModifier1() * $this->getModifier2() * $this->getPercent();
    }

    /**
     * Checks if the value is set and throws a null exception if isset returns false
     *
     * @param $value
     * @throws \redhotmayo\exception\NullArgumentException
     */
    protected function checkIfSet($value) {
        if (!isset($value)) {
            throw new NullArgumentException();
        }
    }
}