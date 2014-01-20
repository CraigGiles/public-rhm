<?php namespace redhotmayo\api\controllers;

use Exception;
use Illuminate\Support\Facades\Input;
use redhotmayo\dataaccess\repository\AccountRepository;

class ApiAccountsController extends ApiController {
    private $accountRepo;

    public function __construct(AccountRepository $accounts) {
        $this->accountRepo = $accounts;
    }
    public function accounts() {
    }

    public function search() {
        $array = array();

        try {
            $conditions = Input::all();
            $success = true;
            $array['data'] = $this->accountRepo->find($conditions);
        } catch (Exception $e) {
            $success = false;
        }

        $array['status'] = $success;
        return $array;
    }

} 