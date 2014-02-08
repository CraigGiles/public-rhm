<?php namespace redhotmayo\registration;

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

    public function register($input, $validator) {
        //validate input
        if (isset($validator) && $validator instanceof Validator) {
            $validated = $validator->validate($input, $validator->getCreationRules());
        } else {
            throw new InvalidArgumentException("Registration Validator must be instanceof Validator");
        }

        //save user
        if ($validated) {
            $user = json_decode(json_encode($input));
            return $this->userRepository->save(User::FromStdClass($user));
        }

        //user was not registered
        return false;
    }
}