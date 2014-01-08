<?php
use  Mockery as m;

class SubscriptionSQLTest extends TestCase {
    const ID = 1234;
    const ZIPCODE = 4321;

    /** @var  \Mockery\MockInterface */
    private $queryBuilder;

    /** @var  \Mockery\MockInterface */
    private $db;

    /** @var  \Mockery\MockInterface */
    private $testUser;

    /** @var  \Mockery\MockInterface */
    private $mockAddress;

    /** @var  \Mockery\MockInterface */
    private $testAccount;

    public function setUp() {
        $this->testUser = m::mock('User');
        $this->testAccount = m::mock('Account');
        $this->mockAddress = m::mock('Address');
        $this->dao = m::mock('SubscriptionSQL');

        $this->testUser->shouldIgnoreMissing();
        $this->testAccount->shouldIgnoreMissing();
        $this->mockAddress->shouldIgnoreMissing();
        $this->dao->shouldIgnoreMissing();

        $this->queryBuilder = m::mock('Illuminate\Database\Query\QueryBuilder');
        $this->db = m::mock('Illuminate\Database\Connection');
        $this->db->shouldReceive('beginTransaction')->once();
        $this->db->shouldReceive('table')->withAnyArgs()->once()->andReturn($this->queryBuilder);
    }

    private function set_up_subscribed_to_zipcode_tests() {
        $this->queryBuilder->shouldReceive('join')->withAnyArgs()->once()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('select')->withAnyArgs()->once()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('where')->withAnyArgs()->times(6)->andReturn($this->queryBuilder);
        $this->testAccount->shouldReceive('getAddress')->once()->andReturn($this->mockAddress);
    }

    public function test_users_should_be_able_to_subscribe_to_zipcode() {
        $this->dao->shouldReceive('save')->withAnyArgs()->andReturn(self::ID);
        $subRepo = new SubscriptionRepositorySQL($this->dao, $this->db);
        $result = $subRepo->subscribeUserToZipcode($this->testUser, self::ZIPCODE);
        $this->assertTrue($result);
    }

    public function test_when_user_could_not_be_subscribed_to_zipcode() {
        $this->dao->shouldReceive('save')->withAnyArgs()->andReturn(null);
        $subRepo = new SubscriptionRepositorySQL($this->dao, $this->db);
        $result = $subRepo->subscribeUserToZipcode($this->testUser, self::ZIPCODE);
        $this->assertFalse($result);

    }

    public function test_exceptions_should_cause_a_rollback() {
        $subscription = new Subscription();
        $subscription->add($this->testUser, self::ZIPCODE);

        $this->queryBuilder->shouldReceive('insertGetId')->withAnyArgs()->once()->andThrow(new Exception("Something"));
        $this->db->shouldReceive('rollBack')->once();

        $subRepo = new SubscriptionRepositorySQL($this->dao, $this->db);
        $id = $subRepo->save($subscription);

        $this->assertEquals(null, $id);
    }

    public function test_user_is_subscribed_to_a_zipcode() {
        $return = array(
            $this->testAccount,
            $this->testAccount,
        );

        $this->set_up_subscribed_to_zipcode_tests();
        $this->queryBuilder->shouldReceive('get')->withNoArgs()->andReturn($return);

        $subRepo = new SubscriptionRepositorySQL($this->dao, $this->db);
        $results = $subRepo->isUserSubscribedToAccount($this->testUser, $this->testAccount);
        $this->assertTrue($results);
    }

    public function test_user_is_not_subscribed_to_zipcode() {
        $return = array();

        $this->set_up_subscribed_to_zipcode_tests();
        $this->queryBuilder->shouldReceive('get')->withNoArgs()->andReturn($return);

        $subRepo = new SubscriptionRepositorySQL($this->dao, $this->db);
        $results = $subRepo->isUserSubscribedToAccount($this->testUser, $this->testAccount);
        $this->assertFalse($results);
    }

    public function test_get_all_user_ids_subscribed_to_a_zipcode() {
        $return = array(
            $this->testAccount,
            $this->testAccount,
        );
        $expectedCount = count($return);

        $this->queryBuilder->shouldReceive('select')->withAnyArgs()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('where')->withAnyArgs()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('get')->withNoArgs()->andReturn($return);

        $subRepo = new SubscriptionRepositorySQL($this->dao, $this->db);
        $results = $subRepo->getAllUserIdsSubscribedToZipcode(self::ZIPCODE);

        $returnCount = count($results);

        $this->assertEquals($expectedCount, $returnCount);
    }
}

