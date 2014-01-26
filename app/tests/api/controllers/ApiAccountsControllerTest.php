<?php

use Illuminate\Support\Facades\Config;

class ApiAccountControllerTest extends TestCase {

    public function test_testaccount() {
        $json = Config::get('testdata.testupdateaccount01');
//        $json = Config::get('testdata.testupdateaccount01');
        $this->call('POST', 'api/accounts/update', array($json));
    }
}
 