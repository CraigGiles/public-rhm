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
    const THROTTLE_EXCEPTION = 'redhotmayo\registration\exceptions\ThrottleException';

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
        $r = new Registration($this->userRepo, $this->userMailer);
        $input = $this->getRegistrationInput();

        $this->userRepo->shouldReceive('save')->once()->andReturn(true);
        $this->validator->shouldReceive('validate')->once()->andReturn(true);
        $this->validator->shouldReceive('getCreationRules')->once();
        $this->throttle->shouldReceive('canUserRegister')->once()->withAnyArgs()->andReturn(true);
        $this->throttle->shouldReceive('decrementMax')->once()->withAnyArgs();


        $registered = $r->register($input, $this->validator, $this->throttle);
        $this->assertTrue($registered);
    }

    public function test_if_validation_fails() {
        $r = new Registration($this->userRepo, $this->userMailer);

        $input = $this->getRegistrationInput();
        $this->setExpectedException(self::VALIDATION_EXCEPTION);

        $this->validator->shouldReceive('getCreationRules')->once();
        $this->validator->shouldReceive('validate')->once()->andThrow(self::VALIDATION_EXCEPTION);

        $registered = $r->register($input, $this->validator, $this->throttle);
        $this->assertTrue($registered);
    }

    public function test_throttle_doesnt_let_new_users_register() {
        $r = new Registration($this->userRepo, $this->userMailer);

        $input = $this->getRegistrationInput();
        $this->setExpectedException(self::THROTTLE_EXCEPTION);
        $this->throttle->shouldReceive('canUserRegister')->once()->withAnyArgs()->andReturn(false);
        $this->validator->shouldReceive('getCreationRules')->once();
        $this->validator->shouldReceive('validate')->once()->withAnyArgs()->andReturn(true);

        $registered = $r->register($input, $this->validator, $this->throttle);
        $this->assertTrue($registered);
    }

    private function getRegistrationInput() {
        $json = Config::get('registration.testuser01');
        return json_decode($json, true);
    }

}
