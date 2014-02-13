<?php namespace redhotmayo\filters;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use redhotmayo\facade\ApiAuthorizer;

class AuthApiFilter {
    const FILTER = 'redhotmayo\filters\AuthApiFilter';

    public function filter() {
        $token = (Request::getMethod() === 'POST') ? Input::json('token') : Input::get('token');
        $authorized = ApiAuthorizer::authorize($token);

        if (!$authorized) {
            return [
                'status' => $authorized,
                'message' => ApiAuthorizer::getMessage()];
        }
    }
} 