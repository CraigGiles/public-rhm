<?php namespace redhotmayo\api\controllers;

use BaseController;
use Exception;
use Illuminate\Support\Facades\Input;
use redhotmayo\facade\ApiAuthorizer;

class ApiSessionController extends BaseController {
    const LOGIN = 'redhotmayo\api\controllers\ApiSessionController@login';

    public function login() {
        $success = false;
        $array = array();

        try {
            $key = ApiAuthorizer::login(Input::get(0));
            $array['token'] = $key;
        } catch (Exception $e) {
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