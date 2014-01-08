<?php
use  Mockery as m;

class LeadDistributionRepositorySQLTest extends TestCase {
    const ID = 1234;
    const ZIPCODE = 4321;
    const DATE = 0;

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
        $subRepo = new LeadDistributionRepositorySQL($this->dao, $this->db);
        $result = $subRepo->subscribeUserToZipcode($this->testUser, self::ZIPCODE);
        $this->assertTrue($result);
    }

    public function test_when_user_could_not_be_subscribed_to_zipcode() {
        $this->dao->shouldReceive('save')->withAnyArgs()->andReturn(null);
        $subRepo = new LeadDistributionRepositorySQL($this->dao, $this->db);
        $result = $subRepo->subscribeUserToZipcode($this->testUser, self::ZIPCODE);
        $this->assertFalse($result);

    }

    public function test_exceptions_should_cause_a_rollback() {
        $subscription = new Subscription();
        $subscription->add($this->testUser, self::ZIPCODE);

        $this->queryBuilder->shouldReceive('insertGetId')->withAnyArgs()->once()->andThrow(new Exception("Something"));
        $this->db->shouldReceive('rollBack')->once();

        $subRepo = new LeadDistributionRepositorySQL($this->dao, $this->db);
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

        $subRepo = new LeadDistributionRepositorySQL($this->dao, $this->db);
        $results = $subRepo->isUserSubscribedToAccount($this->testUser, $this->testAccount);
        $this->assertTrue($results);
    }

    public function test_user_is_not_subscribed_to_zipcode() {
        $return = array();

        $this->set_up_subscribed_to_zipcode_tests();
        $this->queryBuilder->shouldReceive('get')->withNoArgs()->andReturn($return);

        $subRepo = new LeadDistributionRepositorySQL($this->dao, $this->db);
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

        $subRepo = new LeadDistributionRepositorySQL($this->dao, $this->db);
        $results = $subRepo->getAllUserIdsSubscribedToZipcode(self::ZIPCODE);

        $returnCount = count($results);

        $this->assertEquals($expectedCount, $returnCount);
    }

    public function test_accounts_should_be_distributed_to_users() {
        //list of accounts should be given to the repository
        //users subscribed to the emails listed in those accounts should have new leads
        //eventually, new push notification should be sent out
//        $account = m::mock('Account');
//        $accounts[] = $account;
//
//        $dao = m::mock('AccountSQL');
//        $repo = new LeadDistributionRepositorySQL($dao, $this->db);
//        $results = $repo->distributeAccountsToUsers($accounts);


    }

    public function test_find_all_accounts_for_zipcode() {
        $account = m::mock('stdClass');
        $account->action = 1;
        $account->text = 1;
        $account->action = 1;
        $account->primaryNumber = 1;
        $account->streetPredirection = 1;
        $account->streetName = 1;
        $account->streetSuffix = 1;
        $account->suiteType = 1;
        $account->suiteNumber = 1;
        $account->cityName = 1;
        $account->countyName = 1;
        $account->stateAbbreviation = 1;
        $account->zipCode = 1;
        $account->plus4Code = 1;
        $account->longitude = 1;
        $account->latitude = 1;
        $account->cassVerified = 1;
        $account->googleGeocoded = 1;
        $account->userId = 1;
        $account->weeklyOpportunity = 1;
        $account->accountName = 1;
        $account->operatorType = 1;
        $account->contactName = 1;
        $account->phone = 1;
        $account->serviceType = 1;
        $account->cuisineType = 1;
        $account->seatCount = 1;
        $account->averageCheck = 1;
        $account->emailAddress = 1;
        $account->openDate = 1;
        $account->estimatedAnnualSales = 1;
        $account->owner = 1;
        $account->mobilePhone = 1;
        $account->website = 1;
        $account->isTargetAccount = 1;
        $account->isMaster = 1;

        $return[] = $account;

        $this->db->shouldReceive('table')->with('accounts')->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('join')->times(2)->withAnyArgs()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('select')->once()->withAnyArgs()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('where')->times(2)->withAnyArgs()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('get')->once()->andReturn($return);


        $dao = m::mock('AccountSQL');

        $repo = new LeadDistributionRepositorySQL($dao, $this->db);
        $results = $repo->findAllAccountsForZipcode(self::ZIPCODE, self::DATE);
        $this->assertEquals(count($results), 1);
        $acct = $results[0];

        $this->assertEquals($account->userId, $acct->getUserID());
        $this->assertEquals($account->weeklyOpportunity, $acct->getWeeklyOpportunity());
        $this->assertEquals($account->accountName, $acct->getAccountName());
        $this->assertEquals($account->operatorType, $acct->getOperatorType());
        $this->assertEquals($account->contactName, $acct->getContactName());
        $this->assertEquals($account->phone, $acct->getPhone());
        $this->assertEquals($account->serviceType, $acct->getServiceType());
        $this->assertEquals($account->cuisineType, $acct->getCuisineType());
        $this->assertEquals($account->seatCount, $acct->getSeatCount());
        $this->assertEquals($account->averageCheck, $acct->getAverageCheck());
        $this->assertEquals($account->emailAddress, $acct->getEmailAddress());
        $this->assertEquals($account->openDate, $acct->getOpenDate());
        $this->assertEquals($account->estimatedAnnualSales, $acct->getEstimatedAnnualSales());
        $this->assertEquals($account->owner, $acct->getOwner());
        $this->assertEquals($account->mobilePhone, $acct->getMobilePhone());
        $this->assertEquals($account->website, $acct->getWebsite());
        $this->assertEquals($account->isTargetAccount, $acct->getIsTargetAccount());
        $this->assertEquals($account->isMaster, $acct->getIsMaster());

        // Verify address came back accurate
        $addr = $acct->getAddress();
        $this->assertEquals($account->primaryNumber, $addr->getPrimaryNumber());
        $this->assertEquals($account->streetPredirection, $addr->getStreetPredirection());
        $this->assertEquals($account->streetName, $addr->getStreetName());
        $this->assertEquals($account->streetSuffix, $addr->getStreetSuffix());
        $this->assertEquals($account->suiteType, $addr->getSuiteType());
        $this->assertEquals($account->suiteNumber, $addr->getSuiteNumber());
        $this->assertEquals($account->cityName, $addr->getCityName());
        $this->assertEquals($account->countyName, $addr->getCountyName());
        $this->assertEquals($account->stateAbbreviation, $addr->getStateAbbreviation());
        $this->assertEquals($account->zipCode, $addr->getZipcode());
        $this->assertEquals($account->plus4Code, $addr->getPlus4Code());
        $this->assertEquals($account->longitude, $addr->getLongitude());
        $this->assertEquals($account->latitude, $addr->getLatitude());
        $this->assertEquals($account->cassVerified, $addr->getCassVerified());
        $this->assertEquals($account->googleGeocoded, $addr->getGoogleGeocoded());

        // Verify notes came back accurate
        $notes = $acct->getNotes();
        $this->assertEquals(1, count($notes));
        $note = $notes[0];
        $this->assertEquals($account->action, $note->getAction());
        $this->assertEquals($account->text, $note->getText());
        $this->assertEquals($account->action, $note->getAuthor());
    }
}

