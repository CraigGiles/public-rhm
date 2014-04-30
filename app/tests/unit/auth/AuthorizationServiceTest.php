<?php namespace redhotmayo\auth;


use Illuminate\Support\Facades\Auth;
use Mockery\MockInterface;
use RedHotMayoTestCase;
use Mockery as m;

class AuthorizationServiceTest extends RedHotMayoTestCase {
    const USER_REPOSITORY = 'redhotmayo\dataaccess\repository\UserRepository';
    const USER = 'redhotmayo\model\User';

    /** @var MockInterface $mockRepo */
    private $mockRepo;

    /** @var MockInterface $mockUser */
    private $mockUser;

    public function setUp() {
        parent::setUp();

        $this->mockRepo = $this->mock(self::USER_REPOSITORY);
        $this->mockUser = $this->mock(self::USER);
    }

    public function tearDown() {
        parent::tearDown();
        m::close();
    }

    /**
     * @test
     * @author Craig Giles < craig@gilesc.com >
     */
    public function it_should_use_service_location() {
        $return = $this->app->make('Authorization');
        $this->assertNotNull($return);
    }

    /**
     * @test
     * @author Craig Giles < craig@gilesc.com >
     */
    public function it_should_return_a_valid_user_when_user_logs_in() {
        $login = $this->getLoginService();

        $parameters = [
            'username' => 'testuser',
            'password' => 'password'
        ];

        Auth::shouldReceive('attempt')->once()->with($parameters)->andReturn(true);
        $this->mockRepo->shouldReceive('find')->with(['username' => 'testuser'])->andReturn($this->mockUser);

        $user = $login->login($parameters);
        $this->assertEquals($this->mockUser, $user);
    }

    /**
     * @test
     * @author Craig Giles < craig@gilesc.com >
     */
    public function it_should_return_false_when_given_invalid_login_credentials() {
        $login = $this->getLoginService();

        $parameters = [
            'username' => 'testuser',
            'password' => 'invalid_password'
        ];

        Auth::shouldReceive('attempt')->once()->with($parameters)->andReturn(false);
        $this->mockRepo->shouldReceive('find')->never();

        $result = $login->login($parameters);
        $this->assertFalse($result);
    }

    private function getLoginService() {
        return new AuthorizationService($this->mockRepo);
    }
}
