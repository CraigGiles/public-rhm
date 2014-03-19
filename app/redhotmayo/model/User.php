<?php namespace redhotmayo\model;

use Illuminate\Auth\Reminders\RemindableInterface;
use stdClass;

class User extends DataObject implements RemindableInterface {
    public static function create($input) {
        if ($input instanceof stdClass) {
            return User::FromStdClass($input);
        } else if (is_array($input)) {
            return User::FromArray($input);
        }
    }

    public static function FromArray($input) {
        $mobileDevice = MobileDevice::FromArray($input);

        $id = isset($input['id']) ? $input['id'] : null;
        $username = isset($input['username']) ? $input['username'] : null;
        $email = isset($input['email']) ? $input['email'] : null;
        $password = isset($input['password']) ? $input['password'] : null;
        $permissions = isset($input['permissions']) ? $input['permissions'] : null;

        return new User($id, $username, $password, $email, $permissions, $mobileDevice);
    }

    public static function FromStdClass($values) {
        $email = isset($values->email) ? $values->email : null;
        $username = isset($values->username) ? $values->username : null;
        $password = isset($values->password) ? $values->password : null;
        $id = isset($values->id) ? $values->id : null;
        $permissions = isset($values->permissions) ? $values->permissions : null;
        $mobileDevice = isset($values->mobileDevice) ? $values->mobileDevice : null;

        return new User($id, $username, $password, $email, $permissions, $mobileDevice);
    }

    private $username;
    private $password;
    private $email;
    private $emailVerified = false;
    private $permissions;

    /** @var  MobileDevice $mobileDevice */
    private $mobileDevice;

    function __construct($id, $username, $password, $email, $permissions, $mobileDevice) {
        $this->setUserId($id);
        $this->setUsername($username);
        $this->setPassword($password);
        $this->setEmail($email);
        $this->setPermissions($permissions);
        $this->setMobileDevice($mobileDevice);
    }



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

    public function setMobileDevice($mobileDevice) {
        if ($mobileDevice instanceof MobileDevice) {
            $this->mobileDevice = $mobileDevice;
        } else if ($mobileDevice instanceof stdClass) {
            $this->mobileDevice = MobileDevice::FromArray(json_decode(json_encode($mobileDevice), true));
        }
    }

    /**
     * @return \redhotmayo\model\MobileDevice
     */
    public function getMobileDevice() {
        return $this->mobileDevice;
    }

    public function getMobileDeviceInstallationId() {
        if (isset($this->mobileDevice)) {
            return $this->mobileDevice->getInstallationId();
        }

        return null;
    }


}