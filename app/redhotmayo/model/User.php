<?php namespace redhotmayo\model;

use Illuminate\Auth\Reminders\RemindableInterface;

class User extends DataObject implements RemindableInterface {
    private $username;
    private $password;
    private $email;
    private $emailVerified;

    public function getUserId() {
        return $this->getId();
    }

    public function setUserId($id) {
        $this->setId($id);
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $emailVerified
     */
    public function setEmailVerified($emailVerified) {
        $this->emailVerified = (bool)$emailVerified;
    }

    /**
     * @return mixed
     */
    public function getEmailVerified() {
        return $this->emailVerified;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username) {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    public static function FromStdClass($values) {
        $usr = new User();
        $email = isset($values->email) ? $values->email : null;
        $username = isset($values->username) ? $values->username : null;
        $id = isset($values->id) ? $values->id : null;

        $usr->setEmail($email);
        $usr->setUsername($username);
        $usr->setId($id);

        return $usr;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        dd($this);
        return $this->email;
    }
}