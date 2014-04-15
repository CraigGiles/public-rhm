<?php namespace redhotmayo\distribution;

use Illuminate\Support\Facades\Config;
use Mockery as m;
use Mockery\MockInterface;
use RedHotMayoTestCase;

class AccountSubscriptionManagerTest extends RedHotMayoTestCase {
    const TEST_USER = 'testusers.testuser01';

    const ROUTE = 'subscribe';
    const CITY_INPUT = 'subscription.city_test';
    const COUNTY_INPUT = 'subscription.county_test';

    /** @var MockInterface $subRepo */
    private $subRepo;

    /** @var MockInterface $userRepo */
    private $zipcodeRepo;

    public function setUp() {
        parent::setUp();

        $this->subRepo = $this->mock('redhotmayo\dataaccess\repository\SubscriptionRepository');
        $this->zipcodeRepo = $this->mock('redhotmayo\dataaccess\repository\ZipcodeRepository');
    }

    public function tearDown() {
        parent::tearDown();
    }


    public function test_it_should_let_a_user_subscribe_to_a_city() {
        $data = Config::get(self::CITY_INPUT);
        $data = json_decode($data, true);

        $this->zipcodeRepo->shouldReceive('getZipcodesFromCity')
                          ->with('SIMI VALLEY', 'CA')
                          ->andReturn([93063, 93064, 93065]);
        $this->zipcodeRepo->shouldReceive('getZipcodesFromCity')
                          ->with('SACRAMENTO', 'CA')
                          ->andReturn([95132]);

        $this->subRepo->shouldReceive('save')->times(4);

        $manager = new AccountSubscriptionManager($this->subRepo, $this->zipcodeRepo);
        $manager->process($this->getRedHotMayoUser(), $data);
    }

    public function test_it_should_let_a_user_subscribe_to_county() {
        $data = Config::get(self::COUNTY_INPUT);
        $data = json_decode($data, true);

        $this->zipcodeRepo->shouldReceive('getZipcodesFromCounty')
                          ->with('VENTURA', 'CA')
                          ->andReturn([93063, 93064, 93065]);
        $this->zipcodeRepo->shouldReceive('getZipcodesFromCounty')
                          ->with('SACRAMENTO', 'CA')
                          ->andReturn([95132]);

        $this->subRepo->shouldReceive('save')->times(4);

        $manager = new AccountSubscriptionManager($this->subRepo, $this->zipcodeRepo);
        $manager->process($this->getRedHotMayoUser(), $data);
    }
}
