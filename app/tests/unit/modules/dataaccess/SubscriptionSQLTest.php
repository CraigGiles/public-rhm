<?php
use  Mockery as m;

class SubscriptionSQLTest extends TestCase {
    const ID = 1234;
    protected $useDatabase = true;

    private $queryBuilder;
    private $db;
    private $testZip;
    private $testUser;

    public function setUp() {
        $this->testUser = new User();
        $this->testZip = 93063;
        $this->queryBuilder = m::mock('Illuminate\Database\Query\QueryBuilder');
        $this->db = m::mock('Illuminate\Database\Connection');
        $this->db->shouldReceive('beginTransaction')->once();
        $this->db->shouldReceive('table')->withAnyArgs()->once()->andReturn($this->queryBuilder);
    }

    public function test_users_should_be_able_to_subscribe_to_zipcode() {

        $subscription = new Subscription();
        $subscription->add($this->testUser, $this->testZip);

        $this->queryBuilder->shouldReceive('insertGetId')->withAnyArgs()->once()->andReturn(self::ID);
        $this->db->shouldReceive('commit')->once();

        $dao = new SubscriptionSQL($this->db);
        $subRepo = new SubscriptionRepositorySQL($dao);
        $id = $subRepo->save($subscription);

        $this->assertEquals(self::ID, $id);
    }

    public function test_exceptions_should_cause_a_rollback() {
        $subscription = new Subscription();
        $subscription->add($this->testUser, $this->testZip);

        $this->queryBuilder->shouldReceive('insertGetId')->withAnyArgs()->once()->andThrow(new Exception("Something"));
        $this->db->shouldReceive('rollBack')->once();

        $dao = new SubscriptionSQL($this->db);
        $subRepo = new SubscriptionRepositorySQL($dao);
        $id = $subRepo->save($subscription);

        $this->assertEquals(null, $id);
    }

    public function test_is_user_subscribed_to_a_zipcode() {
//        $dao = new SubscriptionSQL($this->db);
//        $subRepo = new SubscriptionRepositorySQL($dao);
//        $results = $subRepo->isUserSubscribedToAccount($this->testUser, $this->testAccount);
    }

}

