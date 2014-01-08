<?php
use  Mockery as m;

class SubscriptionSQLTest extends TestCase {
    const ID = 1234;
    protected $useDatabase = true;

    private $queryBuilder;
    private $db;

    public function setUp() {
        $this->queryBuilder = m::mock('Illuminate\Database\Query\QueryBuilder');
        $this->db = m::mock('Illuminate\Database\Connection');
        $this->db->shouldReceive('beginTransaction')->once();
    }

    public function test_users_should_be_able_to_subscribe_to_zipcode() {
        $testUser = 'testuser01';
        $testZip = 93063;

        $subscription = new Subscription();
        $subscription->add($testUser, $testZip);

        $this->db->shouldReceive('table')->withAnyArgs()->once()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('insertGetId')->withAnyArgs()->once()->andReturn(self::ID);
        $this->db->shouldReceive('commit')->once();

        $dao = new SubscriptionSQL($this->db);
        $subRepo = new SubscriptionRepositorySQL($dao);
        $id = $subRepo->save($subscription);

        $this->assertEquals(self::ID, $id);
    }

    public function test_exceptions_should_cause_a_rollback() {
        $testUser = 'testuser01';
        $testZip = 93063;

        $subscription = new Subscription();
        $subscription->add($testUser, $testZip);

        $this->db->shouldReceive('table')->withAnyArgs()->once()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('insertGetId')->withAnyArgs()->once()->andThrow(new Exception("Something"));
        $this->db->shouldReceive('rollBack')->once();

        $dao = new SubscriptionSQL($this->db);
        $subRepo = new SubscriptionRepositorySQL($dao);
        $id = $subRepo->save($subscription);

        $this->assertEquals(null, $id);
    }
}

