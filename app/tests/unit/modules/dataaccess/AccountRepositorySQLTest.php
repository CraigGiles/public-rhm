<?php

use Mockery as m;

class AccountRepositorySQLTest extends TestCase {
    /** @var  \Mockery\MockInterface */
    protected $queryBuilder;

    /** @var  \Mockery\MockInterface */
    protected $db;

    const ID = 1234;
    const ZIPCODE = 4321;
    const DATE = 0;

    /** @var  \Mockery\MockInterface */
    private $testUser;

    /** @var  \Mockery\MockInterface */
    private $mockAddress;

    /** @var  \Mockery\MockInterface */
    private $testAccount;

    public function setUp() {
        $this->queryBuilder = m::mock('Illuminate\Database\Query\QueryBuilder');
        $this->db = m::mock('Illuminate\Database\Connection');

        $this->testUser = m::mock('User');
        $this->testAccount = m::mock('Account');
        $this->mockAddress = m::mock('Address');
        $this->dao = m::mock('SubscriptionSQL');

        $this->testUser->shouldIgnoreMissing();
        $this->testAccount->shouldIgnoreMissing();
        $this->mockAddress->shouldIgnoreMissing();
        $this->dao->shouldIgnoreMissing();

        $this->queryBuilder = m::mock('Illuminate\Database\Query\QueryBuilder');
        $this->db->shouldReceive('beginTransaction')->once();
        $this->db->shouldReceive('table')->withAnyArgs()->once()->andReturn($this->queryBuilder);
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

        $expected = array(
            $this->testAccount,
        );

        $this->db->shouldReceive('table')->with('accounts')->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('join')->times(2)->withAnyArgs()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('select')->once()->withAnyArgs()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('where')->times(2)->withAnyArgs()->andReturn($this->queryBuilder);
        $this->queryBuilder->shouldReceive('get')->once()->andReturn($return);


        $dao = m::mock('AccountSQL');

        $repo = new AccountRepositorySQL($dao, $this->db);
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