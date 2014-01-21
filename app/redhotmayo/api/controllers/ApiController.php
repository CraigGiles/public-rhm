<?php namespace redhotmayo\api\controllers;

use BaseController;
use Illuminate\Support\Facades\Input;
use redhotmayo\facade\ApiAuthorizer;

class ApiController extends BaseController {

    public function authorize() {
        ApiAuthorizer::authorize(Input::all());
    }
} 