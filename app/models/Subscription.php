<?php


class Subscription {
    private $userId;
    private $zipCode;

    /**
     * @return mixed
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @return int
     */
    public function getZipCode() {
        return $this->zipCode;
    }

    public function add($userId, $zipCode) {
        $this->userId = $userId;
        $this->zipCode = intval($zipCode);
    }
}
