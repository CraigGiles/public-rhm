<?php namespace redhotmayo\api\controllers;

use Exception;
use Illuminate\Support\Facades\Input;
use redhotmayo\api\auth\ApiSession;
use redhotmayo\dataaccess\repository\AccountRepository;
use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\model\Account;

class ApiAccountController extends ApiController {
    const UPDATE = 'redhotmayo\api\controllers\ApiAccountController@store';
    const TARGET = 'redhotmayo\api\controllers\ApiAccountController@target';
    const DELETE = 'redhotmayo\api\controllers\ApiAccountController@delete';
    const DISTANCE = 'redhotmayo\api\controllers\ApiAccountController@distance';
    const SEARCH = 'redhotmayo\api\controllers\ApiAccountController@search';

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
        $values = Input::json()->all();
        $returnArray = [];
        $delete = [];
        $enable = [];
        $success = false;

        try {
            //get current user associated with the API token
            $session = new ApiSession();
            $id = $session->getIdOfAuthedUser($values['token']);

            if (isset($id)) {
                //get all accounts to be deleted from input json
                foreach ($values as $key => $value) {
                    if (is_numeric($key)) {
                        if ($value === "false") {
                            $enable[] = $key;
                        } else {
                            $delete[] = $key;
                        }
                    }
                }

                //get account belongs to user, mark it for deletion
                $userDelete = $this->filterAccounts($id, $delete);
                $userEnabled = $this->filterAccounts($id, $enable);
                $this->accountRepo->markAccountsDeleted($userDelete);
                $this->accountRepo->restoreAccounts($userEnabled);
            }

            $success = true;
        } catch (Exception $e) {
            $returnArray['message'] = $e->getMessage();
        }

        $returnArray ['status'] = $success;
        return $returnArray;
    }

    public function target() {
        $input = Input::all();
        dd($input);
        $array = array();
        $success = false;

        try {
            $values = Input::get('account');
            $accounts = explode(',', $values);
            $this->accountRepo->markAccountsTargeted($accounts);

            $success = true;
        } catch (Exception $e) {
            $array['message'] = $e->getMessage();
        }

        $array ['status'] = $success;
        return $array;
    }

    public function update() {
        dd(Input::all());
    }

    private function filterAccounts($id, $accountIds) {
        $accounts = $this->accountRepo->getAllUsersAccountIds($id);
        $valid = [];

        foreach ($accountIds as $account) {
            if (in_array($account, $accounts)) {
                $valid[] = $account;
            }
        }

        return $valid;
    }
}
