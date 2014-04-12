<?php namespace redhotmayo\registration;

use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use InvalidArgumentException;
use redhotmayo\dataaccess\repository\sql\ThrottleRegistrationRepositorySQL;
use redhotmayo\dataaccess\repository\ThrottleRegistrationRepository;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\mailers\UserMailer;
use redhotmayo\model\User;
use redhotmayo\validation\Validator;

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
     * @throws \Exception
     * @return bool
     */
    public function register(array $input, RegistrationValidator $validator, ThrottleRegistrationRepository $throttle=null) {
        //TODO: WS-43 REMOVE when not needed anymore
        /** @var ThrottleRegistrationRepository $throttle */
        if (!isset($throttle)) {
            $throttle = App::make('ThrottleRegistrationRepository');
        }

        if (!$throttle->canUserRegister($input)) {
            throw new Exception("Registration limited to invited guests. Please try back at a later date.");
        }
        //TODO: END WS-43 REMOVE

        $registered = false;

        //validate input
        $validated = $validator->validate($input, $validator->getCreationRules());

        //save user
        if ($validated) {
            $user = User::FromArray($input);
            $registered = $this->userRepository->save($user);
        }

//        //send user an email
//        if ($registered && isset($user)) {
//            $this->mailer->welcome($user);
//        }

        if ($registered) {
           $throttle->decrementMax($input);
        }

        return $registered;
    }
}
