<?php namespace api\controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use TestCase;
use Mockery as m;

class ApiNoteControllerTest extends TestCase {
    public function tearDown() {
        m::close();
    }

    public function test_getting_it_to_work() {
        $json = Config::get('testdata.testnote01');

        $results = $this->call('POST', 'api/notes/add', array($json));
        dd($results);
    }

    public function test_add_note_to_existing_account() {
//        $acct = Config::get('testaccount01');
//        $queryBuilder = m::mock('Illuminate\Database\Query\QueryBuilder');
//        $queryBuilder->shouldReceive('where')->once()->with('accountId', '=', '1')->andReturn($acct);
//        $mock = DB::shouldReceive('table')->with('accounts')->andReturn($queryBuilder);
    }
}
 