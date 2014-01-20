<?php namespace redhotmayo\auth\passwords;

use Closure;
use Illuminate\Auth\Reminders\RemindableInterface;
use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\model\User;

class PasswordBroker extends \Illuminate\Auth\Reminders\PasswordBroker
{
//    /**
//     * @param array $credentials
//     * @param Closure $callback
//     * @return string
//     */
//    public function remind(array $credentials, Closure $callback = null)
//    {
//        // First we will check to see if we found a user at the given credentials and
//        // if we did not we will redirect back to this current URI with a piece of
//        // "flash" data in the session to indicate to the developers the errors.
//        $user = $this->getUser($credentials);
//
//
//        if (is_null($user))
//        {
//            return self::INVALID_USER;
//        }
//
//        // Once we have the reminder token, we are ready to send a message out to the
//        // user with a link to reset their password. We will then redirect back to
//        // the current URI having nothing set in the session to indicate errors.
//        $token = $this->reminders->create($user);
//
//        $this->sendReminder($user, $token, $callback);
//
//        return self::REMINDER_SENT;
//    }
//
//    /**
//     * Reset the password for the given token.
//     *
//     * @param  array    $credentials
//     * @param  Closure  $callback
//     * @return mixed
//     */
    public function reset(array $credentials, Closure $callback)
    {

        // If the responses from the validate method is not a user instance, we will
        // assume that it is a redirect and simply return it from this method and
        // the user is properly redirected having an error message on the post.
        $user = $this->validateReset($credentials);

        if ( ! $user instanceof RemindableInterface)
        {
            return $user;
        }

        $pass = $credentials['password'];
        // Once we have called this callback, we will remove this token row from the
        // table and return the response from this callback so the user gets sent
        // to the destination given by the developers from the callback return.
        call_user_func($callback, $user, $pass);

        $this->reminders->delete($credentials['token']);

        return self::PASSWORD_RESET;
    }

    public function getUser(array $credentials) {
        $userDAO = DataAccessObject::GetUserDAO();
        $user = $userDAO->getUser($credentials);
        $user = User::FromStdClass($user);
        return $user;
    }
}