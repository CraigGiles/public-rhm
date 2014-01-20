<?php namespace redhotmayo\auth\passwords;

use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\model\User;

class PasswordBroker extends \Illuminate\Auth\Reminders\PasswordBroker {

    /**
     * Returns a copy of the user object responsible for the credentials found
     *
     * @param array $credentials
     * @return \Illuminate\Auth\Reminders\RemindableInterface|mixed|User
     */
    public function getUser(array $credentials) {
        $userDAO = DataAccessObject::GetUserDAO();
        $user = $userDAO->getUser($credentials);
        $user = User::FromStdClass($user);
        return $user;
    }
}