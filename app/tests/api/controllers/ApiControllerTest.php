<?php

class ApiControllerTest extends TestCase {
    public function test_post() {
        $json = '{"username":"testuser01", "token":1234}';
        $this->call('POST', 'api/authorize', array($json));
    }
}
 