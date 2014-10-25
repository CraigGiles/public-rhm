<?php

use Illuminate\Support\Facades\Config;
use redhotmayo\model\User;

class RedHotMayoTestCase extends TestCase {
    const TEST_USER = false;
    private $user;

    public function setUp() {
        parent::setUp();

        $this->setupTestUser();
    }

    public function tearDown() {
        parent::tearDown();

        Mockery::close();
    }

    public function getAuthUser() {
        return $this->user;
    }

    public function getRedHotMayoUser() {
        return User::FromGenericUser($this->getAuthUser());
    }

    private function setupTestUser() {
        if (static::TEST_USER) {
            //generate mocked user and set them to Auth::user()
            $config = json_decode(Config::get(static::TEST_USER), true);
            $this->user = new \Illuminate\Auth\GenericUser($config);
            $this->be($this->user);

            //set UserRepository to return this mocked user
            $mockUserRepo = $this->mock('UserRepository');
            $mockUserRepo->shouldReceive('find')->andReturn(User::FromGenericUser($this->user));
        }
    }

    public function mock($class) {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);

        return $mock;
    }
}
