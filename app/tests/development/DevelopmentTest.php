<?php namespace redhotmayo\development;

use Illuminate\Support\Facades\Config;
use Mockery\CountValidator\Exception;
use PHPUnit_Framework_TestCase;
use redhotmayo\dataaccess\repository\sql\UserRepositorySQL;
use redhotmayo\registration\MobileRegistrationValidator;
use redhotmayo\registration\Registration;
use redhotmayo\validation\ValidationException;
use TestCase;

class DevelopmentTest extends TestCase {
    public function test_it_should_verify_valid_api_json() {
        try {
            $json = Config::get('testdata.register_user');
            $results = $this->call('post', '/api/users/new', [$json]);
            var_dump("Got a reply");
            dd($results);
        } catch (ValidationException $e) {
            dd($e->getErrors());
        }
//        $validator = new MobileRegistrationValidator();
//        $userRepo = new UserRepositorySQL();
//        $json = Config::get('testdata.register_user');
//        $input = json_decode($json, true);
//
//        try {
//            $r = new Registration($userRepo);
//            $registered = $r->register($input, $validator);
//            dd($registered);
//        } catch (ValidationException $e) {
//            dd($e->getErrors());
//        }
    }
}
 