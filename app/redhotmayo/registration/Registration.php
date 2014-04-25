<?php namespace redhotmayo\registration;

use Illuminate\Support\Facades\App;
use redhotmayo\dataaccess\repository\ThrottleRegistrationRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\mailers\UserMailer;
use redhotmayo\model\User;
use redhotmayo\registration\exceptions\ThrottleException;

class Registration {
    /**
     * @var \redhotmayo\dataaccess\repository\UserRepository
     */
    private $userRepository;

    /**
     * @var \redhotmayo\mailers\UserMailer
     */
    private $mailer;

    public function __construct(UserRepository $userRepository, UserMailer $mailer) {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    /**
     * @param array $input
     * @param RegistrationValidator $validator
     * @param \redhotmayo\dataaccess\repository\ThrottleRegistrationRepository $throttle
     * @return bool
     *
     * @throws ThrottleException
     */
    public function register(array $input, RegistrationValidator $validator, ThrottleRegistrationRepository $throttle=null) {
        $registered = false;

        //validate input
        $validated = $validator->validate($input, $validator->getCreationRules());

        //TODO: WS-43 REMOVE when not needed anymore
        /** @var ThrottleRegistrationRepository $throttle */
        $throttle = $this->getThrottleRepository($throttle);

        if (!$throttle->canUserRegister($input)) {
            throw new ThrottleException;
        }
        //TODO: END WS-43 REMOVE

        //save user
        if ($validated) {
            $user = User::FromArray($input);
            $registered = $this->userRepository->save($user);
        }

        //TODO: once we have mailgun support, add mail
//        //send user an email
//        if ($registered && isset($user)) {
//            $this->mailer->welcome($user);
//        }

        if ($registered) {
           $throttle->decrementMax($input);
        }

        return $registered;
    }

    /**
     * @codeCoverageIgnore
     */
    private function getThrottleRepository($throttle) {
        $result = $throttle;

        if (!isset($throttle)) {
            $result = App::make('ThrottleRegistrationRepository');
        }

        return $result;
    }
}
