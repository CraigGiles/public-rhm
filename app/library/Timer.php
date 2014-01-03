<?php

/**
 * Class Timer
 * @author Craig Giles <craig@gilesc.com>
 * @date 12/2013
 */
class Timer {
    private $starttime;
    private $endtime;

    public function __construct() {
        $this->starttime = 0;
        $this->endtime = 0;
    }
    public function startTimer() {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $this->starttime = $mtime;
    }

    public function stopTimer() {
        $mtime = microtime();
        $mtime = explode(" ",$mtime);
        $mtime = $mtime[1] + $mtime[0];
        $this->endtime = $mtime;
        $totaltime = ($this->endtime - $this->starttime);
        return $totaltime;
    }

}