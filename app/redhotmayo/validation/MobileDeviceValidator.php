<?php namespace redhotmayo\validation;

class MobileDeviceValidator extends Validator {
    public function getCreationRules() {
        $rules['deviceType'] = "required";
        $rules['installationId'] = "required";
        $rules['appVersion'] = "required";

        return $rules;
    }

    public function getUpdateRules() {
        throw new ValidationException("Update not implemented");
    }
}