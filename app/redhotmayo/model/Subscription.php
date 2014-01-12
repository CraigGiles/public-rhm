<?php namespace redhotmayo\model;


class Subscription {
    /** @var  User $user */
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

    /**
     * @param User $user
     * @param $zipCode
     */
    public function add($user, $zipCode) {
        $this->user = $user;
        $this->zipCode = intval($zipCode);
    }

    public function getUserName() {
        return $this->user->getUsername();
    }

    public function getUserID() {
        return $this->user->getUserId();
    }
}
