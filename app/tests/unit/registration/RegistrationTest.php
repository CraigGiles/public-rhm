<?php namespace redhotmayo\registration;

use Illuminate\Support\Facades\Config;
use Mockery as m;
use redhotmayo\dataaccess\repository\sql\UserRepositorySQL;
use TestCase;

class RegistrationTest extends TestCase {
    const USER_REPOSITORY = 'redhotmayo\dataaccess\repository\UserRepository';
    const REGISTRATION_VALIDATOR = 'redhotmayo\registration\RegistrationValidator';
    const VALIDATION_EXCEPTION = 'redhotmayo\validation\ValidationException';

    public function tearDown() {
        m::close();
    }

    public function test_it_should_verify_valid_api_json() {
        $userRepo = m::mock(self::USER_REPOSITORY);
        $userRepo->shouldReceive('save')->once()->andReturn(true);

        $validator = m::mock(self::REGISTRATION_VALIDATOR);
        $validator->shouldReceive('validate')->once()->andReturn(true);
        $validator->shouldReceive('getCreationRules')->once();

        $json = Config::get('testdata.register_user');
        $input = json_decode($json, true);

        $r = new Registration($userRepo);
        $registered = $r->register($input, $validator);
        $this->assertTrue($registered);
    }

    public function test_if_validation_fails() {
        $this->setExpectedException(self::VALIDATION_EXCEPTION);

        $userRepo = m::mock(self::USER_REPOSITORY);

        $validator = m::mock(self::REGISTRATION_VALIDATOR);
        $validator->shouldReceive('getCreationRules')->once();
        $validator->shouldReceive('validate')->once()->andThrow(self::VALIDATION_EXCEPTION);

        $json = Config::get('testdata.register_user');
        $input = json_decode($json, true);

        $r = new Registration($userRepo);
        $registered = $r->register($input, $validator);
        $this->assertTrue($registered);
    }

}
 