<?php namespace redhotmayo\api\controllers;

use BaseController;
use Exception;
use Illuminate\Support\Facades\Input;
use redhotmayo\facade\ApiAuthorizer;

class ApiSessionController extends BaseController {
    const LOGIN = 'redhotmayo\api\controllers\ApiSessionController@login';

    public function login() {
        $array = array();

        try {
            $input = Input::json()->all();
            $key = ApiAuthorizer::login($input);
            $array['token'] = $key;
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $array['message'] = $e->getMessage();
        }

        $array['status'] = $success;

        return $array;
    }

    public function store() {
    }

    public function destroy() {
    }
} 