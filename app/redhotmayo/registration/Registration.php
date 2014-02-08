<?php namespace redhotmayo\registration;


use redhotmayo\api\validator\InputValidator;
use redhotmayo\dataaccess\repository\UserRepository;
use redhotmayo\model\User;

class Registration {
    /**
     * @var \redhotmayo\dataaccess\repository\UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function register($input) {
        //validate input
        UserValidator::validate
        //save user
        //return message


    }

    public function apiRegistration($json) {
        $required = [
            'username',
            'password',
            'passwordConfirmation',
            'email',
            'deviceType',
            'installationId',
            'appVersion',
        ];

        $validator = new InputValidator();
        $valid = $validator->validate($json, $required);

        $this->userRepository->register($parameters);
    }

    /**
     * @param $parameters
     * @throws \InvalidArgumentException
     */
    private function validateInput($parameters) {

        //extract this
        foreach ($required as $req) {
            if (!array_key_exists($req, $parameters)) {
                throw new \InvalidArgumentException("{$req} was not found.");
            }
        }
    }
}