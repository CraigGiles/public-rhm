<?php namespace redhotmayo\api\controllers;

use Exception;
use Illuminate\Support\Facades\Input;
use redhotmayo\api\auth\AccountFilter;
use redhotmayo\api\auth\ApiSession;
use redhotmayo\dataaccess\repository\AccountRepository;
use redhotmayo\dataaccess\repository\dao\DataAccessObject;
use redhotmayo\model\Account;

class ApiAccountController extends ApiController {
    const UPDATE = 'redhotmayo\api\controllers\ApiAccountController@update';
    const TARGET = 'redhotmayo\api\controllers\ApiAccountController@target';
    const DELETE = 'redhotmayo\api\controllers\ApiAccountController@delete';
    const DISTANCE = 'redhotmayo\api\controllers\ApiAccountController@distance';
    const SEARCH = 'redhotmayo\api\controllers\ApiAccountController@search';

    private $accountRepo;

    public function __construct(AccountRepository $accounts) {
        $this->accountRepo = $accounts;
    }

    public function store() {

    }

    public function search() {
        $array = array();

        try {
            $conditions = Input::all();

            //get current user associated with the API token
            $session = new ApiSession();
            $userId = $session->getIdOfAuthedUser($conditions['token']);
            unset($conditions['token']);

            $conditions['userId'] = $userId;
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
        $success = false;

        try {
            //get current user associated with the API token
            $session = new ApiSession();
            $id = $session->getIdOfAuthedUser($values['token']);

            if (isset($id)) {
                //get all accounts to be deleted from input json
                $accounts = $this->getAccountToggles($values);

                //get account belongs to user, mark it for deletion
                $userDelete = $this->filterAccountIds($id, $accounts['true']);
                $userEnabled = $this->filterAccountIds($id, $accounts['false']);
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
        $values = Input::json()->all();
        $array = array();
        $success = false;

        try {
            //get current user associated with the API token
            $session = new ApiSession();
            $id = $session->getIdOfAuthedUser($values['token']);

            if (isset($id)) {
                $accounts = $this->getAccountToggles($values);
                $target = isset($accounts['true']) ? $this->filterAccountIds($id, $accounts['true']) : [];
                $untarget = isset($accounts['false']) ? $this->filterAccountIds($id, $accounts['false']) : [];
                $this->accountRepo->markAccountsTargeted($target, true);
                $this->accountRepo->markAccountsTargeted($untarget, false);
            }

            $success = true;
        } catch (Exception $e) {
            $array['message'] = $e->getMessage();
        }

        $array ['status'] = $success;
        return $array;
    }

    public function update() {
        $array = array();
        $unsaved = array();
        $success = false;
        try {
            $input = Input::json()->all();
            //get current user associated with the API token
            $session = new ApiSession();
            $id = $session->getIdOfAuthedUser($input['token']);

            $accounts = isset($input['accounts']) ? $input['accounts'] : [];

            //todo: add account filter here
            //$accounts = $this->accountFilter->filterAccountCollection($accounts);

            foreach ($accounts as $account) {
                $save = Account::FromArray($account);
                $unsaved[] = $this->accountRepo->save($save);
            }

            $success = true;
        } catch (Exception $e) {
            $array['message'] = $e->getMessage();
        }

        $array['status'] = $success;

        return $array;
    }

    private function filterAccountIds($id, $accountIds) {
        $accounts = $this->accountRepo->getAllUsersAccountIds($id);
        $valid = [];
        foreach ($accountIds as $account) {
            if (in_array($account, $accounts)) {
                $valid[] = $account;
            }
        }
        return $valid;
    }

    private function getAccountToggles($values) {
        $false = [];
        $true = [];

        foreach ($values as $key => $value) {
            if (is_numeric($key)) {
                if ($value === false) {
                    $false[] = $key;
                } else {
                    $true[] = $key;
                }
            }
        }

        return ['true' => $true, 'false' => $false];
    }
}
