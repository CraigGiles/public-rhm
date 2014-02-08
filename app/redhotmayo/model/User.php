<?php namespace redhotmayo\model;

use Illuminate\Auth\Reminders\RemindableInterface;

class User extends DataObject implements RemindableInterface {
    public static function FromStdClass($values) {
        $usr = new User();
        $email = isset($values->email) ? $values->email : null;
        $username = isset($values->username) ? $values->username : null;
        $password = isset($values->password) ? $values->password : null;
        $id = isset($values->id) ? $values->id : null;

        $deviceType = isset($values->deviceType) ? $values->deviceType : null;
        $installationId = isset($values->installationId) ? $values->installationId: null;
        $appVersion = isset($values->appVersion) ? $values->appVersion: null;

        $usr->setId($id);
        $usr->setUsername($username);
        $usr->setEmail($email);
        $usr->setPassword($password);
        $usr->setDeviceType($deviceType);
        $usr->setInstallationId($installationId);
        $usr->setAppVersion($appVersion);


        return $usr;
    }

    private $username;
    private $password;
    private $email;
    private $emailVerified = false;
    private $permissions;

    private $deviceType;
    private $installationId;
    private $appVersion;

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

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail() {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPermissions() {
        return $this->permissions;
    }

    /**
     * @param mixed $permissions
     */
    public function setPermissions($permissions) {
        $this->permissions = $permissions;
    }

    /**
     * @param mixed $appVersion
     */
    public function setAppVersion($appVersion) {
        $this->appVersion = $appVersion;
    }

    /**
     * @return mixed
     */
    public function getAppVersion() {
        return $this->appVersion;
    }

    /**
     * @param mixed $deviceType
     */
    public function setDeviceType($deviceType) {
        $this->deviceType = $deviceType;
    }

    /**
     * @return mixed
     */
    public function getDeviceType() {
        return $this->deviceType;
    }

    /**
     * @param mixed $installationId
     */
    public function setInstallationId($installationId) {
        $this->installationId = $installationId;
    }

    /**
     * @return mixed
     */
    public function getInstallationId() {
        return $this->installationId;
    }


}