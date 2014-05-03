<?php namespace redhotmayo\model;

use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Auth\UserInterface;
use stdClass;

class User extends DataObject implements UserInterface, RemindableInterface {
    const REMEMBER_TOKEN = 'remember_token';

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

        $mobile = isset($values->mobileDevice) ? $values->mobileDevice : null;
        $mobileDevice = MobileDevice::FromArray(json_decode(json_encode($mobile), true));

        return new User($id, $username, $password, $email, $permissions, $mobileDevice);
    }

    private $username;
    private $password;
    private $email;
    private $emailVerified = false;
    private $permissions;
    private $rememberToken;
    
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

    public static function FromGenericUser($genericUser) {
        $id = isset($genericUser->id) ? $genericUser->id : null;
        $username = isset($genericUser->username) ? $genericUser->username : null;
        $email = isset($genericUser->email) ? $genericUser->email : null;

        return new self($id, $username, null, $email, null, null);
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

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier() {
        return $this->getUsername();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword() {
        return $this->getPassword();
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken() {
        return $this->rememberToken;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value) {
        $this->rememberToken = $value;
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName() {
        return self::REMEMBER_TOKEN;
    }
}
