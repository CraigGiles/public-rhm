<?php namespace redhotmayo\api\controllers;

use BaseController;
use Illuminate\Support\Facades\Input;
use redhotmayo\facade\ApiAuthorizer;

class ApiController extends BaseController {

    public function authorize() {
        $response = ApiAuthorizer::authorize(Input::all());
        return $response;
    }
} 