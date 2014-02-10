<?php namespace redhotmayo\registration;

use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\model\User;
use redhotmayo\validation\Validator;

class Registration {
    /**
     * @var \redhotmayo\dataaccess\repository\UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * @param array $input
     * @param RegistrationValidator $validator
     * @return bool
     */
    public function mobileRegistration(array $input, RegistrationValidator $validator) {
        $registered = false;

        //validate input
        $validated = $validator->validate($input, $validator->getCreationRules());

        //save user
        if ($validated) {
            $user = User::FromArray($input);
            $registered = $this->userRepository->save($user);
        }

        if ($registered) {
            //send welcome email
        }

        //user was not registered
        return $registered;
    }
}