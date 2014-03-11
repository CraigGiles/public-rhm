<?php namespace redhotmayo\distribution\calculation;

use redhotmayo\exception\NullArgumentException;
use redhotmayo\parser\AverageCheck;

abstract class WeeklyOpportunity {
    /** @var  AverageCheck $averageCheckParser */
    private $averageCheckParser;

    /** @var int $seats */
    private $seats;

    /** @var double $averageCheck */
    private $averageCheck;

    public function __construct($seats, $averageCheck) {
        //do conversion here
        $parser = $this->getAverageCheckParser();

        //set seats and average check
        $this->setSeats($seats);
        $this->setAverageCheck($parser->parse($averageCheck));
    }

    abstract function getModifier1();
    abstract function getModifier2();
    abstract function getPercent();

    /**
     * @param AverageCheck $averageCheckParser
     */
    public function setAverageCheckParser(AverageCheck $averageCheckParser) {
        $this->averageCheckParser = $averageCheckParser;
    }

    /**
     * @return AverageCheck
     */
    public function getAverageCheckParser() {
        if (!isset($this->averageCheckParser)) {
            $this->averageCheckParser = new AverageCheck();
        }

        return $this->averageCheckParser;
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


    public function calculate() {
        $seats = $this->getSeats();
        $ave = $this->getAverageCheck();

        return $seats * $ave * $this->getModifier1() * $this->getModifier2() * $this->getPercent();
    }

    protected function checkIfSet($value) {
        if (!isset($value)) {
            throw new NullArgumentException();
        }
    }
}