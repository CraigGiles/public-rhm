<?php 

class RedHotMayoTestCase extends TestCase {
    public function setUp() {
        parent::setUp();

        $this->setupTestUser();
    }

    private function setupTestUser() {
        if (static::TEST_USER) {
            $config = json_decode(Config::get(static::TEST_USER), true);
            $user = new \Illuminate\Auth\GenericUser($config);
            $this->be($user);
        }
    }

    public function mock($class) {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);

        return $mock;
    }
}
