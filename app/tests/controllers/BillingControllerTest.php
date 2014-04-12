<?php

use Mockery as m;

class BillingControllerTest extends RedHotMayoControllerTestCase {
    const TEST_USER = 'testusers.testuser01';

    const BILLING_REPOSITORY = 'redhotmayo\dataaccess\repository\BillingRepository';

    const ROUTE = 'billing';
    const VALID_INPUT = 'billing.valid_input';

    /** @var \Mockery\MockInterface $billingRepository */
    private $billingRepository;

    public function setUp() {
        parent::setUp();

        $this->billingRepository = $this->mock(self::BILLING_REPOSITORY);
    }
    public function tearDown() {
        parent::tearDown();

        m::close();
    }

    public function test_it_should_save_a_new_billing_object() {
        $this->billingRepository
            ->shouldReceive('save')
            ->once()
            ->andReturn(1);

        $this->callPostWithJson(self::ROUTE, self::VALID_INPUT);
        $this->assertResponseOk();
    }
}
