<?php namespace redhotmayo\registration;

use TestCase;

class RegistrationValidatorTest extends TestCase {
    const USERNAME_CREATE_RULE = 'required|min:5|unique:users,username';
    const EMAIL_CREATE_RULE = 'required|email|unique:users,email';
    const PASSWORD_CREATE_RULE = 'required|confirmed|min:8';
    const VALIDATION_EXCEPTION = 'redhotmayo\validation\ValidationException';

    private $rules;

    public function setUp() {
        parent::setUp();

        $v = new RegistrationValidator();
        $this->rules = $v->getCreationRules();
    }

    public function test_validator_validates_all_fields() {
        $this->assertTrue(array_key_exists(RegistrationValidator::USERNAME, $this->rules));
        $this->assertTrue(array_key_exists(RegistrationValidator::PASSWORD, $this->rules));
        $this->assertTrue(array_key_exists(RegistrationValidator::EMAIL, $this->rules));
    }

    public function test_validator_only_has_three_creation_rules() {
        $this->assertEquals(count($this->rules), 3);
    }

    public function test_creation_user_rule() {
        $this->assertEquals(self::USERNAME_CREATE_RULE, $this->rules[RegistrationValidator::USERNAME]);
    }

    public function test_creation_email_rule() {
        $this->assertEquals(self::EMAIL_CREATE_RULE, $this->rules[RegistrationValidator::EMAIL]);
    }
    public function test_creation_password_rule() {
        $this->assertEquals(self::PASSWORD_CREATE_RULE, $this->rules[RegistrationValidator::PASSWORD]);
    }

    public function test_update_rules_throw_exception() {
        $this->setExpectedException(self::VALIDATION_EXCEPTION);
        $r = new RegistrationValidator();
        $r->getUpdateRules();
    }
}
