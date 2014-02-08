<?php namespace redhotmayo\registration;


use redhotmayo\validation\ValidationException;

class MobileRegistrationValidator extends RegistrationValidator {
    public function getCreationRules() {
        $rules = parent::getCreationRules();

        $rules['deviceType'] = "required";
        $rules['installationId'] = "required";
        $rules['appVersion'] = "required";

        return $rules;
    }

    public function getUpdateRules() {
        throw new ValidationException("Update not implemented");
    }
} 