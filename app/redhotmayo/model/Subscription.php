<?php namespace redhotmayo\model;


class Subscription {
    /** @var  User $user */
    private $user;
    private $zipCode;

    public function __construct(User $user, $zipCode) {
        $this->add($user, $zipCode);
    }

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
     * Add a single user and zipcode
     *
     * @param User $user
     * @param $zipCode
     *
     * @deprecated
     * Once the subscription page is live than the XLSX subscription script will be deprecated and
     * this object will be converted to a different object model.
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
