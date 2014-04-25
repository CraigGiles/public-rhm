<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Mockery as m;
use Mockery\MockInterface;

class BillingControllerTest extends RedHotMayoControllerTestCase {
    const TEST_USER = 'testusers.testuser01';

    const BILLING_MANAGER = 'redhotmayo\billing\BillingManager';

    const ROUTE = 'billing';
    const VALID_INPUT = 'billing.valid_input';
    const INPUT_NO_TOKEN = 'billing.input_with_no_token';

    /** @var \Mockery\MockInterface $billingManager */
    private $billingManager;

    public function setUp() {
        parent::setUp();

        $this->billingManager = $this->mock(self::BILLING_MANAGER);
    }
    public function tearDown() {
        parent::tearDown();

        m::close();
    }

    public function test_should_redirect_back_when_billing_token_not_found() {
        $this->billingManager->shouldIgnoreMissing();
        $this->shouldRedirectWithErrors();

        $this->callPostWithArray(self::ROUTE, self::INPUT_NO_TOKEN);
    }

    public function test_it_should_save_a_new_billing_object() {
        $input = Config::get(self::VALID_INPUT);
        $input = json_decode($input, true);

        $this->setupBillingManager($input);
        $this->callPostWithArray(self::ROUTE, self::VALID_INPUT);
        $this->assertResponseOk();
    }

    public function test_it_should_throw_billing_exception() {
        $input = Config::get(self::VALID_INPUT);
        $input = json_decode($input, true);

        $this->shouldRedirectWithErrors();
        $this->setupBillingManager($input)
            ->andThrow('redhotmayo\billing\exception\BillingException');

        $this->callPostWithArray(self::ROUTE, self::VALID_INPUT);
    }

    public function test_it_should_tie_subscription_to_user() {
        $input = Config::get(self::VALID_INPUT);
        $input = json_decode($input, true);

        $this->setupBillingManager($input)->with($this->getAuthUser(), $input['stripeToken']);

        $this->callPostWithArray(self::ROUTE, self::VALID_INPUT);
    }

    private function setupBillingManager($input) {
        return $this->billingManager
            ->shouldReceive('addBasicSubscription')
            ->once();
    }

    private function shouldRedirectWithErrors() {
        /** @var MockInterface Redirect */
        Redirect::shouldReceive('action')->once()->andReturnSelf();
        Redirect::shouldReceive('withInput')->once()->andReturnSelf();
        Redirect::shouldReceive('withErrors')->once();
        Redirect::shouldIgnoreMissing();
    }
}
