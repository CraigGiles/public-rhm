<?php
use Illuminate\Support\Facades\DB;
use redhotmayo\dataaccess\repository\sql\SubscriptionRepositorySQL;
use redhotmayo\model\Subscription;

use Mockery as m;

class SubscriptionRepositorySQLTest extends TestCase {
    const ZIPCODE = 1234;

    public function test_subscriptions_can_be_saved() {
//        $queryBuilder = m::mock('Illuminate\Database\Query\QueryBuilder');
//        $user = m::mock('User');
//        $sub = new Subscription();
//
//        DB::shouldReceive('beginTransaction')->once()->andReturn($queryBuilder);
//        DB::shouldReceive('commit')->once();
//        DB::shouldReceive('table')->with('subscriptions')->once()->andReturn($queryBuilder);
//        $queryBuilder->shouldReceive('insertGetId')->withAnyArgs()->once()->andReturn(1);
//
//        $repo = new SubscriptionRepositorySQL();
//
//        $user->shouldReceive('getId')->once()->andReturn(1);
//        $sub->add($user, self::ZIPCODE);
//        $response = $repo->save($sub);
//        $this->assertTrue($response);
    }

    public function test_is_a_user_subscribed_to_zipcode() {
//        $queryBuilder = m::mock('Illuminate\Database\Query\QueryBuilder');
//        $address = m::mock('Address');
//        $address->shouldIgnoreMissing();
//        $user = m::mock('User');
//        $user->shouldIgnoreMissing();
//        $account = m::mock('Account');
//        $account->shouldReceive('getAddress')->andReturn($address);
//        $account->shouldIgnoreMissing();
//
//        $return = array('something');
//
//        DB::shouldReceive('table')->with('addresses')->once()->andReturn($queryBuilder);
//        $queryBuilder->shouldReceive('join')->withAnyArgs()->once()->andReturn($queryBuilder);
//        $queryBuilder->shouldReceive('select')->withAnyArgs()->once()->andReturn($queryBuilder);
//        $queryBuilder->shouldReceive('where')->withAnyArgs()->times(6)->andReturn($queryBuilder);
//        $queryBuilder->shouldReceive('get')->andReturn($return);
//
//        $repo = new SubscriptionRepositorySQL();
//        $results = $repo->isUserSubscribedToAccount($user, $account);
//        $this->assertTrue($results);
    }

}
