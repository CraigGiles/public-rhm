<?php namespace redhotmayo\registration;

use Illuminate\Support\Facades\Config;
use Mockery as m;
use redhotmayo\dataaccess\repository\sql\UserRepositorySQL;
use TestCase;

class RegistrationTest extends TestCase {
    public function tearDown() {
        m::close();
    }

    public function test_it_should_verify_valid_api_json() {
        $json = Config::get('testdata.register_user');
        $input = json_decode($json, true);
        $validator = new MobileRegistrationValidator();
        $r = new Registration(new UserRepositorySQL());
        $registered = $r->register($input, $validator);
        $this->assertTrue($registered);
    }

}
 