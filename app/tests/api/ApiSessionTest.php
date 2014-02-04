<?php namespace redhotmayo\api\auth;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Mockery;
use PHPUnit_Framework_TestCase;
use TestCase;

class ApiSessionTest extends TestCase {
    const USER = 'redhotmayo\model\User';

    public function test_me() {
//        $json = '{"username":"testuser01", "token":1234}';
//        $response = $this->call('POST', 'api/authorize', array($json));
//        dd($response);

        $json = Config::get('testdata.apilogin');
        $this->call('POST', 'api/login', array($json));
    }

    public function testLoginWithNewUser() {
//        $api = new ApiSession();
//        $userMock = Mockery::mock(self::USER);
////        $queryBuilderMock = Mockery::mock('Illuminate\Database\Query\Builder');
//
////        DB::shouldReceive('table')->withAnyArgs()->andReturn($queryBuilderMock);
//        $userMock->shouldReceive('getUserId')->once()->andReturn(1234);
////        $queryBuilderMock->shouldReceive('where')->withAnyArgs()->andReturn([]);
//
//
//        $api->login($userMock, '1234');
    }

    public function testCreateNewSession() {
        $api = new ApiSession();
        $userMock = Mockery::mock('redhotmayo\model\User');
        $queryBuilderMock = Mockery::mock('Illuminate\Database\Query\Builder');

        $userMock->shouldReceive('getUserId')->once()->andReturn(1234);
        $queryBuilderMock->shouldReceive('insertGetId')->withAnyArgs()->andReturn(1);
        DB::shouldReceive('table')->withAnyArgs()->andReturn($queryBuilderMock);

        $key = $api->create($userMock);
        $this->assertEquals(40, strlen($key));
    }
}
 