<?php


use Mockery\MockInterface;
use Mockery as m;

class SubscriptionControllerTest extends RedHotMayoControllerTestCase {
    const TEST_USER = 'testusers.testuser01';

    const ROUTE = 'subscribe';
    const VALID_INPUT = 'subscription.subscription01';

    /** @var MockInterface $subRepo */
    private $subRepo;

    /** @var MockInterface $userRepo */
    private $userRepo;

    /** @var MockInterface $subManager */
    private $subManager;

    public function setUp() {
        parent::setUp();

        $this->subRepo = $this->mock('redhotmayo\dataaccess\repository\SubscriptionRepository');
        $this->userRepo = $this->mock('redhotmayo\dataaccess\repository\UserRepository');
        $this->subManager = $this->mock('redhotmayo\distribution\AccountSubscriptionManager');
    }

    public function tearDown() {
        parent::tearDown();

        m::close();
    }

    public function test_store_with_valid_input_and_valid_user() {
        $this->userRepo->shouldReceive('find')->andReturn($this->getRedHotMayoUser());
        $this->subManager->shouldIgnoreMissing();

        $this->callPostWithArray(self::ROUTE, self::VALID_INPUT);
    }
}
