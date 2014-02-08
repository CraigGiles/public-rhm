<?php namespace redhotmayo\validation;

use Illuminate\Support\Facades\Validator as V;

abstract class Validator {
    public abstract function getCreationRules();
    public abstract function getUpdateRules();

    /**
     * Perform validation
     *
     * @param $input
     * @param $rules
     *
     * @return bool
     * @throws ValidationException
     */
    public function validate($input, $rules) {
        $validation = V::make($input, $rules);

        if ($validation->fails()) {
            throw new ValidationException($validation->messages());
        }

        return true;
    }

    /**
     * Validate against default ruleset
     *
     * @param $input
     *
     * @return bool
     */
    public function validateForCreation($input) {
        return $this->validate($input, $this->getCreationRules());
    }

    /**
     * Validate against update ruleset
     *
     * @param $input
     *
     * @return bool
     */
    public function validateForUpdate($input) {
        return $this->validate($input, $this->getUpdateRules());
    }
}

