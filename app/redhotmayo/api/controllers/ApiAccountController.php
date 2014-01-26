<?php namespace redhotmayo\api\controllers;

use Exception;
use Illuminate\Support\Facades\Input;
use redhotmayo\dataaccess\repository\AccountRepository;
use redhotmayo\model\Account;

class ApiAccountController extends ApiController {
    const UPDATE = 'redhotmayo\api\controllers\ApiAccountController@store';
    private $accountRepo;

    public function __construct(AccountRepository $accounts) {
        $this->accountRepo = $accounts;
    }

    public function store() {
        $array = array();
        $unsaved = array();
        $success = false;

        try {
            $accountsAsJson = Input::all();
            $accountsAsJson = $accountsAsJson[0];
            $stdObjects = json_decode($accountsAsJson);
            $stdAccounts = $stdObjects->accounts;
            foreach ($stdAccounts as $account) {
                $save = Account::FromStdClass($account);
                $unsaved[] = $this->accountRepo->save($save);
            }

            $success = true;
        } catch (Exception $e) {
            $array['message'] = $e->getMessage();
        }

        $array['status'] = $success;

        return $array;
    }

    public function search() {
        $array = array();

        try {
            $conditions = Input::all();
            $success = true;
            $array['data'] = $this->accountRepo->find($conditions);
        } catch (Exception $e) {
            $success = false;
            $array['message'] = $e->getMessage();
        }

        $array['status'] = $success;
        return $array;
    }

    public function distance() {
        dd(Input::all());
    }

    public function delete() {
        dd(Input::all());
    }

    public function target() {
        dd(Input::all());
    }

    public function update() {
        dd(Input::all());
    }
}
