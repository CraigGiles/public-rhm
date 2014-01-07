<?php

class SubscriptionTest extends TestCase {
    public function setUp() {

    }

    public function test_users_should_be_able_to_subscribe_to_zipcode() {
        $sub = new Subscription();
        $subDAO = DataAccess::getDAO(DataAccessObject::SUBSCRIPTION);
        $subDAO->save($subDAO);
        $sub->add(1, 12345);
    }
}

