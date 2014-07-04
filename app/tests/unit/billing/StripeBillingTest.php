<?php  namespace redhotmayo\billing;

use Mockery\MockInterface;
use redhotmayo\billing\stripe\StripeBilling;
use RedHotMayoTestCase;
use Mockery as m;

class StripeBillingTest extends RedHotMayoTestCase {
    const BILLABLE = '\redhotmayo\billing\Billable';
    const BILLING_REPOSITORY = '\redhotmayo\dataaccess\repository\BillingRepository';
    const BILLING_PLAN = '\redhotmayo\billing\plan\BillingPlan';
    const STRIPE_CUSTOMER = '\redhotmayo\billing\stripe\StripeCustomer';

    /** @var MockInterface $billingRepo */
    protected $billingRepo;

    /** @var MockInterface $billingPlan */
    protected $billingPlan;

    /** @var  MockInterface $billable */
    protected $billable;

    /** @var MockInterface $customer */
    protected $customer;

    /** @var StripeBilling $stripe */
    protected $stripe;

    public function setUp() {
        parent::setUp();

        $this->billable = $this->mock(self::BILLABLE);
        $this->billingRepo = $this->mock(self::BILLING_REPOSITORY);
        $this->billingPlan = $this->mock(self::BILLING_PLAN);
        $this->customer = $this->mock(self::STRIPE_CUSTOMER);
        $this->stripe = new StripeBilling($this->billingRepo, $this->billable, $this->customer);
    }

    public function tearDown() {
        parent::tearDown();
        m::close();
    }

    public function test_it_can_be_instantiated() {
        $this->assertNotNull($this->stripe);
    }

    public function test_it_should_subscribe_a_customer() {
//        $planId = '01';
//        $this->billingPlan->shouldReceive('getId')->once()->andReturn($planId);
//        $this->customer->shouldReceive('subscriptions')->andReturnSelf();
//        $this->customer->shouldReceive('create')->once()->with(['plan' => $planId])->andReturn(true);
//        $this->billingRepo->shouldReceive('save')->withAnyArgs();
//        $this->stripe->subscribe($this->billingPlan);
    }
}
