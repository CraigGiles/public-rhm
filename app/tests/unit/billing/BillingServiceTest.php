<?php namespace redhotmayo\billing;

use RedHotMayoTestCase;
use Mockery as m;

class BillingManagerTest extends RedHotMayoTestCase {
    public function setUp() {
        parent::setUp();

    }

    public function tearDown() {
        parent::tearDown();
        m::close();
    }

    public function test_it_should_record_user_billed_for_token() {

    }
}
