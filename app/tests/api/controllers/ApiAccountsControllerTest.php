<?php

use Illuminate\Support\Facades\Config;

class ApiAccountControllerTest extends TestCase {

    public function test_testaccount() {
        $json = Config::get('testdata.testaccount01');

        $this->call('POST', 'api/accounts/update', array($json));
    }
}
 