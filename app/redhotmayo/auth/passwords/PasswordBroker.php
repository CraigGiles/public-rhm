<?php namespace redhotmayo\auth\passwords;

use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\dataaccess\repository\sql\UserRepositorySQL;
use redhotmayo\model\User;

class PasswordBroker extends \Illuminate\Auth\Reminders\PasswordBroker {

    /**
     * Returns a copy of the user object responsible for the credentials found
     *
     * @param array $credentials
     * @return \Illuminate\Auth\Reminders\RemindableInterface|mixed|User
     */
    public function getUser(array $credentials) {
        $email = isset($credentials['email']) ? $credentials['email'] : null;
        $user = (new UserRepositorySQL())->find(['email' => $email]);
        return $user;
    }
}