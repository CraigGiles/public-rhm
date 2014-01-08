<?php


class Subscription {
    private $user;
    private $zipCode;

    /**
     * @return mixed
     */
    public function getuser() {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getZipCode() {
        return $this->zipCode;
    }

    public function add($user, $zipCode) {
        $this->userId = $user;
        $this->zipCode = intval($zipCode);
    }
}
