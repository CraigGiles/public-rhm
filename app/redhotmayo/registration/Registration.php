<?php namespace redhotmayo\registration;

use Illuminate\Support\Facades\App;
use redhotmayo\dataaccess\repository\ThrottleRegistrationRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\mailers\UserMailer;
use redhotmayo\model\User;
use redhotmayo\registration\exceptions\ThrottleException;
use redhotmayo\utility\Arrays;

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

        /** @var ThrottleRegistrationRepository $throttle */
        $throttle = $this->getThrottleRepository($throttle);
        $validated = $validator->validate($input, $validator->getCreationRules());

        //save user
        if ($validated) {
            $user = User::FromArray($input);
            $registered = $this->userRepository->save($user);
        }

        //send user an email
        if (App::environment() != 'local' &&
            App::environment() != 'testing' &&
            $registered && isset($user)) {
            $this->mailer->welcome($user);
        }

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
