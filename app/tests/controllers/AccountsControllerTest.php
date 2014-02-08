<?php

use Illuminate\Support\Facades\Config;
use Mockery as m;

class AccountsControllerTest extends TestCase {
    public function tearDown() {
        m::close();
    }

    public function test_register_new_user() {
        $json = Config::get('testdata.register_user');
//        $this->call('post', ['/users/new', $json]);
        $this->call('POST', '/users/new', array($json));
    }
//    public function test_post_accounts() {
////        $json = Config::get('testdata.testaccount01');
////        $this->post('accounts', $json);
//    }
//
//    public function test_get_accounts_index_page() {
////        $records[] = m::mock('Account');
////        $records[0]->shouldReceive('toJson')
////                   ->once()
////                   ->andReturn(1234);
////
////        $mock = m::mock('redhotmayo\dataaccess\repository\AccountRepository');
////        $mock->shouldReceive('all')
////             ->once()
////             ->andReturn($records);
////
////        $this->app->instance('redhotmayo\dataaccess\repository\AccountRepository', $mock);
////
////        $this->get('accounts');
////        $this->assertResponseOk();
//    }
//
//    public function test_finding_all_accounts_by_zipcode() {
//
//    }
} 