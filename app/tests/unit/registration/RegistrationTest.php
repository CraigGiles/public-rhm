<?php namespace redhotmayo\registration;

use Illuminate\Support\Facades\Config;
use Mockery as m;
use Mockery\MockInterface;
use TestCase;

class RegistrationTest extends TestCase {
    const USER_REPOSITORY = 'redhotmayo\dataaccess\repository\UserRepository';
    const REGISTRATION_VALIDATOR = 'redhotmayo\registration\RegistrationValidator';
    const VALIDATION_EXCEPTION = 'redhotmayo\validation\ValidationException';
    const USER_MAILER = 'redhotmayo\mailers\UserMailer';
    const THROTTLE_REPOSITORY = 'redhotmayo\dataaccess\repository\ThrottleRegistrationRepository';

    /** @var MockInterface $userMailer */
    private $userRepo;

    /** @var MockInterface $userMailer */
    private $userMailer;

    /** @var MockInterface $validator */
    private $validator;

    /** @var MockInterface $throttle */
    private $throttle;

    public function setUp() {
        parent::setUp();

        $this->userRepo = m::mock(self::USER_REPOSITORY);
        $this->userMailer = m::mock(self::USER_MAILER);
        $this->validator = m::mock(self::REGISTRATION_VALIDATOR);
        $this->throttle = m::mock(self::THROTTLE_REPOSITORY);
    }

    public function tearDown() {
        parent::tearDown();

        m::close();
    }

    public function test_it_should_let_a_user_register() {
        $this->userRepo->shouldReceive('save')->once()->andReturn(true);
        $this->validator->shouldReceive('validate')->once()->andReturn(true);
        $this->validator->shouldReceive('getCreationRules')->once();
        $this->throttle->shouldReceive('canUserRegister')->once()->withAnyArgs()->andReturn(true);
        $this->throttle->shouldReceive('decrementMax')->once()->withAnyArgs();

        $r = new Registration($this->userRepo, $this->userMailer);

        $json = Config::get('registration.testuser01');
        $input = json_decode($json, true);

        $registered = $r->register($input, $this->validator, $this->throttle);
        $this->assertTrue($registered);
    }

    public function test_if_validation_fails() {
//        $this->setExpectedException(self::VALIDATION_EXCEPTION);
//
//        $userRepo = m::mock(self::USER_REPOSITORY);
//
//        $validator = m::mock(self::REGISTRATION_VALIDATOR);
//        $validator->shouldReceive('getCreationRules')->once();
//        $validator->shouldReceive('validate')->once()->andThrow(self::VALIDATION_EXCEPTION);
//
//        $json = Config::get('testdata.register_user');
//        $input = json_decode($json, true);
//
//        $r = new Registration($userRepo);
//        $registered = $r->register($input, $validator);
//        $this->assertTrue($registered);
    }

}
