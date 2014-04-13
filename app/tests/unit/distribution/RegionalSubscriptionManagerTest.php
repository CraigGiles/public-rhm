<?php namespace redhotmayo\distribution;

use Illuminate\Support\Facades\Config;
use Mockery\MockInterface;
use RedHotMayoTestCase;
use Mockery as m;

class RegionalSubscriptionManagerTest extends RedHotMayoTestCase {
    const TEST_USER = 'testusers.testuser01';

    const ROUTE = 'subscribe';
    const VALID_INPUT = 'subscription.city_test';

    /** @var MockInterface $subRepo */
    private $subRepo;

    /** @var MockInterface $userRepo */
    private $zipcodeRepo;

    /** @var RegionalSubscriptionManager $manager */
    private $manager;

    public function setUp() {
        parent::setUp();

        $this->subRepo = $this->mock('redhotmayo\dataaccess\repository\SubscriptionRepository');
        $this->zipcodeRepo = $this->mock('redhotmayo\dataaccess\repository\ZipcodeRepository');
    }

    public function tearDown() {
        parent::tearDown();
    }


    public function test_it_should_let_a_user_subscribe_to_a_city() {
        $data = Config::get(self::VALID_INPUT);
        $data = json_decode($data, true);
        $data = $data['regions'];

        $this->zipcodeRepo->shouldIgnoreMissing([]);

        $manager = new RegionalSubscriptionManager($this->subRepo, $this->zipcodeRepo);
        $manager->subscribeRegionsToUser($this->getRedHotMayoUser(), $data);
    }
}
