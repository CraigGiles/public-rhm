<?php 

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

    private function setupTestUser() {
        if (static::TEST_USER) {
            $config = json_decode(Config::get(static::TEST_USER), true);
            $this->user = new \Illuminate\Auth\GenericUser($config);
            $this->be($this->user);
        }
    }

    public function mock($class) {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);

        return $mock;
    }
}
