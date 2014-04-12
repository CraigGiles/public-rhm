<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {
    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication() {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__ . '/../../bootstrap/start.php';
    }

    public function mock($class) {
        $mock = Mockery::mock($class);
        $this->app->instance($class, $mock);

        return $mock;
    }

    public function postWithJson($route, $config) {
        $this->call('POST', $route, [], [], [], Config::get($config));
    }

    public function postWithArray($route, $config) {
        $this->call('POST', $route, $this->getTestDataAsArray($config));
    }

    private function getTestDataAsArray($config) {
        $rawData = Config::get($config);
        $data = json_decode($rawData, true);
        Input::replace($input = $data);

        return $input;
    }

}
