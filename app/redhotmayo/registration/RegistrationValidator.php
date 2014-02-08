<?php namespace redhotmayo\registration;

use redhotmayo\validation\ValidationException;
use redhotmayo\validation\Validator;

class RegistrationValidator extends Validator {
    public function getCreationRules() {
        return [
            'username' => "required|min:5",
            'password' => "required|confirmed|min:8",
            'email' => "required|email",
        ];
    }

    public function getUpdateRules() {
        throw new ValidationException("Update not implemented");
    }
}


