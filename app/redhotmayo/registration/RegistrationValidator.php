<?php namespace redhotmayo\registration;

use redhotmayo\validation\ValidationException;
use redhotmayo\validation\Validator;

class RegistrationValidator extends Validator {
    const USERNAME = 'username';
    const PASSWORD = 'password';
    const EMAIL = 'email';

    public function getCreationRules() {
        return [
            'username' => "required|min:5|unique:users,username",
            'password' => "required|confirmed|min:8",
            'email' => "required|email|unique:users,email",
        ];
    }

    public function getUpdateRules() {
        throw new ValidationException("Update not implemented");
    }
}


