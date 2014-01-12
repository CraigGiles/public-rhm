<?php

use Mockery as m;

class AccountsControllerTest extends TestCase {
    public function tearDown() {
        m::close();
    }

    public function test_get_accounts_index_page() {
//        $records[] = m::mock('Account');
//        $records[0]->shouldReceive('toJson')
//                   ->once()
//                   ->andReturn(1234);
//
//        $mock = m::mock('redhotmayo\dataaccess\repository\AccountRepository');
//        $mock->shouldReceive('all')
//             ->once()
//             ->andReturn($records);
//
//        $this->app->instance('redhotmayo\dataaccess\repository\AccountRepository', $mock);
//
//        $this->get('accounts');
//        $this->assertResponseOk();
    }

    public function test_finding_all_accounts_by_zipcode() {

    }
} 